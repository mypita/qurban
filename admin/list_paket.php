<?php
session_start();
require_once '../services/konek.php';

// Cek session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fungsi untuk mendapatkan daftar paket hewan qurban
function getAnimalPackages($conn) {
    $query = "SELECT * FROM animals ORDER BY type, price";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Proses update paket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_package'])) {
    $id = clean_input($_POST['id']);
    $type = clean_input($_POST['type']);
    $price = clean_input($_POST['price']);
    $stock = clean_input($_POST['stock']);
    $weight_group = clean_input($_POST['weight_group']);
    $weight_range = clean_input($_POST['weight_range']);
    $description = clean_input($_POST['description']);
    
    // Update data paket
    $stmt = $conn->prepare("UPDATE animals SET 
                          type = ?, 
                          price = ?, 
                          stock = ?, 
                          weight_group = ?, 
                          weight_range = ?, 
                          description = ? 
                          WHERE id = ?");
    $stmt->bind_param("sdissss", $type, $price, $stock, $weight_group, $weight_range, $description, $id);
    
    if ($stmt->execute()) {
        header("Location: list_paket.php?success=Paket berhasil diupdate");
        exit;
    } else {
        $error = "Gagal mengupdate paket: " . $conn->error;
    }
    $stmt->close();
}

$packages = getAnimalPackages($conn);
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
        .tbl-card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
        }
        .type-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .type-sapi { background-color: #d4edda; color: #155724; }
        .type-kambing { background-color: #cce5ff; color: #004085; }
        .type-domba { background-color: #fff3cd; color: #856404; }
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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">List Paket</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="col-md-12">
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NO</th>
                                            <th>JENIS HEWAN</th>
                                            <th>KELOMPOK BERAT</th>
                                            <th>RENTANG BERAT</th>
                                            <th>HARGA</th>
                                            <th>STOK</th>
                                            <th>DESKRIPSI</th>
                                            <th class="text-end">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($packages as $index => $package):
                                            $type_class = 'type-' . $package['type'];
                                            ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <span class="type-badge <?php echo $type_class; ?>">
                                                        <?php echo ucfirst($package['type']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($package['weight_group']); ?></td>
                                                <td><?php echo htmlspecialchars($package['weight_range']); ?></td>
                                                <td>Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></td>
                                                <td><?php echo htmlspecialchars($package['stock']); ?></td>
                                                <td><?php echo htmlspecialchars($package['description']); ?></td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        data-id="<?php echo $package['id']; ?>"
                                                        data-type="<?php echo htmlspecialchars($package['type']); ?>"
                                                        data-price="<?php echo htmlspecialchars($package['price']); ?>"
                                                        data-stock="<?php echo htmlspecialchars($package['stock']); ?>"
                                                        data-weight-group="<?php echo htmlspecialchars($package['weight_group']); ?>"
                                                        data-weight-range="<?php echo htmlspecialchars($package['weight_range']); ?>"
                                                        data-description="<?php echo htmlspecialchars($package['description']); ?>">
                                                        <i class="fas fa-edit"></i>
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
                            <form method="POST" action="list_paket.php">
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="update_package" value="1">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Paket Qurban</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Jenis Hewan</label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="sapi">Sapi</option>
                                            <option value="kambing">Kambing</option>
                                            <option value="domba">Domba</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Harga (Rp)</label>
                                        <input type="number" class="form-control" id="price" name="price" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stok</label>
                                        <input type="number" class="form-control" id="stock" name="stock" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weight_group" class="form-label">Kelompok Berat</label>
                                        <input type="text" class="form-control" id="weight_group" name="weight_group"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weight_range" class="form-label">Rentang Berat</label>
                                        <input type="text" class="form-control" id="weight_range" name="weight_range"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            required></textarea>
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
    <script>
        // Handle edit modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit_id').value = this.getAttribute('data-id');
                document.getElementById('type').value = this.getAttribute('data-type');
                document.getElementById('price').value = this.getAttribute('data-price');
                document.getElementById('stock').value = this.getAttribute('data-stock');
                document.getElementById('weight_group').value = this.getAttribute('data-weight-group');
                document.getElementById('weight_range').value = this.getAttribute('data-weight-range');
                document.getElementById('description').value = this.getAttribute('data-description');
            });
        });
    </script>

</body>

</html>