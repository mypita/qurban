<?php
session_start();
require_once './services/konek.php'; // Pastikan path ini benar

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';
$order_details = null; // Untuk menyimpan detail pesanan setelah checkout sukses

// Fungsi untuk membersihkan input (jika belum ada di konek.php)
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

// Ambil data user untuk pre-fill form
$user_query = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_query->close();

// Ambil daftar rekening bank
$bank_accounts = [];
$bank_query = $conn->query("SELECT id, bank_name, account_number, account_owner FROM bank_accounts WHERE is_active = 1");
if ($bank_query) {
    while ($row = $bank_query->fetch_assoc()) {
        $bank_accounts[] = $row;
    }
} else {
    error_log("Failed to fetch bank accounts: " . $conn->error);
}


// --- Proses Form Checkout ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $delivery_address = clean_input($_POST['delivery_address']);
    $delivery_date = clean_input($_POST['delivery_date']);
    $phone = clean_input($_POST['phone']);
    $payment_method = clean_input($_POST['payment_method']);
    $bank_account_id = ($payment_method == 'manual_transfer' && isset($_POST['bank_account_id'])) ? clean_input($_POST['bank_account_id']) : null;

    $payment_proof = null;

    // Validasi input
    if (empty($delivery_address) || empty($delivery_date) || empty($phone) || empty($payment_method)) {
        $error_message = "Semua kolom wajib diisi.";
    } elseif ($payment_method == 'manual_transfer' && empty($bank_account_id)) {
        $error_message = "Pilih rekening bank tujuan transfer.";
    } else {
        // Handle file upload
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "assets/payment_proofs/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Buat direktori jika belum ada
            }

            $file_extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid('proof_') . '.' . $file_extension;
            $target_file = $target_dir . $new_file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Periksa tipe file
            $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($imageFileType, $allowed_types)) {
                $error_message = "Maaf, hanya file JPG, JPEG, PNG, & PDF yang diizinkan.";
            } elseif ($_FILES['payment_proof']['size'] > 5000000) { // 5MB max size
                $error_message = "Maaf, ukuran file terlalu besar (maks 5MB).";
            } else {
                if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
                    $payment_proof = $target_file;
                } else {
                    $error_message = "Gagal mengupload bukti transfer.";
                }
            }
        } else {
            $error_message = "Bukti transfer wajib diupload.";
        }
    }

    if (empty($error_message)) {
        // Mulai transaksi database
        $conn->begin_transaction();

        try {
            // Ambil item keranjang untuk perhitungan total dan pengurangan stok
            $cart_items = [];
            $total_price = 0;
            $cart_query = $conn->prepare("SELECT c.quantity, a.id as animal_id, a.price, a.stock FROM carts c JOIN animals a ON c.animal_id = a.id WHERE c.user_id = ?");
            $cart_query->bind_param("i", $user_id);
            $cart_query->execute();
            $cart_result = $cart_query->get_result();

            if ($cart_result->num_rows == 0) {
                throw new Exception("Keranjang Anda kosong.");
            }

            while ($item = $cart_result->fetch_assoc()) {
                if ($item['stock'] < $item['quantity']) {
                    throw new Exception("Stok untuk " . htmlspecialchars($item['description']) . " tidak mencukupi.");
                }
                $cart_items[] = $item;
                $total_price += $item['quantity'] * $item['price'];
            }
            $cart_query->close();

            // 1. Masukkan data ke tabel orders
            $insert_order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, delivery_address, delivery_date, phone, payment_proof, status, payment_method, bank_account_id) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)");
            $insert_order_stmt->bind_param("idsssssi", $user_id, $total_price, $delivery_address, $delivery_date, $phone, $payment_proof, $payment_method, $bank_account_id);
            if (!$insert_order_stmt->execute()) {
                throw new Exception("Gagal membuat pesanan: " . $insert_order_stmt->error);
            }
            $order_id = $insert_order_stmt->insert_id;
            $insert_order_stmt->close();

            // 2. Masukkan item keranjang ke tabel order_items dan kurangi stok hewan
            $insert_item_stmt = $conn->prepare("INSERT INTO order_items (order_id, animal_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
            $update_stock_stmt = $conn->prepare("UPDATE animals SET stock = stock - ? WHERE id = ?");

            foreach ($cart_items as $item) {
                // Masukkan ke order_items
                $insert_item_stmt->bind_param("iiid", $order_id, $item['animal_id'], $item['quantity'], $item['price']);
                if (!$insert_item_stmt->execute()) {
                    throw new Exception("Gagal menambahkan item pesanan: " . $insert_item_stmt->error);
                }

                // Kurangi stok hewan
                $update_stock_stmt->bind_param("ii", $item['quantity'], $item['animal_id']);
                if (!$update_stock_stmt->execute()) {
                    throw new Exception("Gagal mengurangi stok hewan: " . $update_stock_stmt->error);
                }
            }
            $insert_item_stmt->close();
            $update_stock_stmt->close();

            // 3. Kosongkan keranjang pengguna
            $clear_cart_stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ?");
            $clear_cart_stmt->bind_param("i", $user_id);
            if (!$clear_cart_stmt->execute()) {
                throw new Exception("Gagal mengosongkan keranjang: " . $clear_cart_stmt->error);
            }
            $clear_cart_stmt->close();

            // Commit transaksi
            $conn->commit();
            $success_message = "Pesanan Anda berhasil ditempatkan! Kami akan segera memprosesnya.";

            // Simpan order_id di session untuk ditampilkan setelah redirect
            $_SESSION['last_order_id'] = $order_id;
            header("Location: checkout.php?order_placed=true");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Terjadi kesalahan saat memproses pesanan: " . $e->getMessage();
            // Hapus file bukti transfer jika sudah terupload tapi transaksi gagal
            if ($payment_proof && file_exists($payment_proof)) {
                unlink($payment_proof);
            }
        }
    }
}

