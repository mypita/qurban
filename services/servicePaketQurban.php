<?php
session_start(); // Pastikan session dimulai untuk mengakses $_SESSION['user_id']
require_once 'konek.php'; // Sesuaikan path ke file koneksi database Anda

header('Content-Type: application/json');

// Fungsi untuk membersihkan input (jika belum ada di konek.php)
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

/**
 * Fungsi untuk menambahkan item ke keranjang belanja.
 * Jika item sudah ada, kuantitasnya akan diperbarui. Jika belum, item baru akan ditambahkan.
 *
 * @param mysqli $conn Objek koneksi database.
 * @param int $user_id ID pengguna yang sedang login.
 * @param int $animal_id ID hewan yang akan ditambahkan.
 * @return bool True jika operasi berhasil, false jika gagal.
 */
function addToCart($conn, $user_id, $animal_id)
{
    // Periksa apakah item sudah ada di keranjang untuk pengguna ini dan hewan ini
    $check_stmt = $conn->prepare("SELECT id, quantity FROM carts WHERE user_id = ? AND animal_id = ?");
    if (!$check_stmt) {
        error_log("Prepare statement failed (check_stmt): " . $conn->error);
        return false;
    }
    $check_stmt->bind_param("ii", $user_id, $animal_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika item sudah ada, perbarui kuantitasnya
        $item = $result->fetch_assoc();
        $new_quantity = $item['quantity'] + 1;
        $update_stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ?");
        if (!$update_stmt) {
            error_log("Prepare statement failed (update_stmt): " . $conn->error);
            return false;
        }
        $update_stmt->bind_param("ii", $new_quantity, $item['id']);
        return $update_stmt->execute();
    } else {
        // Jika item belum ada, tambahkan item baru ke keranjang
        $insert_stmt = $conn->prepare("INSERT INTO carts (user_id, animal_id, quantity) VALUES (?, ?, 1)");
        if (!$insert_stmt) {
            error_log("Prepare statement failed (insert_stmt): " . $conn->error);
            return false;
        }
        $insert_stmt->bind_param("ii", $user_id, $animal_id);
        return $insert_stmt->execute();
    }
}

/**
 * Fungsi untuk mendapatkan jumlah total item di keranjang belanja pengguna.
 *
 * @param mysqli $conn Objek koneksi database.
 * @param int $user_id ID pengguna.
 * @return int Jumlah total item di keranjang.
 */
function getUserCartCount($conn, $user_id) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM carts WHERE user_id = ?");
    if (!$stmt) {
        error_log("Prepare statement failed (getUserCartCount): " . $conn->error);
        return 0;
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    // Jika tidak ada item di keranjang, SUM akan mengembalikan NULL, jadi pastikan mengembalikan 0
    return (int)($row['total_items'] ?? 0);
}

/**
 * Fungsi untuk mendapatkan detail item di keranjang pengguna.
 *
 * @param mysqli $conn Objek koneksi database.
 * @param int $user_id ID pengguna.
 * @return array Array asosiatif dari item keranjang.
 */
