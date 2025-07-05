<?php
session_start();
require_once 'services/konek.php'; // Pastikan path ini benar

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// Fungsi untuk mendapatkan item keranjang (dipindahkan ke servicepaketQurban.php)
// function getCartItems($conn, $user_id)
// {
//     $query = "SELECT c.*, a.type, a.weight_group, a.price, a.image_url, a.description
//               FROM carts c
//               JOIN animals a ON c.animal_id = a.id
//               WHERE c.user_id = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $user_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     return $result->fetch_all(MYSQLI_ASSOC);
// }

// Hapus semua logika POST/GET untuk remove dan update di sini.
// Ini akan ditangani oleh AJAX.

// Ambil item keranjang untuk tampilan awal
// Asumsi getCartItems sudah tersedia melalui include servicepaketQurban.php atau fungsi serupa
// Untuk menghindari redeclare, kita akan panggil langsung dari service jika sudah di-include
// Jika servicepaketQurban.php tidak di-include di sini, Anda perlu memanggilnya atau mendefinisikan ulang getCartItems
// Untuk demo ini, saya akan mengasumsikan getCartItems bisa dipanggil langsung atau Anda akan mengadaptasinya.
// Jika getCartItems tidak tersedia, Anda bisa copy paste fungsi dari servicepaketQurban.php ke sini.
// Atau, cara yang lebih baik adalah membuat endpoint GET di servicepaketQurban.php untuk mendapatkan item keranjang.
// Untuk saat ini, saya akan menggunakan query langsung seperti sebelumnya, atau Anda bisa mengintegrasikan dengan endpoint GET jika ada.

// Mengambil item keranjang untuk tampilan awal halaman
$cart_items = [];
$total_price = 0;
$stmt = $conn->prepare("SELECT c.id as cart_item_id, c.quantity, a.id as animal_id, a.type, a.description, a.price, a.image_url, a.weight_group, a.weight_range, a.stock FROM carts c JOIN animals a ON c.animal_id = a.id WHERE c.user_id = ?");
if ($stmt) {
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['quantity'] * $row['price'];
  }
  $stmt->close();
} else {
  error_log("Failed to prepare statement for initial cart items: " . $conn->error);
}


$active = 'cart';
?>
<?php include 'components/header.php'; ?>