// --- Ambil Detail Pesanan Terakhir Jika Ada (setelah redirect sukses) ---
if (isset($_GET['order_placed']) && isset($_SESSION['last_order_id'])) {
    $last_order_id = $_SESSION['last_order_id'];
    unset($_SESSION['last_order_id']); // Hapus dari session agar tidak tampil lagi setelah refresh

    $order_query = $conn->prepare("SELECT o.*, ba.bank_name, ba.account_number, ba.account_owner FROM orders o LEFT JOIN bank_accounts ba ON o.bank_account_id = ba.id WHERE o.id = ? AND o.user_id = ?");
    $order_query->bind_param("ii", $last_order_id, $user_id);
    $order_query->execute();
    $order_result = $order_query->get_result();
    $order_details = $order_result->fetch_assoc();
    $order_query->close();

    if ($order_details) {
        $order_items_query = $conn->prepare("SELECT oi.quantity, oi.price_per_unit, a.type, a.description, a.image_url FROM order_items oi JOIN animals a ON oi.animal_id = a.id WHERE oi.order_id = ?");
        $order_items_query->bind_param("i", $last_order_id);
        $order_items_query->execute();
        $order_items_result = $order_items_query->get_result();
        $order_details['items'] = [];
        while ($item = $order_items_result->fetch_assoc()) {
            $order_details['items'][] = $item;
        }
        $order_items_query->close();
    }
}

// --- Ambil Item Keranjang Saat Ini (jika belum ada pesanan yang berhasil) ---
$cart_items = [];
$total_cart_price = 0;
if (!$order_details) { // Hanya ambil item keranjang jika tidak menampilkan detail pesanan yang berhasil
    $stmt = $conn->prepare("SELECT c.id as cart_item_id, c.quantity, a.id as animal_id, a.type, a.description, a.price, a.image_url, a.weight_group, a.weight_range, a.stock FROM carts c JOIN animals a ON c.animal_id = a.id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_cart_price += $row['quantity'] * $row['price'];
    }
    $stmt->close();
}

?>

<?php include 'components/header.php'; ?>

