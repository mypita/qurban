<?php
session_start();
require_once 'services/konek.php'; // Sesuaikan dengan nama file koneksi Anda

$error = '';
$success = '';
$name = '';
$email = '';
$phone = '';

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// Proses form registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = clean_input($_POST['name']);
  $email = clean_input($_POST['email']);
  $phone = clean_input($_POST['phone']);
  $password = clean_input($_POST['password']);
  $confirm_password = clean_input($_POST['confirm_password']);

  // Validasi input
  if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
    $error = 'Semua field wajib diisi!';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Format email tidak valid!';
  } elseif ($password !== $confirm_password) {
    $error = 'Konfirmasi password tidak cocok!';
  } elseif (strlen($password) < 6) {
    $error = 'Password minimal 6 karakter!';
  } else {
    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = 'Email sudah terdaftar!';
    } else {
      // Hash password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Simpan ke database
      $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'customer')");
      $insert_stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);

      if ($insert_stmt->execute()) {
        $success = 'Pendaftaran berhasil! Silakan <a href="login.php">login</a>.';
        // Kosongkan form
        $name = $email = $phone = '';
      } else {
        $error = 'Terjadi kesalahan saat menyimpan data: ' . $conn->error;
      }
      $insert_stmt->close();
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Sign up | CrediQurban - May</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords"
    content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="./assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="./assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="./assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="./assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="./assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="./assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="./assets/css/style-preset.css">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="auth-header">
          <a href="index.php"><img src="assets/images/logo-qurban.png" alt="Logo Qurban" width="200"></a>
        </div>
        <div class="card my-5">
          <div class="card-body">
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
              <div class="alert alert-success"><?php echo $success; ?></div>
            <?php else: ?>

              <form action="register.php" method="POST">
                <div class="d-flex justify-content-between align-items-end mb-4">
                  <h3 class="mb-0"><b>Buat Akun Baru</b></h3>
                  <a href="login.php" class="link-primary">Sudah punya akun?</a>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Nama Lengkap*</label>
                  <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap"
                    value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Alamat Email*</label>
                  <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email"
                    value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Nomor Telepon*</label>
                  <input type="tel" name="phone" class="form-control" placeholder="Masukkan nomor telepon"
                    value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Password*</label>
                  <input type="password" name="password" class="form-control" placeholder="Buat password (min 6 karakter)"
                    required>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Konfirmasi Password*</label>
                  <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password"
                    required>
                </div>

                <p class="mt-4 text-sm text-muted">Dengan mendaftar, Anda menyetujui <a href="terms.php"
                    class="text-primary">Syarat & Ketentuan</a> dan <a href="privacy.php" class="text-primary">Kebijakan
                    Privasi</a> kami</p>

                <div class="d-grid mt-3">
                  <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                </div>

              </form>

            <?php endif; ?>
          </div>
        </div>
        <div class="auth-footer row">
          <div class="col my-1">
            <p class="m-0">Hak Cipta © <a href="index.php">CrediQurban</a></p>
          </div>
          <div class="col-auto my-1">
            <ul class="list-inline footer-link mb-0">
              <li class="list-inline-item"><a href="index.php">Beranda</a></li>
              <li class="list-inline-item"><a href="about.php">Tentang Kami</a></li>
              <li class="list-inline-item"><a href="contact.php">Kontak</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  <script src="./assets/js/plugins/popper.min.js"></script>
  <script src="./assets/js/plugins/simplebar.min.js"></script>
  <script src="./assets/js/plugins/bootstrap.min.js"></script>
  <script src="./assets/js/fonts/custom-font.js"></script>
  <script src="./assets/js/pcoded.js"></script>
  <script src="./assets/js/plugins/feather.min.js"></script>





  <script>layout_change('light');</script>




  <script>change_box_container('false');</script>



  <script>layout_rtl_change('false');</script>


  <script>preset_change("preset-1");</script>


  <script>font_change("Public-Sans");</script>



</body>
<!-- [Body] end -->

</html>