<?php
session_start();
require_once './services/konek.php';

// Hapus fungsi addToCart dan penanganan POST untuk add_to_cart di sini.
// Logika tersebut kini telah dipindahkan ke services/servicepaketQurban.php

// Dapatkan paket hewan dari database
$query = "SELECT * FROM animals ORDER BY type, price, description";
$result = $conn->query($query);
$animals = [];
while ($row = $result->fetch_assoc()) {
    $animals[] = $row;
}

// Kelompokkan hewan berdasarkan jenis DAN nama ras/breed
$groupedAnimalsByBreed = [];
foreach ($animals as $animal) {
    $description = $animal['description'];
    $type = $animal['type'];
    $breed_name = '';

    // Extract breed name from description
    if (strpos($description, 'Kambing Boer') !== false) {
        $breed_name = 'Boer';
    } elseif (strpos($description, 'Kambing Kacang') !== false) {
        $breed_name = 'Kacang';
    } elseif (strpos($description, 'Domba Garut') !== false) {
        $breed_name = 'Garut';
    } elseif (strpos($description, 'Domba Texel') !== false) {
        $breed_name = 'Texel';
    } elseif (strpos($description, 'Sapi Limosin') !== false) {
        $breed_name = 'Limosin';
    } elseif (strpos($description, 'Sapi Bali') !== false) {
        $breed_name = 'Bali';
    } else {
        // For generic types like "Qurban Domba Tipe Hemat"
        $breed_name = 'Generic ' . ucfirst($type); // e.g., "Generic Domba"
    }

    // Now group by type and then by breed_name, and then sort by weight_group
    // We'll use FIELD() for consistent sorting of weight_group within the breed
    $sort_order = array_search($animal['weight_group'], ['HEMAT', 'SPESIAL', 'ISTIMEWA', 'SUPER']);
    $groupedAnimalsByBreed[$type][$breed_name][$sort_order] = $animal;

    // After adding, ensure the sub-array is sorted by key (which is the sort_order)
    ksort($groupedAnimalsByBreed[$type][$breed_name]);
}
?>

<?php include 'components/header.php'; ?>

