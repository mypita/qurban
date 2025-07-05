<?php
session_start();
require_once './services/konek.php';

// Hapus fungsi addToCart dan penanganan POST untuk add_to_cart di sini.
// Logika tersebut kini telah dipindahkan ke services/servicepaketQurban.php

// Dapatkan paket hewan dari database
$query = "SELECT * FROM animals ORDER BY type, price";
$result = $conn->query($query);
$animals = [];
while ($row = $result->fetch_assoc()) {
  $animals[] = $row;
}

// Kelompokkan hewan berdasarkan jenis
$groupedAnimals = [];
foreach ($animals as $animal) {
  $groupedAnimals[$animal['type']][] = $animal;
}
?>

<?php include 'components/header.php'; ?>

<!-- ======= Main =======-->
<main>
  <!-- ======= Paket Qurban =======-->
  <section class="hero__v6 section" id="paket">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
          <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Pilihan Hewan</span>
          <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">Berbagai Jenis Hewan Qurban</h2>
          <p data-aos="fade-up" data-aos-delay="200">Pilih hewan qurban sesuai kebutuhan dengan kualitas terbaik dan
            harga transparan</p>
        </div>
      </div>

      <!-- Area untuk menampilkan pesan dari AJAX -->
      <div id="cart-message-area" class="mb-4">
        <?php
        // Tampilkan pesan sukses/error yang mungkin ada dari redirect sebelumnya
        // Ini akan tetap berfungsi jika ada redirect dari halaman lain ke paket.php
        if (isset($_SESSION['cart_success'])): ?>
          <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <?php echo $_SESSION['cart_success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php unset($_SESSION['cart_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart_error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <?php echo $_SESSION['cart_error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php unset($_SESSION['cart_error']); ?>
        <?php endif; ?>
      </div>

      <!-- Tab Navigation -->
      <ul class="nav nav-tabs justify-content-center mb-4" id="animalTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="sapi-tab" data-bs-toggle="tab" data-bs-target="#sapi" type="button"
            role="tab">Sapi</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="kambing-tab" data-bs-toggle="tab" data-bs-target="#kambing" type="button"
            role="tab">Kambing</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="domba-tab" data-bs-toggle="tab" data-bs-target="#domba" type="button"
            role="tab">Domba</button>
        </li>
      </ul>

      <div class="tab-content" id="animalTabsContent">
        <!-- Sapi Tab -->
        <div class="tab-pane fade show active" id="sapi" role="tabpanel">
          <div class="row g-4">
            <?php if (isset($groupedAnimals['sapi'])): ?>
              <?php foreach ($groupedAnimals['sapi'] as $index => $animal): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                  <div class="card h-100 shadow-sm">
                    <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                      alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                      <p class="card-text">
                        <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                        <span class="d-block mt-2"><?= htmlspecialchars($animal['weight_range']) ?></span>
                      </p>
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                        <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                      </div>
                    </div>
                    <div class="card-footer bg-white">
                      <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Tambahkan class 'add-to-cart-form' untuk seleksi di JavaScript -->
                        <form class="add-to-cart-form">
                          <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
                          <!-- Tambahkan input hidden untuk 'action' -->
                          <input type="hidden" name="action" value="add_to_cart">
                          <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-cart-plus me-1"></i> Tambah
                          </button>
                        </form>
                      <?php else: ?>
                        <a href="login.php" class="btn btn-primary w-100">
                          <i class="fas fa-cart-plus me-1"></i> Tambah
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada paket sapi tersedia saat ini.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Kambing Tab -->
        <div class="tab-pane fade" id="kambing" role="tabpanel">
          <div class="row g-4">
            <?php if (isset($groupedAnimals['kambing'])): ?>
              <?php foreach ($groupedAnimals['kambing'] as $index => $animal): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                  <div class="card h-100 shadow-sm">
                    <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                      alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                      <p class="card-text">
                        <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                        <span class="d-block mt-2"><?= htmlspecialchars($animal['weight_range']) ?></span>
                      </p>
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                        <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                      </div>
                    </div>
                    <div class="card-footer bg-white">
                      <?php if (isset($_SESSION['user_id'])): ?>
                        <form class="add-to-cart-form">
                          <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
                          <input type="hidden" name="action" value="add_to_cart">
                          <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-cart-plus me-1"></i> Tambah
                          </button>
                        </form>
                      <?php else: ?>
                        <a href="login.php" class="btn btn-primary w-100">
                          <i class="fas fa-cart-plus me-1"></i> Tambah
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada paket kambing tersedia saat ini.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Domba Tab -->
        <div class="tab-pane fade" id="domba" role="tabpanel">
          <div class="row g-4">
            <?php if (isset($groupedAnimals['domba'])): ?>
              <?php foreach ($groupedAnimals['domba'] as $index => $animal): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                  <div class="card h-100 shadow-sm">
                    <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                      alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                      <p class="card-text">
                        <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                        <span class="d-block mt-2"><?= htmlspecialchars($animal['weight_range']) ?></span>
                      </p>
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                        <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                      </div>
                    </div>
                    <div class="card-footer bg-white">
                      <?php if (isset($_SESSION['user_id'])): ?>
                        <form class="add-to-cart-form">
                          <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
                          <input type="hidden" name="action" value="add_to_cart">
                          <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-cart-plus me-1"></i> Tambah
                          </button>
                        </form>
                      <?php else: ?>
                        <a href="login.php" class="btn btn-primary w-100">
                          <i class="fas fa-cart-plus me-1"></i> Tambah
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada paket domba tersedia saat ini.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Paket Qurban -->

  <!-- ======= Cara Berqurban ======= -->
  <section class="section howitworks__v1" id="how-it-works" style="background-color: #f8f9fa;">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-8 text-center mx-auto">
          <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Proses Berqurban</span>
          <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">Cara Memesan Qurban</h2>
          <p data-aos="fade-up" data-aos-delay="200">Langkah mudah berqurban secara digital dengan jaminan syariah
            dan kualitas terbaik</p>
        </div>
      </div>

      <div class="row g-md-5">
        <!-- Langkah 1 -->
        <div class="col-md-6 col-lg-3">
          <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative"
            data-aos="fade-up" data-aos-delay="0">
            <div data-aos="fade-right" data-aos-delay="500">
              <img class="arch-line" src="assets/images/arch-line.svg" alt="Garis penghubung">
            </div>
            <span class="step-number rounded-circle text-center fw-bold mb-4 mx-auto bg-primary">1</span>
            <div>
              <div class="icon-lg mb-3 mx-auto">
                <i class="bi bi-search-heart fs-1 text-primary"></i>
              </div>
              <h3 class="fs-5 mb-3">Pilih Hewan</h3>
              <p class="small">Telusuri katalog hewan qurban kami dan pilih sesuai kebutuhan (sapi, kambing, atau
                domba)</p>
            </div>
          </div>
        </div>

        <!-- Langkah 2 -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="step-card reverse text-center h-100 d-flex flex-column justify-content-start position-relative">
            <div data-aos="fade-right" data-aos-delay="800">
              <img class="arch-line reverse" src="assets/images/arch-line-reverse.svg" alt="Garis penghubung">
            </div>
            <span class="step-number rounded-circle text-center fw-bold mb-4 mx-auto bg-success">2</span>
            <div class="icon-lg mb-3 mx-auto">
              <i class="bi bi-cart-check fs-1 text-success"></i>
            </div>
            <h3 class="fs-5 mb-3">Checkout</h3>
            <p class="small">Masukkan data diri dan alamat pengiriman, lalu pilih metode pembayaran</p>
          </div>
        </div>

        <!-- Langkah 3 -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
          <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative">
            <div data-aos="fade-right" data-aos-delay="1100">
              <img class="arch-line" src="assets/images/arch-line.svg" alt="Garis penghubung">
            </div>
            <span class="step-number rounded-circle text-center fw-bold mb-4 mx-auto bg-warning">3</span>
            <div class="icon-lg mb-3 mx-auto">
              <i class="bi bi-credit-card fs-1 text-warning"></i>
            </div>
            <h3 class="fs-5 mb-3">Konfirmasi</h3>
            <p class="small">Upload bukti transfer dan tunggu verifikasi dari tim kami (1x24 jam)</p>
          </div>
        </div>

        <!-- Langkah 4 -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="900">
          <div class="step-card last text-center h-100 d-flex flex-column justify-content-start position-relative">
            <span class="step-number rounded-circle text-center fw-bold mb-4 mx-auto bg-danger">4</span>
            <div class="icon-lg mb-3 mx-auto">
              <i class="bi bi-truck fs-1 text-danger"></i>
            </div>
            <h3 class="fs-5 mb-3">Pengiriman</h3>
            <p class="small">Pantau status pengiriman hewan qurban hingga sampai di tujuan</p>
          </div>
        </div>
      </div>

      <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="1200">
        <a href="#" class="btn btn-primary px-4 py-2">
          <i class="bi bi-play-circle me-2"></i> Tonton Video Proses
        </a>
      </div>
    </div>
  </section>
  <!-- End Cara Berqurban -->
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

    // Tangani pengiriman form "Tambah ke Keranjang" menggunakan AJAX
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault(); // Mencegah pengiriman form default (reload halaman)

        const formData = new FormData(form);

        // URL target untuk permintaan AJAX
        const targetUrl = 'services/servicepaketQurban.php';

        fetch(targetUrl, {
          method: 'POST',
          body: formData
        })
          .then(response => {
            // Jika respons adalah 401 Unauthorized, redirect ke halaman login
            if (response.status === 401) {
              window.location.href = 'login.php';
              return Promise.reject('Unauthorized'); // Hentikan pemrosesan lebih lanjut
            }
            // Pastikan respons adalah JSON
            return response.json();
          })
          .then(data => {
            if (data.success) {
              displayMessage(data.message, 'success');
              // Perbarui counter keranjang jika ada (asumsi .cart-counter ada di header.php)
              const cartCounter = document.querySelector('.cart-counter');
              if (cartCounter) {
                cartCounter.textContent = parseInt(cartCounter.textContent) + 1;
              }
            } else {
              displayMessage(data.message || 'Gagal menambahkan item', 'danger');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            // Tampilkan pesan error umum jika bukan karena unauthorized redirect
            if (error !== 'Unauthorized') {
              displayMessage('Terjadi kesalahan saat menambahkan item. Silakan coba lagi.', 'danger');
            }
          });
      });
    });
  </script>
  <?php include 'components/footer.php'; ?>