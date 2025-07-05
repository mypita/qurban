<?php
session_start();
require_once './services/konek.php';
?>
<?php include 'components/header.php'; ?>

    <!-- ======= Main =======-->
    <main>
      <!-- ======= testimonial =======-->
      <section class="hero__v6 section" id="testimonial">
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


            <!-- Testimonial 4 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
              <div class="testimonial rounded-4 p-4 shadow-sm bg-white">
                <div class="quote-icon mb-3 text-warning">
                  <i class="bi bi-quote fs-1"></i>
                </div>
                <blockquote class="mb-3 fs-5 fst-italic">
                  "Sebagai karyawan dengan gaji pas-pasan, saya sangat terbantu dengan program qurban kambing disini.
                  Hewannya sehat, prosesnya syar'i, dan yang penting bisa ikut berqurban tanpa harus mengeluarkan biaya
                  besar. Tahun depan insya Allah akan ikut lagi!"
                </blockquote>
                <div class="testimonial-author d-flex gap-3 align-items-center">
                  <div class="author-img">
                    <img class="rounded-circle img-fluid" src="assets/images/testimonial/5.jpeg" alt="Bapak Rudi"
                      style="width: 60px; height: 60px; object-fit: cover;">
                  </div>
                  <div class="lh-base">
                    <strong class="d-block">Rudi Hartono</strong>
                    <span>Karyawan, Tangerang</span>
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

            <!-- Testimonial 5 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
              <div class="testimonial rounded-4 p-4 shadow-sm bg-white">
                <div class="quote-icon mb-3 text-warning">
                  <i class="bi bi-quote fs-1"></i>
                </div>
                <blockquote class="mb-3 fs-5 fst-italic">
                  "Qurban corporate untuk 50 karyawan kami tahun ini sangat memuaskan. Laporan real-time dengan foto dan
                  video membuat proses qurban jadi transparan. Tahun depan kami akan gunakan layanan ini lagi dan
                  menambah jumlah hewan qurban."
                </blockquote>
                <div class="testimonial-author d-flex gap-3 align-items-center">
                  <div class="author-img">
                    <img class="rounded-circle img-fluid" src="assets/images/testimonial/6.jpeg" alt="Bapak Faisal"
                      style="width: 60px; height: 60px; object-fit: cover;">
                  </div>
                  <div class="lh-base">
                    <strong class="d-block">Faisal Rahman</strong>
                    <span>Direktur Perusahaan, Jakarta</span>
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

            <!-- Testimonial 6 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
              <div class="testimonial rounded-4 p-4 shadow-sm bg-white">
                <div class="quote-icon mb-3 text-warning">
                  <i class="bi bi-quote fs-1"></i>
                </div>
                <blockquote class="mb-3 fs-5 fst-italic">
                  "Sebagai ustadzah, saya sering ditanya tentang tempat qurban yang terpercaya. Setelah mencoba sendiri,
                  saya rekomendasikan layanan ini ke jamaah karena: 1) Hewan sesuai syarat 2) Penyembelihan syar'i 3)
                  Distribusi merata ke daerah yang membutuhkan."
                </blockquote>
                <div class="testimonial-author d-flex gap-3 align-items-center">
                  <div class="author-img">
                    <img class="rounded-circle img-fluid" src="assets/images/testimonial/3.jpeg" alt="Ustadz Ali"
                      style="width: 60px; height: 60px; object-fit: cover;">
                  </div>
                  <div class="lh-base">
                    <strong class="d-block">Ustadzah Halimah Alaymun</strong>
                    <span>Pengasuh Pesantren, Surabaya</span>
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

          </div>
        </div>
      </section>
      <!-- End testimonial-->
  <?php include 'components/footer.php'; ?>