<?php
session_start();
require_once 'services/konek.php'; // Sesuaikan dengan nama file koneksi Anda

$error = '';
$email = '';

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// Proses form login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = clean_input($_POST['email']);
  $password = clean_input($_POST['password']);
  $remember = isset($_POST['remember']) ? true : false;

  // Validasi input
  if (empty($email) || empty($password)) {
    $error = 'Email dan password harus diisi!';
  } else {
    // Cari user di database
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();

      // Verifikasi password
      if (password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Jika remember me dicentang, set cookie
        if ($remember) {
          $cookie_value = $user['id'] . ':' . hash('sha256', $user['password']);
          setcookie('remember_me', $cookie_value, time() + (86400 * 30), "/"); // 30 hari
        }

        // Redirect ke halaman yang sesuai berdasarkan role
        if ($user['role'] == 'admin') {
          header('Location: admin/dashboard.php');
        } else {
          header('Location: index.php');
        }
        exit;
      } else {
        $error = 'Password salah!';
      }
    } else {
      $error = 'Email tidak terdaftar!';
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Login | CrediQurban - May</title>
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

            <form action="login.php" method="POST">
              <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Masuk ke Akun Anda</b></h3>
                <a href="register.php" class="link-primary">Belum punya akun?</a>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email"
                  value="<?php echo htmlspecialchars($email); ?>" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
              </div>
              <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1">
                  <label class="form-check-label text-muted" for="customCheckc1">Ingat saya</label>
                </div>
                <a href="forgot_password.php" class="text-secondary f-w-400">Lupa Password?</a>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Masuk</button>
              </div>
            </form>
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