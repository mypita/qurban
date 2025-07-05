<?php
session_start();
require_once './services/konek.php';
?>
<?php include 'components/header.php'; ?>

<!-- ======= Main =======-->
<main>
  <!-- ======= Tentang Kami =======-->
  <section class="hero__v6 section" id="about">
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
  <?php include 'components/footers.php'; ?>