function getCartItems($conn, $user_id)
{
    $query = "SELECT c.id as cart_item_id, c.quantity, a.id as animal_id, a.type, a.weight_group, a.price, a.image_url, a.description, a.weight_range, a.stock
              FROM carts c
              JOIN animals a ON c.animal_id = a.id
              WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare statement failed (getCartItems): " . $conn->error);
        return [];
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// --- Fungsi-fungsi yang sudah ada sebelumnya (tetap dipertahankan) ---

// Fungsi untuk mendapatkan semua paket qurban
function getAllPaketQurban() {
    global $conn;

    $query = "SELECT * FROM animals ORDER BY type, price";
    $result = $conn->query($query);

    $paket = array();
    while ($row = $result->fetch_assoc()) {
        $paket[] = $row;
    }

    return $paket;
}

// Fungsi untuk mendapatkan paket qurban berdasarkan ID
function getPaketQurbanById($id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

// Fungsi untuk mendapatkan paket qurban berdasarkan jenis hewan
function getPaketQurbanByType($type) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM animals WHERE type = ? ORDER BY price");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();

    $paket = array();
    while ($row = $result->fetch_assoc()) {
        $paket[] = $row;
    }

    return $paket;
}

// Fungsi untuk mengurangi stok hewan qurban
function reduceStock($animal_id, $quantity) {
    global $conn;

    $stmt = $conn->prepare("UPDATE animals SET stock = stock - ? WHERE id = ? AND stock >= ?");
    $stmt->bind_param("iii", $quantity, $animal_id, $quantity);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

// Fungsi untuk menambah stok hewan qurban
function increaseStock($animal_id, $quantity) {
    global $conn;

    $stmt = $conn->prepare("UPDATE animals SET stock = stock + ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $animal_id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

// --- Penanganan permintaan HTTP ---
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Penanganan permintaan GET yang sudah ada
        if (isset($_GET['id'])) {
            $paket = getPaketQurbanById(clean_input($_GET['id']));
            echo json_encode($paket);
        } elseif (isset($_GET['type'])) {
            $paket = getPaketQurbanByType(clean_input($_GET['type']));
            echo json_encode($paket);
        } else {
            $paket = getAllPaketQurban();
            echo json_encode($paket);
        }
        break;

    case 'POST':
        // Pastikan pengguna sudah login untuk semua operasi POST yang terkait keranjang
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'message' => 'Anda harus login untuk melakukan aksi ini.']);
            exit;
        }
        $user_id = $_SESSION['user_id'];

        $action = isset($_POST['action']) ? clean_input($_POST['action']) : null;
        $data = json_decode(file_get_contents('php://input'), true); // Untuk JSON body jika ada

        // Prioritaskan $_POST untuk form-data, lalu $data untuk json
        if ($action === null && isset($data['action'])) {
            $action = clean_input($data['action']);
        }

        switch ($action) {
            case 'add_to_cart':
                $animal_id = isset($_POST['animal_id']) ? clean_input($_POST['animal_id']) : null;

                if ($animal_id === null) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['success' => false, 'message' => 'ID hewan tidak valid.']);
                    exit;
                }

                if (addToCart($conn, $user_id, $animal_id)) {
                    $new_cart_count = getUserCartCount($conn, $user_id); // Dapatkan jumlah keranjang terbaru
                    echo json_encode(['success' => true, 'message' => 'Item berhasil ditambahkan ke keranjang.', 'cart_count' => $new_cart_count]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan item ke keranjang. Silakan coba lagi.']);
                }
                break;

            case 'remove_from_cart':
                $cart_id = isset($_POST['cart_id']) ? clean_input($_POST['cart_id']) : null;

                if ($cart_id === null) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID keranjang tidak valid.']);
                    exit;
                }

                $stmt = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
                if (!$stmt) {
                    error_log("Prepare statement failed (remove_from_cart): " . $conn->error);
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
                    exit;
                }
                $stmt->bind_param("ii", $cart_id, $user_id);
                if ($stmt->execute()) {
                    $new_cart_count = getUserCartCount($conn, $user_id);
                    $updated_cart_items = getCartItems($conn, $user_id);
                    echo json_encode(['success' => true, 'message' => 'Item berhasil dihapus dari keranjang.', 'cart_count' => $new_cart_count, 'cart_items' => $updated_cart_items]);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus item dari keranjang.']);
                }
                $stmt->close();
                break;

            case 'update_cart_quantity':
                // Menerima array kuantitas dari form
                $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

                $conn->begin_transaction();
                try {
                    foreach ($quantities as $cart_id => $quantity) {
                        $cart_id = clean_input($cart_id);
                        $quantity = clean_input($quantity);

                        // Ambil animal_id dari cart untuk validasi stok
                        $animal_id_query = $conn->prepare("SELECT animal_id FROM carts WHERE id = ? AND user_id = ?");
                        $animal_id_query->bind_param("ii", $cart_id, $user_id);
                        $animal_id_query->execute();
                        $animal_id_result = $animal_id_query->get_result();
                        $animal_id_data = $animal_id_result->fetch_assoc();
                        $animal_id_query->close();

                        if (!$animal_id_data) {
                            throw new Exception("Item keranjang tidak ditemukan atau bukan milik Anda.");
                        }
                        $current_animal_id = $animal_id_data['animal_id'];

                        // Dapatkan stok hewan saat ini
                        $stock_query = $conn->prepare("SELECT stock FROM animals WHERE id = ?");
                        $stock_query->bind_param("i", $current_animal_id);
                        $stock_query->execute();
                        $stock_result = $stock_query->get_result();
                        $stock_data = $stock_result->fetch_assoc();
                        $stock_query->close();

                        $available_stock = $stock_data['stock'];

                        if ($quantity <= 0) {
                            // Hapus item jika kuantitas 0 atau kurang
                            $stmt = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
                            $stmt->bind_param("ii", $cart_id, $user_id);
                        } else {
                            // Validasi stok sebelum update
                            if ($quantity > $available_stock) {
                                throw new Exception("Stok tidak mencukupi untuk item ini. Stok tersedia: " . $available_stock);
                            }
                            // Perbarui kuantitas
                            $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
                            $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
                        }
                        if (!$stmt->execute()) {
                            throw new Exception("Gagal memperbarui item keranjang (ID: " . $cart_id . ").");
                        }
                        $stmt->close();
                    }
                    $conn->commit();
                    $new_cart_count = getUserCartCount($conn, $user_id);
                    $updated_cart_items = getCartItems($conn, $user_id);
                    echo json_encode(['success' => true, 'message' => 'Keranjang berhasil diperbarui.', 'cart_count' => $new_cart_count, 'cart_items' => $updated_cart_items]);
                } catch (Exception $e) {
                    $conn->rollback();
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                break;

            case 'reduce_stock': // Existing action
                if (isset($data['animal_id']) && isset($data['quantity'])) {
                    $success = reduceStock(clean_input($data['animal_id']), clean_input($data['quantity']));
                    echo json_encode(['success' => $success]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Parameter tidak valid untuk reduce_stock']);
                }
                break;

            case 'increase_stock': // Existing action
                if (isset($data['animal_id']) && isset($data['quantity'])) {
                    $success = increaseStock(clean_input($data['animal_id']), clean_input($data['quantity']));
                    echo json_encode(['success' => $success]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Parameter tidak valid untuk increase_stock']);
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Aksi tidak valid']);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Metode tidak diizinkan']);
}

$conn->close();
?>