<main>
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

            <div id="cart-message-area" class="mb-4">
                <?php
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
                <div class="tab-pane fade show active" id="sapi" role="tabpanel">
                    <?php if (isset($groupedAnimalsByBreed['sapi']) && !empty($groupedAnimalsByBreed['sapi'])): ?>
                        <?php foreach ($groupedAnimalsByBreed['sapi'] as $breedName => $breedAnimals): ?>
                            <h4 class="mt-4 mb-3"><?= htmlspecialchars($breedName === "Generic Sapi" ? "Sapi Umum" : "Sapi " . $breedName) ?></h4>
                            <div class="row g-4 mb-4">
                                <?php foreach ($breedAnimals as $index => $animal): ?>
                                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                                        <div class="card h-100 shadow-sm">
                                            <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                                                alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                                                <p class="card-text">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                                                    <span class="d-block mt-2">Berat: <?= htmlspecialchars($animal['weight_range']) ?></span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                                                    <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-success mt-3 w-100" data-bs-toggle="modal"
                                                    data-bs-target="#certificateModal">
                                                    <i class="fas fa-file-alt me-1"></i> Lihat E-Sertifikat
                                                </button>
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
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">Tidak ada paket sapi tersedia saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="kambing" role="tabpanel">
                    <?php if (isset($groupedAnimalsByBreed['kambing']) && !empty($groupedAnimalsByBreed['kambing'])): ?>
                        <?php foreach ($groupedAnimalsByBreed['kambing'] as $breedName => $breedAnimals): ?>
                            <h4 class="mt-4 mb-3"><?= htmlspecialchars($breedName === "Generic Kambing" ? "Kambing Umum" : "Kambing " . $breedName) ?></h4>
                            <div class="row g-4 mb-4">
                                <?php foreach ($breedAnimals as $index => $animal): ?>
                                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                                        <div class="card h-100 shadow-sm">
                                            <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                                                alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                                                <p class="card-text">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                                                    <span class="d-block mt-2">Berat: <?= htmlspecialchars($animal['weight_range']) ?></span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                                                    <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-success mt-3 w-100" data-bs-toggle="modal"
                                                    data-bs-target="#certificateModal">
                                                    <i class="fas fa-file-alt me-1"></i> Lihat E-Sertifikat
                                                </button>
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
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">Tidak ada paket kambing tersedia saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="domba" role="tabpanel">
                    <?php if (isset($groupedAnimalsByBreed['domba']) && !empty($groupedAnimalsByBreed['domba'])): ?>
                        <?php foreach ($groupedAnimalsByBreed['domba'] as $breedName => $breedAnimals): ?>
                            <h4 class="mt-4 mb-3"><?= htmlspecialchars($breedName === "Generic Domba" ? "Domba Umum" : "Domba " . $breedName) ?></h4>
                            <div class="row g-4 mb-4">
                                <?php foreach ($breedAnimals as $index => $animal): ?>
                                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 50) ?>">
                                        <div class="card h-100 shadow-sm">
                                            <img src="assets/images/<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top"
                                                alt="<?= htmlspecialchars($animal['type']) ?>" style="height: 244px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($animal['description']) ?></h5>
                                                <p class="card-text">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($animal['weight_group']) ?></span>
                                                    <span class="d-block mt-2">Berat: <?= htmlspecialchars($animal['weight_range']) ?></span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Rp <?= number_format($animal['price'], 0, ',', '.') ?></h6>
                                                    <small class="text-muted">Stok: <?= $animal['stock'] ?></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-success mt-3 w-100" data-bs-toggle="modal"
                                                    data-bs-target="#certificateModal">
                                                    <i class="fas fa-file-alt me-1"></i> Lihat E-Sertifikat
                                                </button>
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
    </section>
    <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certificateModalLabel">E-Sertifikat Hewan Qurban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="assets/images/qurban/sertifikat.webp" alt="E-Sertifikat Hewan Qurban"
                        class="img-fluid rounded shadow-sm">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                <a class="glightbox btn btn-play d-inline-flex align-items-center gap-2"
                    href="https://youtu.be/jsIKgFvsu7Y?feature=shared" data-gallery="video">
                    <i class="bi bi-play-circle me-2"></i> Tonton Video Proses
                </a>
            </div>
        </div>
    </section>
    <section class="stats__v3 section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap content rounded-4" data-aos="fade-up" data-aos-delay="0"
                        style="background-color: #1A5D1A;">
                        <div class="rounded-borders">
                            <div class="rounded-border-1"></div>
                            <div class="rounded-border-2"></div>
                            <div class="rounded-border-3"></div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center text-white" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="stat-item">
                                <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="157"
                                    data-purecounter-duration="2">0</span><span>+</span></h3>
                                <p class="mb-0">Masjid Terbantu</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center text-white" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="stat-item">
                                <h3 class="fs-1 fw-bold"> <span class="purecounter" data-purecounter-start="0"
                                    data-purecounter-end="5320" data-purecounter-duration="2">0</span><span>+</span></h3>
                                <p class="mb-0">Hewan Qurban</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center text-white" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="stat-item">
                                <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="98"
                                    data-purecounter-duration="2">0</span><span>%</span></h3>
                                <p class="mb-0">Kepuasan Jamaah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section testimonials__v2" id="testimonials" style="background-color: #f8f5ee;">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Kata Mereka</span>
                    <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">Berkah Qurban yang Dirasakan</h2>
                    <p data-aos="fade-up" data-aos-delay="200">Dari hati ke hati, pengalaman nyata jamaah yang berqurban
                        melalui kami</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="testimonial rounded-4 p-4 shadow-sm" style="background-color: white;">
                        <div class="quote-icon mb-3 text-warning">
                            <i class="bi bi-quote fs-1"></i>
                        </div>
                        <blockquote class="mb-3 fs-5" style="font-style: italic;">
                            "Alhamdulillah tahun ini bisa berqurban sapi via online. Awalnya ragu, tapi pas lihat videonya di
                            YouTube, sapinya gemuk-gemuk dan penyembelihannya sesuai syar'i. Dagingnya sampai ke pesantren anak
                            yatim di Bogor. Bikin hati adem dan rezeki makin lancar, masha Allah tabarakallah 💖"
                        </blockquote>
                        <div class="testimonial-author d-flex gap-3 align-items-center">
                            <div class="author-img">
                                <img class="rounded-circle img-fluid" src="assets/images/testimonial/1.jpeg" alt="Ibu Aisyah"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="lh-base">
                                <strong class="d-block">Ummu Aisyah</strong>
                                <span>Ibu Rumah Tangga, Depok</span>
                                <div class="rating mt-1">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial rounded-4 p-4 shadow-sm" style="background-color: white;">
                        <div class="quote-icon mb-3 text-warning">
                            <i class="bi bi-quote fs-1"></i>
                        </div>
                        <blockquote class="mb-3 fs-5" style="font-style: italic;">
                            "Barakallah buat Qurban Indonesia! Tahun lalu pesan kambing disini, harganya bersaing tapi kualitas no
                            tipu-tipu. Pas Idul Adha dapat laporan lengkap sama foto-foto penyembelihan. Anak-anak di panti asuhan
                            seneng banget dapet daging segar. Insya Allah tahun ini mau pesan 2 ekor lagi, biar makin banyak yang
                            kebahagiaan 🤲"
                        </blockquote>
                        <div class="testimonial-author d-flex gap-3 align-items-center">
                            <div class="author-img">
                                <img class="rounded-circle img-fluid" src="assets/images/testimonial/4.jpeg" alt="Bapak Ahmad"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="lh-base">
                                <strong class="d-block">H. Ahmad Sudirman</strong>
                                <span>Pengusaha, Bandung</span>
                                <div class="rating mt-1">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial rounded-4 p-4 shadow-sm" style="background-color: white;">
                        <div class="quote-icon mb-3 text-warning">
                            <i class="bi bi-quote fs-1"></i>
                        </div>
                        <blockquote class="mb-3 fs-5" style="font-style: italic;">
                            "Subhanallah... Pertama kali coba qurban online karena lagi di perantauan. Adminnya ramah banget,
                            sabar jawab pertanyaan saya yang banyak. Pas hari H dapat video penyembelihan dan sertifikat digital.
                            Keluarga di kampung juga dapat bagian daging. Bener-bener memudahkan dan bikin hati tenang.
                            Jazakumullah khairan 💐"
                        </blockquote>
                        <div class="testimonial-author d-flex gap-3 align-items-center">
                            <div class="author-img">
                                <img class="rounded-circle img-fluid" src="assets/images/testimonial/2.jpeg" alt="Siti Maryam"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="lh-base">
                                <strong class="d-block">Siti Maryam</strong>
                                <span>Perantau, Malaysia</span>
                                <div class="rating mt-1">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="#" class="btn btn-lg btn-outline-primary">
                    <i class="bi bi-chat-square-quote me-2"></i> Lihat Lebih Banyak Testimoni
                </a>
            </div>
        </div>
    </section>
    <section class="section faq__v2" id="faq">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6 col-lg-7 mx-auto text-center">
                    <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Pertanyaan Umum</span>
                    <h2 class="h2 fw-bold mb-3" data-aos="fade-up" data-aos-delay="0">FAQ Layanan Qurban Syariah</h2>
                    <p data-aos="fade-up" data-aos-delay="100">Temukan jawaban atas pertanyaan seputar layanan qurban kami
                        yang transparan, terpercaya, dan penuh berkah.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-content">
                        <div class="accordion custom-accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapseOne">
                                        Apa saja jenis hewan qurban yang tersedia?
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseOne">
                                    <div class="accordion-body">
                                        Kami menyediakan hewan qurban sesuai syariat Islam: sapi, kambing, dan domba. Setiap hewan telah
                                        memenuhi kriteria sehat, cukup umur, dan bebas cacat. Anda bisa memilih langsung di katalog
                                        kami.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseThree">
                                        Apakah ada jaminan hewan qurban sesuai syariat?
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="panelsStayOpen-collapseThree">
                                    <div class="accordion-body">
                                        <strong>Ya, 100%.</strong> Hewan qurban kami:<br>
                                        1. Dipantau oleh Dewan Syariah<br>
                                        2. Memiliki sertifikat kesehatan<br>
                                        3. Proses penyembelihan sesuai tuntunan Islam (halal & thayyib)
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseFour">
                                        Berapa lama pengiriman hewan qurban?
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="panelsStayOpen-collapseFour">
                                    <div class="accordion-body">
                                        Pengiriman dilakukan H-2 Idul Adha. Anda akan mendapat:<br>
                                        - Notifikasi real-time<br>
                                        - Foto laporan distribusi<br>
                                        - Kontak panitia penerima<br><br>
                                        *Untuk area terpencil, konfirmasi via CS kami.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseFive">
                                        Bagaimana jika ingin qurban atas nama orang lain?
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="panelsStayOpen-collapseFive">
                                    <div class="accordion-body">
                                        Anda dapat memasukkan nama pekurban lain saat proses checkout. Sertifikat akan diterbitkan atas nama
                                        pekurban yang Anda tentukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
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

            // Auto-hide after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        }
    }

    // Tangani pengiriman form AJAX untuk tambah ke keranjang
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah form dari refresh halaman

            const formData = new FormData(this);

            fetch('./services/servicepaketQurban.php', { // Pastikan path ini benar
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Harapkan respons JSON
                .then(data => {
                    if (data.success) {
                        displayMessage(data.message, 'success');
                        // Opsional: perbarui tampilan keranjang jika ada indikator
                        // console.log('Current cart count:', data.cart_count);
                    } else {
                        displayMessage(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    displayMessage('Terjadi kesalahan saat menambahkan ke keranjang.', 'danger');
                });
        });
    });

    // Initialize AOS
    AOS.init({
        duration: 800, // Durasi animasi (ms)
        easing: 'ease-in-out', // Efek easing
        once: true, // Animasi hanya berjalan sekali saat scroll ke elemen
        mirror: false, // Apakah elemen harus dianimasikan lagi saat di-scroll ke atas
    });

    // Initialize PureCounter
    new PureCounter();
</script>

<?php include 'components/footer.php'; ?>