<?php
session_start();
require_once 'services/konek.php'; // Pastikan path ini benar

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_orders = [];
$error_message = '';

// Fungsi untuk membersihkan input (jika belum ada di konek.php)
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

try {
    // Ambil semua pesanan untuk user yang sedang login
    $orders_query = $conn->prepare("
        SELECT 
            o.id, o.total_price, o.delivery_address, o.delivery_date, o.phone, 
            o.payment_proof, o.status, o.payment_method, o.created_at,
            ba.bank_name, ba.account_number, ba.account_owner
        FROM orders o
        LEFT JOIN bank_accounts ba ON o.bank_account_id = ba.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    if (!$orders_query) {
        throw new Exception("Failed to prepare orders query: " . $conn->error);
    }
    $orders_query->bind_param("i", $user_id);
    $orders_query->execute();
    $orders_result = $orders_query->get_result();

    while ($order = $orders_result->fetch_assoc()) {
        // Untuk setiap pesanan, ambil item-itemnya
        $order_items_query = $conn->prepare("
            SELECT 
                oi.quantity, oi.price_per_unit, a.type, a.description, a.image_url, 
                a.weight_group, a.weight_range
            FROM order_items oi
            JOIN animals a ON oi.animal_id = a.id
            WHERE oi.order_id = ?
        ");
        if (!$order_items_query) {
            throw new Exception("Failed to prepare order items query: " . $conn->error);
        }
        $order_items_query->bind_param("i", $order['id']);
        $order_items_query->execute();
        $order_items_result = $order_items_query->get_result();
        
        $order['items'] = [];
        while ($item = $order_items_result->fetch_assoc()) {
            $order['items'][] = $item;
        }
        $order_items_query->close();
        
        $user_orders[] = $order;
    }
    $orders_query->close();

} catch (Exception $e) {
    $error_message = "Terjadi kesalahan saat mengambil data pesanan: " . $e->getMessage();
    error_log($error_message);
}

$active = 'orders'; // Untuk menandai menu aktif di header

// Definisi teks status yang konsisten dengan halaman admin Anda
$status_text_map = [
    'pending' => 'Menunggu Pembayaran',
    'paid' => 'Telah Dibayar',
    'processed' => 'Diproses',
    'shipped' => 'Dikirim',
    'delivered' => 'Terkirim',
    'cancelled' => 'Dibatalkan'
];
?>

<?php include 'components/header.php'; ?>

<main>
    <section class="section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold text-center">Pesanan Saya</h2>
                </div>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <?= $error_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Area untuk menampilkan pesan AJAX (tetap ada jika ada fitur AJAX lain di masa depan) -->
            <div id="ajax-message-area" class="mb-4"></div>

            <?php if (empty($user_orders)): ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-box-seam fa-4x text-muted mb-4" style="font-size: 4rem;"></i>
                        <h4 class="mb-3">Anda belum memiliki pesanan.</h4>
                        <p class="text-muted mb-4">Mulai berqurban sekarang dengan memilih hewan terbaik kami.</p>
                        <a href="paket.php" class="btn btn-primary">Lihat Paket Qurban</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="accordion" id="ordersAccordion">
                    <?php foreach ($user_orders as $index => $order): ?>
                        <div class="accordion-item shadow-sm mb-3">
                            <h2 class="accordion-header" id="heading<?= $order['id'] ?>">
                                <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $order['id'] ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $order['id'] ?>">
                                    <strong>Pesanan #<?= htmlspecialchars($order['id']) ?></strong> 
                                    <span class="ms-3 badge bg-<?= 
                                        $order['status'] == 'pending' ? 'warning text-dark' : 
                                        ($order['status'] == 'paid' ? 'info' : 
                                        ($order['status'] == 'processed' ? 'primary' : 
                                        ($order['status'] == 'shipped' ? 'secondary' : 
                                        ($order['status'] == 'delivered' ? 'success' : 'danger')))) 
                                    ?>">
                                        <?= htmlspecialchars($status_text_map[$order['status']] ?? ucwords($order['status'])) ?>
                                    </span>
                                    <span class="ms-auto text-muted">Total: Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                                </button>
                            </h2>
                            <div id="collapse<?= $order['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $order['id'] ?>" data-bs-parent="#ordersAccordion">
                                <div class="accordion-body">
                                    <h5 class="mb-3">Detail Pesanan</h5>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item"><strong>Tanggal Pesan:</strong> <?= date('d F Y H:i', strtotime($order['created_at'])) ?></li>
                                        <li class="list-group-item"><strong>Tanggal Pengiriman:</strong> <?= date('d F Y', strtotime($order['delivery_date'])) ?></li>
                                        <li class="list-group-item"><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($order['delivery_address']) ?></li>
                                        <li class="list-group-item"><strong>Telepon:</strong> <?= htmlspecialchars($order['phone']) ?></li>
                                        <li class="list-group-item"><strong>Metode Pembayaran:</strong> <?= htmlspecialchars(ucwords(str_replace('_', ' ', $order['payment_method']))) ?></li>
                                        <?php if ($order['payment_method'] == 'manual_transfer' && $order['bank_name']): ?>
                                            <li class="list-group-item"><strong>Tujuan Transfer:</strong> <?= htmlspecialchars($order['bank_name']) ?> (<?= htmlspecialchars($order['account_number']) ?>) a.n. <?= htmlspecialchars($order['account_owner']) ?></li>
                                        <?php endif; ?>
                                        <li class="list-group-item" id="payment-proof-row-<?= $order['id'] ?>">
                                            <strong>Bukti Transfer:</strong> 
                                            <?php if ($order['payment_proof']): 
                                                $file_extension = pathinfo($order['payment_proof'], PATHINFO_EXTENSION);
                                                $is_image = in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif']);
                                            ?>
                                                <?php if ($is_image): ?>
                                                    <div class="mt-2">
                                                        <a href="<?= htmlspecialchars($order['payment_proof']) ?>" target="_blank">
                                                            <img src="<?= htmlspecialchars($order['payment_proof']) ?>" alt="Bukti Transfer" style="max-width: 200px; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                                                        </a>
                                                    </div>
                                                <?php else: // Assume PDF or other document ?>
                                                    <div class="mt-2">
                                                        <a href="<?= htmlspecialchars($order['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-pdf"></i> Lihat PDF Bukti
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Belum ada bukti transfer.</span>
                                            <?php endif; ?>
                                        </li>
                                    </ul>

                                    <h5 class="mt-4 mb-3">Item Pesanan</h5>
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
                                                <?php foreach ($order['items'] as $item): ?>
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
                                                        <td>Rp <?= number_format($item['price_per_unit'], 0, ',', '.') ?></td>
                                                        <td>Rp <?= number_format($item['quantity'] * $item['price_per_unit'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-end">Total Pesanan:</th>
                                                    <th>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'components/footer.php'; ?>

<script>
    // Fungsi displayAjaxMessage tetap dipertahankan jika ada fitur AJAX lain di orders.php
    function displayAjaxMessage(message, type) {
        const messageArea = document.getElementById('ajax-message-area');
        if (messageArea) {
            messageArea.innerHTML = ''; // Hapus pesan sebelumnya
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            messageArea.appendChild(alertDiv);

            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000); // Sembunyikan setelah 5 detik
        }
    }

    // Hapus semua JavaScript yang terkait dengan modal upload bukti transfer karena modal dan tombolnya dihapus.
    // document.addEventListener('DOMContentLoaded', function() {
    //     document.querySelectorAll('.upload-proof-btn').forEach(button => {
    //         button.addEventListener('click', function() {
    //             const orderId = this.dataset.orderId;
    //             document.getElementById('upload_order_id').value = orderId;
    //         });
    //     });

    //     const uploadProofForm = document.getElementById('upload-proof-form');
    //     if (uploadProofForm) {
    //         uploadProofForm.addEventListener('submit', function(e) {
    //             e.preventDefault();
    //             const formData = new FormData(this);
    //             const orderId = document.getElementById('upload_order_id').value;
    //             fetch('services/servicepaketQurban.php', {
    //                 method: 'POST',
    //                 body: formData
    //             })
    //             .then(response => {
    //                 if (response.status === 401) {
    //                     window.location.href = 'login.php';
    //                     return Promise.reject('Unauthorized');
    //                 }
    //                 return response.json();
    //             })
    //             .then(data => {
    //                 if (data.success) {
    //                     displayAjaxMessage(data.message, 'success');
    //                     const modal = bootstrap.Modal.getInstance(document.getElementById('uploadProofModal'));
    //                     if (modal) modal.hide();
    //                     const proofLinkContainer = document.getElementById(`payment-proof-row-${orderId}`);
    //                     if (proofLinkContainer) {
    //                         proofLinkContainer.innerHTML = `
    //                             <strong>Bukti Transfer:</strong> 
    //                             <a href="${data.new_proof_url}" target="_blank" class="btn btn-sm btn-outline-primary mt-2" id="proof-link-${orderId}"><i class="bi bi-file-earmark-image"></i> Lihat Bukti</a>
    //                             <button type="button" class="btn btn-sm btn-info mt-2 ms-2 upload-proof-btn" data-bs-toggle="modal" data-bs-target="#uploadProofModal" data-order-id="${orderId}">
    //                                 <i class="bi bi-upload"></i> Edit Bukti
    //                             </button>
    //                         `;
    //                         proofLinkContainer.querySelector('.upload-proof-btn').addEventListener('click', function() {
    //                             const orderId = this.dataset.orderId;
    //                             document.getElementById('upload_order_id').value = orderId;
    //                         });
    //                     }
    //                     uploadProofForm.reset();
    //                 } else {
    //                     displayAjaxMessage(data.message || 'Gagal mengupload bukti transfer.', 'danger');
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error('Error:', error);
    //                 if (error !== 'Unauthorized') {
    //                     displayAjaxMessage('Terjadi kesalahan saat mengupload bukti transfer.', 'danger');
    //                 }
    //             });
    //         });
    //     }
    // });
</script>