<!-- ======= Main =======-->
<main>

  <!-- Area untuk menampilkan pesan dari AJAX -->
  <div id="cart-message-area" class="mb-4 container">
    <!-- Pesan sukses/error akan ditampilkan di sini oleh JavaScript -->
  </div>

  <!-- ======= Keranjang =======-->
  <section class="hero__v6 section" id="cart">
    <div class="container">
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold">Keranjang Belanja</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <?php if (count($cart_items) > 0): ?>
            <!-- Form diubah agar tidak submit secara default, akan dihandle AJAX -->
            <form id="cart-update-form">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="cart-table">
                      <thead>
                        <tr>
                          <th>Produk</th>
                          <th>Harga</th>
                          <th>Jumlah</th>
                          <th>Subtotal</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($cart_items as $item):
                          $type_class = 'type-' . $item['type'];
                          $subtotal = $item['price'] * $item['quantity'];
                          ?>
                          <tr data-cart-item-id="<?= htmlspecialchars($item['cart_item_id']); ?>">
                            <td>
                              <div class="d-flex align-items-center">
                                <img src="assets/images/<?php echo htmlspecialchars($item['image_url']); ?>"
                                  class="cart-item-img me-3" alt="<?php echo htmlspecialchars($item['type']); ?>">
                                <div>
                                  <span class="type-badge <?php echo $type_class; ?> mb-1">
                                    <?php echo ucfirst($item['type']); ?>
                                  </span>
                                  <h6 class="mb-0"><?php echo htmlspecialchars($item['weight_group']); ?></h6>
                                  <small><?php echo htmlspecialchars($item['description']); ?></small>
                                </div>
                              </div>
                            </td>
                            <td class="item-price" data-price="<?= htmlspecialchars($item['price']); ?>">Rp
                              <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td>
                              <input type="number" name="quantity[<?php echo $item['cart_item_id']; ?>]"
                                class="form-control quantity-input" value="<?php echo $item['quantity']; ?>" min="1"
                                max="<?= htmlspecialchars($item['stock']) ?>">
                              <?php if ($item['stock'] < 10 && $item['stock'] > 0): ?>
                                <small class="text-danger">Stok tersisa: <?= htmlspecialchars($item['stock']) ?></small>
                              <?php elseif ($item['stock'] == 0): ?>
                                <small class="text-danger">Stok habis!</small>
                              <?php endif; ?>
                            </td>
                            <td class="item-subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            <td>
                              <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"
                                data-cart-id="<?php echo $item['cart_item_id']; ?>">
                                <i class="fas fa-trash"></i>
                              </button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer text-end">
                  <button type="submit" name="update_cart" class="btn btn-outline-primary me-2">
                    <i class="fas fa-sync-alt me-1"></i> Update Keranjang
                  </button>
                </div>
              </div>
            </form>
          <?php else: ?>
            <div class="card shadow-sm">
              <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h4 class="mb-3">Keranjang Anda kosong</h4>
                <p class="text-muted mb-4">Silakan tambahkan paket qurban terlebih dahulu</p>
                <a href="paket.php" class="btn btn-primary">Lihat Paket Qurban</a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <?php if (count($cart_items) > 0): ?>
          <div class="col-lg-4 ms-auto">
            <div class="card shadow-sm summary-card">
              <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Belanja</h5>

                <div class="d-flex justify-content-between mb-2">
                  <span>Total Harga:</span>
                  <span id="summary-total-price">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold mb-4">
                  <span>Total:</span>
                  <span id="summary-final-total">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>

                <a href="checkout.php" class="btn btn-checkout w-100 py-2">
                  <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- End Keranjang -->
  <?php include 'components/footer.php'; ?>

  <script>
    /**
     * Fungsi untuk menampilkan pesan di area pesan keranjang.
     * Menggantikan alert() dengan tampilan Bootstrap.
     * @param {string} message Pesan yang akan ditampilkan.
     * @param {string} type Tipe pesan (e.g., 'success', 'danger').
     */
    function displayMessage(message, type) {
      const messageArea = document.getElementById('cart-message-area');
      if (messageArea) {
        // Hapus pesan yang sudah ada sebelumnya
        messageArea.innerHTML = '';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
        messageArea.appendChild(alertDiv);

        // Sembunyikan pesan secara otomatis setelah 5 detik
        setTimeout(() => {
          const bsAlert = new bootstrap.Alert(alertDiv);
          bsAlert.close();
        }, 5000);
      }
    }

    /**
     * Mengupdate tampilan penghitung keranjang di header.
     * @param {number} count Jumlah item di keranjang.
     */
    function updateHeaderCartCount(count) {
      const cartItemCountSpan = document.getElementById('cart-item-count');
      if (cartItemCountSpan) {
        cartItemCountSpan.textContent = count;
        if (count > 0) {
          cartItemCountSpan.style.display = ''; // Tampilkan
        } else {
          cartItemCountSpan.style.display = 'none'; // Sembunyikan
        }
      }
    }

    /**
     * Merender ulang tabel keranjang dan ringkasan total.
     * @param {Array} cartItems Array objek item keranjang.
     */
    function renderCart(cartItems) {
      const cartTableBody = document.querySelector('#cart-table tbody');
      const summaryTotalPriceSpan = document.getElementById('summary-total-price');
      const summaryFinalTotalSpan = document.getElementById('summary-final-total');
      const cartSection = document.getElementById('cart'); // Section utama keranjang
      const emptyCartMessage = `
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h4 class="mb-3">Keranjang Anda kosong</h4>
                        <p class="text-muted mb-4">Silakan tambahkan paket qurban terlebih dahulu</p>
                        <a href="paket.php" class="btn btn-primary">Lihat Paket Qurban</a>
                    </div>
                </div>
            `;

      cartTableBody.innerHTML = ''; // Kosongkan tabel
      let newTotalPrice = 0;

      if (cartItems.length === 0) {
        // Jika keranjang kosong, tampilkan pesan kosong dan sembunyikan ringkasan
        const colLg12 = cartSection.querySelector('.col-lg-12');
        const colLg4 = cartSection.querySelector('.col-lg-4.ms-auto');

        if (colLg12) {
          colLg12.innerHTML = emptyCartMessage;
        }
        if (colLg4) {
          colLg4.style.display = 'none'; // Sembunyikan ringkasan
        }
        updateHeaderCartCount(0); // Pastikan header juga 0
        return;
      } else {
        // Pastikan ringkasan terlihat jika ada item
        const colLg12 = cartSection.querySelector('.col-lg-12');
        const colLg4 = cartSection.querySelector('.col-lg-4.ms-auto');
        if (colLg4) {
          colLg4.style.display = 'block';
        }
        // Jika sebelumnya kosong, pastikan form dan tabel muncul kembali
        if (!document.getElementById('cart-update-form')) {
          // Ini skenario lebih kompleks, mungkin perlu reload halaman atau render ulang seluruh section
          // Untuk kesederhanaan, jika sebelumnya kosong, kita asumsikan halaman akan di-reload
          // atau struktur HTML form/table sudah ada dan hanya isinya yang diupdate.
          // Jika Anda ingin benar-benar mulus dari kosong ke ada item,
          // Anda perlu membuat template HTML untuk form dan ringkasan dan menyuntikkannya.
          // Untuk saat ini, kita fokus pada update setelah ada item.
        }
      }


      cartItems.forEach(item => {
        const subtotal = item.price * item.quantity;
        newTotalPrice += subtotal;

        const row = document.createElement('tr');
        row.setAttribute('data-cart-item-id', item.cart_item_id);
        row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="assets/images/${item.image_url}" class="cart-item-img me-3" alt="${item.type}">
                            <div>
                                <span class="type-badge type-${item.type} mb-1">
                                    ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}
                                </span>
                                <h6 class="mb-0">${item.weight_group}</h6>
                                <small>${item.description}</small>
                            </div>
                        </div>
                    </td>
                    <td class="item-price" data-price="${item.price}">Rp ${formatRupiah(item.price)}</td>
                    <td>
                        <input type="number" name="quantity[${item.cart_item_id}]"
                            class="form-control quantity-input" value="${item.quantity}" min="1" max="${item.stock}">
                        ${item.stock < 10 && item.stock > 0 ? `<small class="text-danger">Stok tersisa: ${item.stock}</small>` : ''}
                        ${item.stock == 0 ? `<small class="text-danger">Stok habis!</small>` : ''}
                    </td>
                    <td class="item-subtotal">Rp ${formatRupiah(subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" data-cart-id="${item.cart_item_id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
        cartTableBody.appendChild(row);
      });

      summaryTotalPriceSpan.textContent = `Rp ${formatRupiah(newTotalPrice)}`;
      summaryFinalTotalSpan.textContent = `Rp ${formatRupiah(newTotalPrice)}`;

      // Re-attach event listeners for newly rendered elements
      attachEventListeners();
    }

    /**
     * Fungsi pembantu untuk memformat angka ke format Rupiah.
     * @param {number} amount
     * @returns {string}
     */
    function formatRupiah(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    }


    /**
     * Melampirkan event listener ke tombol hapus dan input kuantitas.
     */
    function attachEventListeners() {
      // Event listener untuk tombol hapus
      document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.onclick = function () {
          const cartId = this.dataset.cartId;
          if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            sendCartAction('remove_from_cart', { cart_id: cartId });
          }
        };
      });

      // Event listener untuk form update keranjang
      const cartUpdateForm = document.getElementById('cart-update-form');
      if (cartUpdateForm) {
        cartUpdateForm.onsubmit = function (e) {
          e.preventDefault();
          const quantities = {};
          document.querySelectorAll('.quantity-input').forEach(input => {
            quantities[input.name.match(/\[(.*?)\]/)[1]] = parseInt(input.value);
          });
          sendCartAction('update_cart_quantity', { quantities: quantities });
        };
      }

      // Optional: Update subtotal secara real-time saat kuantitas berubah
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.oninput = function () {
          const row = this.closest('tr');
          const price = parseFloat(row.querySelector('.item-price').dataset.price);
          const quantity = parseInt(this.value);
          const subtotalElement = row.querySelector('.item-subtotal');
          if (!isNaN(price) && !isNaN(quantity)) {
            subtotalElement.textContent = `Rp ${formatRupiah(price * quantity)}`;
          }
          // Note: Total harga di ringkasan tidak akan update sampai form disubmit
        };
      });
    }

    /**
     * Mengirim permintaan AJAX ke servicepaketQurban.php.
     * @param {string} action Nama aksi (e.g., 'remove_from_cart', 'update_cart_quantity').
     * @param {Object} data Objek data yang akan dikirim.
     */
    function sendCartAction(action, data) {
      const formData = new FormData();
      formData.append('action', action);
      for (const key in data) {
        if (typeof data[key] === 'object' && data[key] !== null) {
          // Handle nested objects (like quantities)
          for (const subKey in data[key]) {
            formData.append(`${key}[${subKey}]`, data[key][subKey]);
          }
        } else {
          formData.append(key, data[key]);
        }
      }

      fetch('services/servicepaketQurban.php', {
        method: 'POST',
        body: formData
      })
        .then(response => {
          if (response.status === 401) {
            window.location.href = 'login.php';
            return Promise.reject('Unauthorized');
          }
          return response.json();
        })
        .then(result => {
          if (result.success) {
            displayMessage(result.message, 'success');
            updateHeaderCartCount(result.cart_count);
            // Render ulang seluruh keranjang dengan data terbaru dari server
            renderCart(result.cart_items || []); // Pastikan selalu array
          } else {
            displayMessage(result.message || 'Terjadi kesalahan.', 'danger');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          if (error !== 'Unauthorized') {
            displayMessage('Terjadi kesalahan saat memproses permintaan.', 'danger');
          }
        });
    }

    // Jalankan saat DOMContentLoaded untuk melampirkan event listeners awal
    document.addEventListener('DOMContentLoaded', attachEventListeners);
  </script>
</main>