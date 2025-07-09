<?php
session_start();
require_once './services/konek.php';
?>
<?php include 'components/header.php'; ?>
<!-- ======= Main =======-->
<main>
  <!-- ======= Hero Qurban Indonesia ======= -->
  <section class="hero__v6 section" id="home">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="row">
            <div class="col-lg-11">
              <span class="hero-subtitle text-uppercase" data-aos="fade-up" data-aos-delay="0">
                Layanan Qurban Berbasis Syariah
              </span>
              <h1 class="hero-title mb-3" data-aos="fade-up" data-aos-delay="100">
                Pengalaman Berqurban Terpercaya, Transparan, dan Penuh Berkah
              </h1>
              <p class="hero-description mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="200">
                Tunaikan ibadah qurban dengan hewan premium bersertifikat halal dan pengiriman ke seluruh Indonesia.
              </p>
              <div class="cta d-flex gap-2 mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="300">
                <a class="btn" href="paket.php">Pesan Qurban Sekarang</a>
                <a class="btn btn-white-outline" href="#how-it-works">
                  Cara Berqurban
                  <svg class="lucide lucide-arrow-up-right" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M7 7h10v10"></path>
                    <path d="M7 17 17 7"></path>
                  </svg>
                </a>
              </div>
              <div class="logos mb-4" data-aos="fade-up" data-aos-delay="400">
                <span class="logos-title text-uppercase mb-4 d-block">
                  Dipercaya Lembaga Islam Terkemuka
                </span>
                <div class="logos-images d-flex gap-4 align-items-center">
                  <img class="img-fluid" src="assets/images/aman/mui.png" alt="MUI" style="width: 90px;">
                  <img class="img-fluid" src="assets/images/aman/kemenag.png" alt="KEMENAG" style="width: 80px;">
                  <img class="img-fluid" src="assets/images/aman/OJK.png" alt="OJK" style="width: 130px;">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-img">
            <img class="img-card img-fluid" src="assets/images/qurban/sertifikat.webp" alt="Sertifikat Qurban"
              data-aos="fade-down" data-aos-delay="600">
            <img class="img-main img-fluid rounded-4" src="assets/images/qurban/utama.png" alt="Hewan Qurban Premium"
              data-aos="fade-in" data-aos-delay="500">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Hero -->

  <!-- ======= Tentang Kami =======-->
  <section class="about__v4 section" id="about">
    <div class="container">
      <div class="row">
        <div class="col-md-6 order-md-2">
          <div class="row justify-content-end">
            <div class="col-md-11 mb-4 mb-md-0">
              <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Tentang Kami</span>
              <h2 class="mb-4" data-aos="fade-up" data-aos-delay="100">
                Memudahkan Ibadah Qurban dengan Layanan Terpercaya dan Syariah
              </h2>
              <div data-aos="fade-up" data-aos-delay="200">
                <p>
                  Qurban Indonesia hadir sebagai solusi modern untuk memenuhi panggilan ibadah qurban dengan
                  kemudahan digital tanpa mengesampingkan ketentuan syariat.
                </p>
                <p>
                  Kami menyediakan hewan qurban premium yang sehat, bersertifikat halal, dengan sistem
                  transparan mulai pemilihan hingga distribusi daging ke mustahik.
                </p>
              </div>
              <h4 class="small fw-bold mt-4 mb-3" data-aos="fade-up" data-aos-delay="300">Nilai Utama Kami</h4>
              <ul class="d-flex flex-row flex-wrap list-unstyled gap-3 features" data-aos="fade-up"
                data-aos-delay="400">
                <li class="d-flex align-items-center gap-2">
                  <span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span>
                  <span class="text">Kesesuaian Syariah</span>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span>
                  <span class="text">Kualitas Hewan Premium</span>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span>
                  <span class="text">Transparansi Penuh</span>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span>
                  <span class="text">Kemudahan Berqurban</span>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span>
                  <span class="text">Distribusi Tepat Sasaran</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="img-wrap position-relative">
            <img class="img-fluid rounded-4" src="assets/images/qurban/banner.png" alt="Tim Qurban Indonesia"
              data-aos="fade-up" data-aos-delay="0">
            <div class="mission-statement p-4 rounded-4 d-flex gap-4" data-aos="fade-up" data-aos-delay="100">
              <div class="mission-icon text-center rounded-circle"><i class="bi bi-heart fs-4"></i></div>
              <div>
                <h2 class="text-uppercase fw-bold">Misi Kami</h3>
                  <p class="fs-5 mb-0">
                    Menyediakan layanan qurban digital yang syar'i, transparan, dan memudahkan umat
                    dalam menjalankan ibadah dengan penuh makna.
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Tentang Kami -->

  <!-- ======= Keunggulan =======-->
  <section class="section features__v2" id="features">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-lg-flex p-5 rounded-4 content" data-aos="fade-in" data-aos-delay="0"
            style="background-color: #f8f5ee;">
            <div class="row">
              <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                <div class="row">
                  <div class="col-lg-11">
                    <div class="h-100 flex-column justify-content-between d-flex">
                      <div>
                        <h2 class="mb-4">Mengapa Memilih Kami</h2>
                        <p class="mb-5">Nikmati kemudahan berqurban secara digital dengan layanan syar'i dan
                          terpercaya. Platform kami memastikan proses qurban Anda aman, transparan, dan penuh berkah
                          - dari pemilihan hewan hingga distribusi daging ke yang berhak.</p>
                      </div>
                      <div class="align-self-start">
                        <a class="glightbox btn btn-play d-inline-flex align-items-center gap-2"
                          href="https://youtu.be/jsIKgFvsu7Y?feature=shared" data-gallery="video">
                          <i class="bi bi-play-fill"></i> Tonton Video Berikut
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-7">
                <div class="row justify-content-end">
                  <div class="col-lg-11">
                    <div class="row">
                      <div class="col-sm-6" data-aos="fade-up" data-aos-delay="0">
                        <div class="icon text-center mb-4" style="color: #1A5D1A;">
                          <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <h3 class="fs-6 fw-bold mb-3">Sertifikasi Syariah</h3>
                        <p>Hewan qurban sehat dengan sertifikasi halal dan memenuhi syarat islami.</p>
                      </div>
                      <div class="col-sm-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon text-center mb-4" style="color: #D4AF37;">
                          <i class="bi bi-camera-video fs-4"></i>
                        </div>
                        <h3 class="fs-6 fw-bold mb-3">Monitoring Langsung</h3>
                        <p>Proses pemilihan dan penyembelihan bisa dipantau via live streaming.</p>
                      </div>
                      <div class="col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon text-center mb-4" style="color: #9A031E;">
                          <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h3 class="fs-6 fw-bold mb-3">Pengiriman Tepat Waktu</h3>
                        <p>Jaminan hewan sampai sebelum Hari Raya dengan sistem logistik terintegrasi.</p>
                      </div>
                      <div class="col-sm-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon text-center mb-4" style="color: #2563EB;">
                          <i class="bi bi-chat-dots fs-4"></i>
                        </div>
                        <h3 class="fs-6 fw-bold mb-3">Layanan Pelanggan</h3>
                        <p>Tim ahli siap membantu 24 jam via WhatsApp, telepon, dan email.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Keunggulan -->

  <!-- ======= Paket Qurban ======= -->
  <section class="section pricing__v2" id="paket">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
          <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Pilihan Hewan</span>
          <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">Berbagai Jenis Hewan Qurban</h2>
          <p data-aos="fade-up" data-aos-delay="200">Pilih hewan qurban sesuai kebutuhan dengan kualitas terbaik dan
            harga transparan</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Card 1: Sapi Limosin -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="p-4 rounded-4 price-table h-100 text-center">
            <img src="assets/images/qurban/1.png" alt="Sapi Limosin" class="img-fluid rounded-3 mb-3"
              style="height: 180px; object-fit: cover;">
            <h3 class="h5">Sapi Limosin</h3>
            <div class="price mb-3">
              <strong>Rp21.5 JT</strong>
              <span class="d-block small">Tipe Standar (±350 kg)</span>
            </div>
            <a href="./cart.php">
              <button class="btn btn-sm btn-outline-primary w-100">+ Tambahkan</button>
            </a>
          </div>
        </div>

        <!-- Card 2: Sapi Bali -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="350">
          <div class="p-4 rounded-4 price-table h-100 text-center">
            <img src="assets/images/qurban/2.png" alt="Sapi Bali" class="img-fluid rounded-3 mb-3"
              style="height: 180px; object-fit: cover;">
            <h3 class="h5">Sapi Bali</h3>
            <div class="price mb-3">
              <strong>Rp25 JT</strong>
              <span class="d-block small">Tipe Standar (300-350 kg)</span>
            </div>
            <a href="./cart.php">
              <button class="btn btn-sm btn-outline-primary w-100">+ Tambahkan</button>
            </a>
          </div>
        </div>

        <!-- Card 3: Kambing -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
          <div class="p-4 rounded-4 price-table h-100 text-center">
            <img src="assets/images/qurban/3.png" alt="Kambing" class="img-fluid rounded-3 mb-3"
              style="height: 180px; object-fit: cover;">
            <h3 class="h5">Kambing</h3>
            <div class="price mb-3">
              <strong>Rp3.4 JT</strong>
              <span class="d-block small">Tipe Hemat (25-29 kg)</span>
            </div>
            <a href="./cart.php">
              <button class="btn btn-sm btn-outline-primary w-100">+ Tambahkan</button>
            </a>
          </div>
        </div>

        <!-- Card 4: Domba -->
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="450">
          <div class="p-4 rounded-4 price-table h-100 text-center">
            <img src="assets/images/qurban/4.png" alt="Domba" class="img-fluid rounded-3 mb-3"
              style="height: 180px; object-fit: cover;">
            <h3 class="h5">Domba</h3>
            <div class="price mb-3">
              <strong>Rp2.5 JT</strong>
              <span class="d-block small">Tipe Hemat (25-29 kg)</span>
            </div>
            <a href="./cart.php">
              <button class="btn btn-sm btn-outline-primary w-100">+ Tambahkan</button>
            </a>
          </div>
        </div>
      </div>

      <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
        <a href="./cart.php" class="btn btn-lg btn-primary">Lihat Semua Pilihan</a>
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

        <a class="glightbox btn btn-play d-inline-flex align-items-center gap-2"
          href="https://youtu.be/jsIKgFvsu7Y?feature=shared" data-gallery="video">
          <i class="bi bi-play-circle me-2"></i> Tonton Video Proses
        </a>
        <!-- <a href="#" class="btn btn-primary px-4 py-2">
              <i class="bi bi-play-circle me-2"></i> Tonton Video Proses
            </a> -->
      </div>
    </div>
  </section>
  <!-- End Cara Berqurban -->

  <!-- ======= Statistik =======-->
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
                <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="0"
                    data-purecounter-duration="2">0</span><span>+</span></h3>
                <p class="mb-0">Masjid Terbantu</p>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center text-white" data-aos="fade-up"
              data-aos-delay="200">
              <div class="stat-item">
                <h3 class="fs-1 fw-bold"> <span class="purecounter" data-purecounter-start="0"
                    data-purecounter-end="0" data-purecounter-duration="2">0</span><span>+</span></h3>
                <p class="mb-0">Hewan Qurban</p>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center text-white" data-aos="fade-up"
              data-aos-delay="300">
              <div class="stat-item">
                <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="100"
                    data-purecounter-duration="2">0</span><span>%</span></h3>
                <p class="mb-0">Kepuasan Jamaah</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Statistik-->

  <!-- ======= Testimonial =======-->
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
        <!-- Testimonial 1 -->
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

        <!-- Testimonial 2 -->
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

        <!-- Testimonial 3 -->
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
  <!-- End Testimonial -->

  <!-- ======= FAQ =======-->
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
              <!-- Pertanyaan 1 -->
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

              <!-- Pertanyaan 2 -->
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                    aria-controls="panelsStayOpen-collapseTwo">
                    Bagaimana sistem pembayaran dan konfirmasinya?
                  </button>
                </h2>
                <div class="accordion-collapse collapse" id="panelsStayOpen-collapseTwo">
                  <div class="accordion-body">
                    Setelah memilih hewan, lakukan pembayaran via transfer bank/dompet digital. Upload bukti
                    transfer di halaman checkout. Tim kami akan verifikasi maksimal 1x24 jam dan mengirim notifikasi
                    via email/WA.
                  </div>
                </div>
              </div>

              <!-- Pertanyaan 3 -->
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

              <!-- Pertanyaan 4 -->
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

              <!-- Pertanyaan 5 -->
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
                    Bisa! Saat checkout:<br>
                    1. Centang opsi "Atas Nama Orang Lain"<br>
                    2. Isi nama yang diinginkan (contoh: Keluarga Bapak Ahmad)<br>
                    3. Kami akan sertakan nama tersebut dalam sertifikat qurban digital yang dikirim ke email Anda.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End FAQ-->

  <!-- ======= Kontak =======-->
  <section class="section contact__v2" id="kontak">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-6 col-lg-7 mx-auto text-center">
          <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Hubungi Kami</span>
          <h2 class="h2 fw-bold mb-3" data-aos="fade-up" data-aos-delay="0">Layanan Qurban Syariah</h2>
          <p data-aos="fade-up" data-aos-delay="100">Konsultasikan kebutuhan qurban Anda bersama tim ahli kami yang
            siap membantu 24 jam sebelum Idul Adha.</p>
        </div>
      </div>
      <div class="row">
        <!-- Form Kontak -->
        <div class="col-md-8 mx-auto aos-init aos-animate">
          <div class="form-wrapper" data-aos="fade-up" data-aos-delay="400">
            <form id="contactForm">
              <div class="row gap-3 mb-3">
                <div class="col-md-12">
                  <label class="mb-2" for="name">Nama Lengkap*</label>
                  <input class="form-control" id="name" type="text" name="name" placeholder="Contoh: Mayfita Ramadanti"
                    required>
                </div>
                <div class="col-md-12">
                  <label class="mb-2" for="email">Email/Nomor WhatsApp*</label>
                  <input class="form-control" id="contact" type="text" name="contact"
                    placeholder="Contoh: 0896-3737-1853" required>
                </div>
              </div>
              <div class="row gap-3 mb-3">
                <div class="col-md-12">
                  <label class="mb-2" for="subject">Jenis Pertanyaan*</label>
                  <select class="form-select" id="subject" name="subject" required>
                    <option value="">-- Pilih --</option>
                    <option value="Pemesanan">Pemesanan Hewan Qurban</option>
                    <option value="Pembayaran">Konfirmasi Pembayaran</option>
                    <option value="Distribusi">Info Distribusi Qurban</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>
              </div>
              <div class="row gap-3 mb-3">
                <div class="col-md-12">
                  <label class="mb-2" for="message">Pesan Detail*</label>
                  <textarea class="form-control" id="message" name="message" rows="5"
                    placeholder="Contoh: Saya ingin memesan 1 ekor sapi untuk qurban di daerah Bogor..."
                    required></textarea>
                </div>
              </div>
              <button class="btn btn-primary fw-semibold w-100 py-3" type="submit">
                <i class="bi bi-send-fill me-2"></i> Kirim Pesan
              </button>
            </form>
            <div class="mt-3 d-none alert alert-success" id="successMessage">
              <i class="bi bi-check-circle-fill me-2"></i> Pesan terkirim! Tim kami akan menghubungi dalam 1x24 jam.
            </div>
            <div class="mt-3 d-none alert alert-danger" id="errorMessage">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mengirim. Silakan coba lagi atau hubungi
              WhatsApp kami.
            </div>

            <!-- Info Penting -->
            <div class="alert alert-warning mt-4">
              <h6><i class="bi bi-info-circle-fill me-2"></i> Penting!</h6>
              <ul class="mb-0 ps-3">
                <li>Untuk konfirmasi pembayaran, harap sertakan nomor invoice</li>
                <li>Respon tercepat via WhatsApp (+62 812-1217-5179)</li>
                <li>Jam operasional H-7 Idul Adha: 24 jam</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Kontak-->
  <?php include 'components/footer.php'; ?>