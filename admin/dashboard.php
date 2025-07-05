<?php
session_start();
require_once '../services/konek.php'; // Pastikan path ini benar

// Cek session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fungsi untuk membersihkan input (jika belum ada di konek.php)
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

// Data untuk dashboard
$dashboard_data = [
    'total_orders' => 0,
    'pending_orders' => 0,
    'paid_orders' => 0,
    'processed_orders' => 0,
    'shipped_orders' => 0,
    'delivered_orders' => 0,
    'cancelled_orders' => 0,
    'total_revenue' => 0,
    'total_customers' => 0,
    'animal_stock' => []
];

$recent_orders = [];
$recent_admin_logs = [];
$error_message = '';

try {
    // 1. Total Pesanan berdasarkan Status dan Total Pendapatan
    $status_counts_query = $conn->query("SELECT status, COUNT(id) as count, SUM(total_price) as total_sum FROM orders GROUP BY status");
    if ($status_counts_query) {
        while ($row = $status_counts_query->fetch_assoc()) {
            $dashboard_data['total_orders'] += $row['count'];
            $dashboard_data[$row['status'] . '_orders'] = $row['count'];
            // Pendapatan dihitung dari status 'paid', 'processed', 'shipped', 'delivered'
            if ($row['status'] === 'paid' || $row['status'] === 'processed' || $row['status'] === 'shipped' || $row['status'] === 'delivered') {
                $dashboard_data['total_revenue'] += $row['total_sum'];
            }
        }
    } else {
        error_log("Error fetching order status counts: " . $conn->error);
        $error_message .= "Gagal mengambil data status pesanan. ";
    }

    // 2. Total Pelanggan
    $customers_query = $conn->query("SELECT COUNT(id) as count FROM users WHERE role = 'customer'");
    if ($customers_query) {
        $customer_row = $customers_query->fetch_assoc();
        $dashboard_data['total_customers'] = $customer_row['count'];
    } else {
        error_log("Error fetching total customers: " . $conn->error);
        $error_message .= "Gagal mengambil data total pelanggan. ";
    }

    // 3. Stok Hewan
    $animal_stock_query = $conn->query("SELECT type, weight_group, stock FROM animals ORDER BY type, weight_group");
    if ($animal_stock_query) {
        while ($row = $animal_stock_query->fetch_assoc()) {
            $dashboard_data['animal_stock'][] = $row;
        }
    } else {
        error_log("Error fetching animal stock: " . $conn->error);
        $error_message .= "Gagal mengambil data stok hewan. ";
    }

    // 4. Pesanan Terbaru (misal 5 pesanan terakhir)
    $recent_orders_query = $conn->query("
        SELECT o.id, u.name as customer_name, o.total_price, o.status, o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    if ($recent_orders_query) {
        while ($row = $recent_orders_query->fetch_assoc()) {
            $recent_orders[] = $row;
        }
    } else {
        error_log("Error fetching recent orders: " . $conn->error);
        $error_message .= "Gagal mengambil data pesanan terbaru. ";
    }

    // 5. Log Aktivitas Admin Terbaru (misal 5 log terakhir)
    $recent_logs_query = $conn->query("
        SELECT al.action, al.notes, al.created_at, u.name as admin_name
        FROM admin_logs al
        JOIN users u ON al.admin_id = u.id
        ORDER BY al.created_at DESC
        LIMIT 5
    ");
    if ($recent_logs_query) {
        while ($row = $recent_logs_query->fetch_assoc()) {
            $recent_admin_logs[] = $row;
        }
    } else {
        error_log("Error fetching recent admin logs: " . $conn->error);
        $error_message .= "Gagal mengambil data log admin terbaru. ";
    }

} catch (Exception $e) {
    error_log("Error fetching dashboard data: " . $e->getMessage());
    $error_message .= "Terjadi kesalahan umum saat mengambil data dashboard: " . $e->getMessage();
}

$active = 'dashboard'; // Untuk menandai menu aktif di sidebar

// Status text mapping for display (consistent with user-facing pages)
$status_text_map = [
    'pending' => 'Menunggu Pembayaran',
    'paid' => 'Telah Dibayar',
    'processed' => 'Diproses',
    'shipped' => 'Dikirim',
    'delivered' => 'Terkirim',
    'cancelled' => 'Dibatalkan'
];
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Dashboard Admin | Qurban App</title>
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
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="../assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="../assets/css/style-preset.css">

    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-processed {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .tbl-card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="./dashboard.php" class="b-brand text-primary">
                    <!-- ========   Change your logo from here    ============ -->
                    <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item active">
                        <a href="./dashboard.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Database</label>
                        <i class="ti ti-database"></i> <!-- Changed icon for database section -->
                    </li>
                    <li class="pc-item">
                        <a href="./list_pesanan.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span> <!-- Changed icon -->
                            <span class="pc-mtext">List Pesanan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="./list_paket.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-box"></i></span> <!-- Changed icon -->
                            <span class="pc-mtext">List Paket</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <!-- ======= Menu collapse Icon ===== -->
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
                            <span>Admin</span> <!-- Placeholder for admin name -->
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="../assets/images/user/avatar-2.jpg" alt="user-image"
                                            class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></h6>
                                        <span>Administrator</span>
                                    </div>
                                    <a href="../logout.php" class="pc-head-link bg-transparent"><i
                                            class="ti ti-power text-danger"></i></a>
                                </div>
                            </div>
                            <div class="tab-content" id="mysrpTabContent">
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">
                                    <a href="../logout.php" class="dropdown-item">
                                        <i class="ti ti-power"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <?php if ($error_message): ?>
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Summary Cards Start -->
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-primary fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 f-w-400 text-muted">Total Pesanan</h6>
                                    <h4 class="mb-0"><?= number_format($dashboard_data['total_orders'], 0, ',', '.') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-dollar-sign text-success fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 f-w-400 text-muted">Total Pendapatan</h6>
                                    <h4 class="mb-0">Rp <?= number_format($dashboard_data['total_revenue'], 0, ',', '.') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-info fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 f-w-400 text-muted">Total Pelanggan</h6>
                                    <h4 class="mb-0"><?= number_format($dashboard_data['total_customers'], 0, ',', '.') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-hourglass-half text-warning fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 f-w-400 text-muted">Pesanan Menunggu Pembayaran</h6>
                                    <h4 class="mb-0"><?= number_format($dashboard_data['pending_orders'], 0, ',', '.') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Summary Cards End -->

                <!-- Order Status Breakdown Start -->
                <div class="col-md-6 col-xl-6">
                    <h5 class="mb-3">Ringkasan Status Pesanan</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Status</th>
                                            <th class="text-end">Jumlah Pesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($status_text_map as $status_key => $status_label): ?>
                                            <tr>
                                                <td>
                                                    <span class="status-badge status-<?= $status_key ?>">
                                                        <?= $status_label ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <?= number_format($dashboard_data[$status_key . '_orders'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Status Breakdown End -->

                <!-- Animal Stock Start -->
                <div class="col-md-6 col-xl-6">
                    <h5 class="mb-3">Stok Hewan Qurban</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipe Hewan</th>
                                            <th>Kelompok Bobot</th>
                                            <th class="text-end">Stok Tersedia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($dashboard_data['animal_stock'])): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Tidak ada data stok hewan.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($dashboard_data['animal_stock'] as $animal): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(ucfirst($animal['type'])) ?></td>
                                                    <td><?= htmlspecialchars($animal['weight_group']) ?></td>
                                                    <td class="text-end"><?= number_format($animal['stock'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Animal Stock End -->

                <!-- Recent Orders Start -->
                <div class="col-md-12 col-xl-8">
                    <h5 class="mb-3">Pesanan Terbaru</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Pesanan</th>
                                            <th>Pelanggan</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Tanggal Pesan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recent_orders)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Tidak ada pesanan terbaru.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recent_orders as $order): ?>
                                                <tr>
                                                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                                                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <span class="status-badge status-<?= $order['status'] ?>">
                                                            <?= htmlspecialchars($status_text_map[$order['status']] ?? ucwords($order['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Recent Orders End -->

                <!-- Recent Admin Activity Start -->
                <div class="col-md-12 col-xl-4">
                    <h5 class="mb-3">Aktivitas Admin Terbaru</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php if (empty($recent_admin_logs)): ?>
                                    <div class="list-group-item text-center text-muted">Tidak ada aktivitas admin terbaru.</div>
                                <?php else: ?>
                                    <?php foreach ($recent_admin_logs as $log): ?>
                                        <div class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-s rounded-circle text-primary bg-light-primary">
                                                        <i class="ti ti-activity f-18"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><?= htmlspecialchars($log['action']) ?> oleh <?= htmlspecialchars($log['admin_name']) ?></h6>
                                                    <p class="mb-0 text-muted">
                                                        <?= htmlspecialchars($log['notes'] ? $log['notes'] : 'Tidak ada catatan.') ?><br>
                                                        <small><?= date('d M Y, H:i', strtotime($log['created_at'])) ?></small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Recent Admin Activity End -->

            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Mantis &#9829; crafted by Team <a href="https://themeforest.net/user/codedthemes"
                            target="_blank">Codedthemes</a> Distributed by <a
                            href="https://themewagon.com/">ThemeWagon</a>.</p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="../index.html">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- [Page Specific JS] start -->
    <script src="../assets/js/plugins/apexcharts.min.js"></script>
    <script src="../assets/js/pages/dashboard-default.js"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/fonts/custom-font.js"></script>
    <script src="../assets/js/pcoded.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>

    <script>layout_change('light');</script>
    <script>change_box_container('false');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>
    <script>font_change("Public-Sans");</script>

</body>
<!-- [Body] end -->

</html>
