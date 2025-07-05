<?php
session_start();
require_once '../services/konek.php';

// Cek session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fungsi untuk mendapatkan daftar pesanan
function getOrders($conn)
{
    $query = "SELECT o.*, u.name as customer_name, u.phone as customer_phone 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              ORDER BY o.created_at DESC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk mendapatkan detail hewan qurban dalam pesanan
function getOrderItems($conn, $order_id)
{
    $stmt = $conn->prepare("SELECT oi.*, a.type, a.weight_group 
                           FROM order_items oi 
                           JOIN animals a ON oi.animal_id = a.id 
                           WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Proses perubahan status pesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = clean_input($_POST['order_id']);
    $new_status = clean_input($_POST['status']);
    $admin_notes = clean_input($_POST['admin_notes']);

    // Update status pesanan
    $stmt = $conn->prepare("UPDATE orders SET status = ?, admin_notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_status, $admin_notes, $order_id);
    $stmt->execute();

    // Catat dalam history
    $history_stmt = $conn->prepare("INSERT INTO order_status_history (order_id, status, notes) VALUES (?, ?, ?)");
    $history_stmt->bind_param("iss", $order_id, $new_status, $admin_notes);
    $history_stmt->execute();

    // Catat log admin
    $log_stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, order_id, notes) VALUES (?, ?, ?, ?)");
    $action = "Update status to " . $new_status;
    $log_stmt->bind_param("isis", $_SESSION['user_id'], $action, $order_id, $admin_notes);
    $log_stmt->execute();

    header("Location: list_pesanan.php?success=Status pesanan berhasil diupdate");
    exit;
}

// Proses hapus pesanan
if (isset($_GET['delete'])) {
    $order_id = clean_input($_GET['delete']);

    // Catat log admin sebelum menghapus
    $log_stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, order_id, notes) VALUES (?, ?, ?, ?)");
    $action = "Delete order";
    $notes = "Order deleted by admin";
    $log_stmt->bind_param("isis", $_SESSION['user_id'], $action, $order_id, $notes);
    $log_stmt->execute();

    // Hapus pesanan
    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_stmt->bind_param("i", $order_id);
    $delete_stmt->execute();

    header("Location: list_pesanan.php?success=Pesanan berhasil dihapus");
    exit;
}

$orders = getOrders($conn);
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Home | Mantis Bootstrap 5 Admin Template</title>
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
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
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
                <a href="../dashboard/index.html" class="b-brand text-primary">
                    <!-- ========   Change your logo from here   ============ -->
                    <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="./dashboard.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Database</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="./list_pesanan.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-typography"></i></span>
                            <span class="pc-mtext">List Pesanan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="./list_paket.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-typography"></i></span>
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
                            <span>Stebin Ben</span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="../assets/images/user/avatar-2.jpg" alt="user-image"
                                            class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Stebin Ben</h6>
                                        <span>UI/UX Designer</span>
                                    </div>
                                    <a href="#!" class="pc-head-link bg-transparent"><i
                                            class="ti ti-power text-danger"></i></a>
                                </div>
                            </div>
                            <div class="tab-content" id="mysrpTabContent">
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">
                                    <a href="#!" class="dropdown-item">
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
                                <h5 class="m-b-10">Home</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript: void(0)">List Pesanan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <div class="col-md-12">
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NO</th>
                                            <th>PELANGGAN</th>
                                            <th>DETAIL QURBAN</th>
                                            <th>ALAMAT PENGIRIMAN</th>
                                            <th>TOTAL</th>
                                            <th>STATUS</th>
                                            <th>TANGGAL</th>
                                            <th class="text-end">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order):
                                            $items = getOrderItems($conn, $order['id']);
                                            $status_class = 'status-' . $order['status'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                                                </td>
                                                <td>
                                                    <?php foreach ($items as $item): ?>
                                                        <?php echo $item['quantity'] . ' ' . ucfirst($item['type']) . ' (' . $item['weight_group'] . ')'; ?><br>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></td>
                                                <td>Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <span class="status-badge <?php echo $status_class; ?>">
                                                        <?php
                                                        $status_text = [
                                                            'pending' => 'Menunggu Pembayaran',
                                                            'paid' => 'Telah Dibayar',
                                                            'processed' => 'Diproses',
                                                            'shipped' => 'Dikirim',
                                                            'delivered' => 'Terkirim',
                                                            'cancelled' => 'Dibatalkan'
                                                        ];
                                                        echo $status_text[$order['status']];
                                                        ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        data-orderid="<?php echo $order['id']; ?>"
                                                        data-status="<?php echo $order['status']; ?>"
                                                        data-notes="<?php echo htmlspecialchars($order['admin_notes']); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-orderid="<?php echo $order['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="list_pesanan.php">
                                <input type="hidden" name="order_id" id="edit_order_id">
                                <input type="hidden" name="update_status" value="1">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Status Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="pending">Menunggu Pembayaran</option>
                                            <option value="paid">Telah Dibayar</option>
                                            <option value="processed">Diproses</option>
                                            <option value="shipped">Dikirim</option>
                                            <option value="delivered">Terkirim</option>
                                            <option value="cancelled">Dibatalkan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Catatan Admin</label>
                                        <textarea class="form-control" id="admin_notes" name="admin_notes"
                                            rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Hapus Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <a href="#" id="confirm_delete" class="btn btn-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Mantis &#9829; crafted by Team <a href="https://themeforest.net/user/codedthemes"
                            target="_blank">Codedthemes</a>
                        Distributed by <a href="https://themewagon.com/">ThemeWagon</a>.</p>
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



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const orderId = this.getAttribute('data-orderid');
                const status = this.getAttribute('data-status');
                const notes = this.getAttribute('data-notes');

                document.getElementById('edit_order_id').value = orderId;
                document.getElementById('status').value = status;
                document.getElementById('admin_notes').value = notes;
            });
        });

        // Handle delete modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const orderId = this.getAttribute('data-orderid');
                document.getElementById('confirm_delete').href = `list_pesanan.php?delete=${orderId}`;
            });
        });
    </script>


</body>
<!-- [Body] end -->

</html>