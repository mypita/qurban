<!DOCTYPE html>
<!--
Template name: Nova
Template author: FreeBootstrap.net
Author website: https://freebootstrap.net/
License: https://freebootstrap.net/license
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> CrediQurban | Website Qurban terpercaya di Indonesia - May </title>
    <link rel="icon" href="./assets/images/favicon.svg" type="image/x-icon">

    <!-- ======= Google Font =======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">
    <!-- End Google Font-->

    <!-- ======= Styles =======-->
    <link href="assets/vendors/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/vendors/glightbox/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendors/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendors/aos/aos.css" rel="stylesheet">
    <!-- End Styles-->

    <!-- ======= Theme Style =======-->
    <link href="assets/css/style-index.css" rel="stylesheet">
    <!-- End Theme Style-->

    <!-- Font Awesome for cart icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- ======= Apply theme =======-->
    <script>
        // Apply the theme as early as possible to avoid flicker
        (function () {
            const storedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', storedTheme);
        })();
    </script>
</head>

<body>


    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">


        <!-- ======= Header =======-->
        <header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
            <div class="container d-flex align-items-center justify-content-between">


                <!-- Start Logo-->
                <a class="navbar-brand w-auto" href="index.php">
                    <!-- If you use a text logo, uncomment this if it is commented-->
                    <!-- Vertex-->

                    <!-- If you plan to use an image logo, uncomment this if it is commented-->

                    <!-- logo dark--><img class="logo dark img-fluid" src="assets/images/logo-qurban.png" width="200"
                        alt="FreeBootstrap.net image placeholder">

                    <!-- logo light--><img class="logo light img-fluid" src="assets/images/logo-qurban.png" width="200"
                        alt="FreeBootstrap.net image placeholder">

                </a>
                <!-- End Logo-->

                <!-- Start offcanvas-->
                <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1"
                    aria-labelledby="fbs__net-navbarsLabel">


                    <div class="offcanvas-header">
                        <div class="offcanvas-header-logo">
                            <!-- If you use a text logo, uncomment this if it is commented-->

                            <!-- h5#fbs__net-navbarsLabel.offcanvas-title Vertex-->

                            <!-- If you plan to use an image logo, uncomment this if it is commented-->
                            <a class="logo-link" id="fbs__net-navbarsLabel" href="index.php">


                                <!-- logo dark--><img class="logo dark img-fluid" src="assets/images/logo-qurban.png"
                                    width="200" alt="FreeBootstrap.net image placeholder">

                                <!-- logo light--><img class="logo light img-fluid" src="assets/images/logo-qurban.png"
                                    width="200" alt="FreeBootstrap.net image placeholder"></a>

                        </div>
                        <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body align-items-lg-center">
                        <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link scroll-link active" aria-current="page"
                                    href="index.php">Home</a>
                            </li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="paket.php">Paket Qurban</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="testimonial.php">Testimonials</a>
                            </li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="about.php">Tentang Kami</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="contact.php">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <!-- End offcanvas-->

                <div class="ms-auto w-auto">
                    <div class="header-social d-flex align-items-center gap-1">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Cart Button (visible when logged in) -->
                            <a class="btn btn-outline-primary position-relative py-2" href="cart.php">
                                <i class="bi bi-cart"></i>
                                <?php
                                // Pastikan koneksi database tersedia di sini.
                                // Jika header.php di-include oleh file lain yang sudah memiliki $conn,
                                // maka tidak perlu require_once konek.php lagi.
                                // Asumsi $conn sudah tersedia dari file utama (misal paket.php)
                                $cart_count = 0;
                                if (isset($_SESSION['user_id']) && isset($conn)) { // Pastikan $conn ada
                                    $user_id = $_SESSION['user_id'];
                                    $cart_query_stmt = $conn->prepare("SELECT SUM(quantity) as count FROM carts WHERE user_id = ?");
                                    if ($cart_query_stmt) {
                                        $cart_query_stmt->bind_param("i", $user_id);
                                        $cart_query_stmt->execute();
                                        $cart_result = $cart_query_stmt->get_result();
                                        if ($cart_result) {
                                            $cart_data = $cart_result->fetch_assoc();
                                            $cart_count = (int)($cart_data['count'] ?? 0); // Handle NULL if no items
                                        }
                                        $cart_query_stmt->close();
                                    } else {
                                        error_log("Failed to prepare cart count statement in header: " . $conn->error);
                                    }
                                }
                                ?>
                                <span id="cart-item-count"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="<?= ($cart_count > 0) ? '' : 'display: none;' ?>">
                                    <?= $cart_count ?>
                                </span>
                            </a>

                            <!-- Profile Picture (visible when logged in) -->
                            <div class="dropdown">
                                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    // Get user profile picture if available
                                    $profile_pic = "assets/images/qurban/profile.png"; // default image
                                    if (isset($_SESSION['user_id'])) {
                                        // $user_id = $_SESSION['user_id'];
                                        // $user_query = "SELECT profile_picture FROM users WHERE id = $user_id";
                                        // $user_result = $conn->query($user_query);
                                        // if ($user_result && $user_result->num_rows > 0) {
                                        //     $user_data = $user_result->fetch_assoc();
                                        //     if (!empty($user_data['profile_picture'])) {
                                        //         $profile_pic = $user_data['profile_picture'];
                                        //     }
                                        // }
                                    }
                                    ?>
                                    <img src="<?php echo $profile_pic; ?>" alt="Profile" width="62" height="62"
                                        class="rounded-circle">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                    <!-- <li><a class="dropdown-item" href="profile.php">Profile</a></li> -->
                                    <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <!-- Cart Button (visible when not logged in) -->
                            <a class="btn btn-outline-primary py-2" href="login.php?redirect=cart.php">
                                <i class="bi bi-cart"></i>
                            </a>

                            <!-- Login Button (visible when not logged in) -->
                            <a class="btn btn-primary py-2" href="login.php">Login</a>
                        <?php endif; ?>

                        <!-- Mobile Menu Button -->
                        <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto"
                            data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars"
                            aria-controls="fbs__net-navbars" aria-label="Toggle navigation" aria-expanded="false">
                            <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="21" x2="3" y1="6" y2="6"></line>
                                <line x1="15" x2="3" y1="12" y2="12"></line>
                                <line x1="17" x2="3" y1="18" y2="18"></line>
                            </svg>
                            <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header-->