<main>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <h2 class="mb-4 text-center">Checkout Pesanan Qurban</h2>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <?= $success_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($order_details): ?>
                        <!-- Tampilan Setelah Checkout Berhasil -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Pesanan Berhasil Ditempatkan!</h5>
                            </div>
                            <div class="card-body">
                                <p>Terima kasih atas pesanan Anda. Berikut adalah detail pesanan Anda:</p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><strong>ID Pesanan:</strong> <?= htmlspecialchars($order_details['id']) ?></li>
                                    <li class="list-group-item"><strong>Total Harga:</strong> Rp <?= number_format($order_details['total_price'], 0, ',', '.') ?></li>
                                    <li class="list-group-item"><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($order_details['delivery_address']) ?></li>
                                    <li class="list-group-item"><strong>Tanggal Pengiriman:</strong> <?= htmlspecialchars($order_details['delivery_date']) ?></li>
                                    <li class="list-group-item"><strong>Telepon:</strong> <?= htmlspecialchars($order_details['phone']) ?></li>
                                    <li class="list-group-item"><strong>Metode Pembayaran:</strong> <?= htmlspecialchars(ucwords(str_replace('_', ' ', $order_details['payment_method']))) ?></li>
                                    <?php if ($order_details['payment_method'] == 'manual_transfer' && $order_details['bank_name']): ?>
                                        <li class="list-group-item"><strong>Tujuan Transfer:</strong> <?= htmlspecialchars($order_details['bank_name']) ?> (<?= htmlspecialchars($order_details['account_number']) ?>) a.n. <?= htmlspecialchars($order_details['account_owner']) ?></li>
                                    <?php endif; ?>
                                    <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-info"><?= htmlspecialchars(ucwords($order_details['status'])) ?></span></li>
                                    <?php if ($order_details['payment_proof']): ?>
                                        <li class="list-group-item"><strong>Bukti Transfer:</strong> <a href="<?= htmlspecialchars($order_details['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-file-earmark-image"></i> Lihat Bukti</a></li>
                                    <?php endif; ?>
                                </ul>

                                <h5>Item Pesanan:</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Hewan</th>
                                                <th>Deskripsi</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($order_details['items'] as $item): ?>
                                                <tr>
                                                    <td><img src="assets/images/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['description']) ?>" width="50" class="rounded"> <?= htmlspecialchars(ucwords($item['type'])) ?></td>
                                                    <td><?= htmlspecialchars($item['description']) ?></td>
                                                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                    <td>Rp <?= number_format($item['price_per_unit'], 0, ',', '.') ?></td>
                                                    <td>Rp <?= number_format($item['quantity'] * $item['price_per_unit'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="paket.php" class="btn btn-primary">Lanjut Belanja</a>
                                    <a href="orders.php" class="btn btn-outline-secondary">Lihat Semua Pesanan Saya</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Tampilan Keranjang dan Form Checkout -->
                        <?php if (empty($cart_items)): ?>
                            <div class="alert alert-info text-center" role="alert">
                                Keranjang Anda kosong. Silakan <a href="paket.php">pilih hewan qurban</a> terlebih dahulu.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Ringkasan Pesanan Anda</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Hewan</th>
                                                            <th>Deskripsi</th>
                                                            <th>Jumlah</th>
                                                            <th>Harga</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($cart_items as $item): ?>
                                                            <tr>
                                                                <td>
                                                                    <img src="assets/images/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['description']) ?>" width="50" class="rounded me-2">
                                                                    <?= htmlspecialchars(ucwords($item['type'])) ?>
                                                                </td>
                                                                <td>
                                                                    <?= htmlspecialchars($item['description']) ?><br>
                                                                    <small class="text-muted"><?= htmlspecialchars($item['weight_group']) ?> (<?= htmlspecialchars($item['weight_range']) ?>)</small>
                                                                </td>
                                                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                                <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                                                <td>Rp <?= number_format($item['quantity'] * $item['price'], 0, ',', '.') ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="4" class="text-end">Total Harga:</th>
                                                            <th>Rp <?= number_format($total_cart_price, 0, ',', '.') ?></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">Detail Pengiriman & Pembayaran</h5>
                                        </div>
                                        <div class="card-body">
                                            <form action="checkout.php" method="POST" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="delivery_address" class="form-label">Alamat Pengiriman Lengkap</label>
                                                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3" required><?= htmlspecialchars($user_data['address'] ?? '') ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="delivery_date" class="form-label">Tanggal Pengiriman Qurban</label>
                                                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" required min="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                                                    <small class="form-text text-muted">Tanggal pengiriman minimal 7 hari dari sekarang.</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Nomor Telepon (Aktif WhatsApp)</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user_data['phone'] ?? '') ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                                        <option value="manual_transfer" selected>Transfer Bank Manual</option>
                                                        <!-- <option value="midtrans">Midtrans (Coming Soon)</option> -->
                                                    </select>
                                                </div>
                                                <div class="mb-3" id="bank_account_selection">
                                                    <label for="bank_account_id" class="form-label">Pilih Rekening Tujuan Transfer</label>
                                                    <select class="form-select" id="bank_account_id" name="bank_account_id" required>
                                                        <option value="">-- Pilih Rekening --</option>
                                                        <?php foreach ($bank_accounts as $bank): ?>
                                                            <option value="<?= htmlspecialchars($bank['id']) ?>">
                                                                <?= htmlspecialchars($bank['bank_name']) ?> - <?= htmlspecialchars($bank['account_number']) ?> a.n. <?= htmlspecialchars($bank['account_owner']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_proof" class="form-label">Upload Bukti Transfer</label>
                                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required>
                                                    <small class="form-text text-muted">Format: JPG, PNG, PDF. Maks 5MB.</small>
                                                </div>
                                                <button type="submit" name="place_order" class="btn btn-success w-100">Tempatkan Pesanan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'components/footer.php'; ?>

<script>
    // JavaScript untuk menampilkan/menyembunyikan pilihan rekening bank berdasarkan metode pembayaran
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method');
        const bankAccountSelectionDiv = document.getElementById('bank_account_selection');

        function toggleBankAccountSelection() {
            if (paymentMethodSelect.value === 'manual_transfer') {
                bankAccountSelectionDiv.style.display = 'block';
                bankAccountSelectionDiv.querySelector('select').setAttribute('required', 'required');
            } else {
                bankAccountSelectionDiv.style.display = 'none';
                bankAccountSelectionDiv.querySelector('select').removeAttribute('required');
            }
        }

        // Panggil saat halaman dimuat
        toggleBankAccountSelection();

        // Panggil saat pilihan metode pembayaran berubah
        paymentMethodSelect.addEventListener('change', toggleBankAccountSelection);

        // Set minimum date for delivery_date input
        const deliveryDateInput = document.getElementById('delivery_date');
        const today = new Date();
        const minDate = new Date();
        minDate.setDate(today.getDate() + 7); // Minimum 7 days from now

        const year = minDate.getFullYear();
        const month = String(minDate.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(minDate.getDate()).padStart(2, '0');
        deliveryDateInput.min = `${year}-${month}-${day}`;
    });
</script>
