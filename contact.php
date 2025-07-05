<?php
session_start();
require_once './services/konek.php';
?>
<?php include 'components/header.php'; ?>

<!-- ======= Main =======-->
<main>
  <!-- ======= Kontak =======-->
  <section class="hero__v6 section" id="contact">
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
                  <input class="form-control" id="name" type="text" name="name" placeholder="Contoh: Ahmad Fauzi"
                    required>
                </div>
                <div class="col-md-12">
                  <label class="mb-2" for="email">Email/Nomor WhatsApp*</label>
                  <input class="form-control" id="contact" type="text" name="contact"
                    placeholder="Contoh: 0812-3456-7890" required>
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
                <li>Respon tercepat via WhatsApp (+62 812-3456-7890)</li>
                <li>Jam operasional H-7 Idul Adha: 24 jam</li>
              </ul>
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

  <?php include 'components/footer.php'; ?>