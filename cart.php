<?php
session_start();
require_once 'services/konek.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Function to get cart items
function getCartItems($conn, $user_id)
{
  $query = "SELECT c.*, a.type, a.weight_group, a.price, a.image_url 
              FROM carts c 
              JOIN animals a ON c.animal_id = a.id 
              WHERE c.user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Process remove item from cart
if (isset($_GET['remove'])) {
  $cart_id = clean_input($_GET['remove']);
  $stmt = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
  $stmt->execute();
  header("Location: cart.php");
  exit;
}

// Process update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
  foreach ($_POST['quantity'] as $cart_id => $quantity) {
    $cart_id = clean_input($cart_id);
    $quantity = clean_input($quantity);

    if ($quantity <= 0) {
      // Remove item if quantity is 0 or less
      $stmt = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
      $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
    } else {
      // Update quantity
      $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
      $stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
    }
    $stmt->execute();
  }
  header("Location: cart.php");
  exit;
}

$cart_items = getCartItems($conn, $_SESSION['user_id']);
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['price'] * $item['quantity'];
}

$active = 'cart';
?>
<?php include 'components/header.php'; ?>

<!-- ======= Main =======-->
<main>

  <!-- ======= Keranjang =======-->
  <section class="hero__v6 section" id="cart">
    <div class="container">
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold">Keranjang Belanja</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <?php if (count($cart_items) > 0): ?>
            <form method="POST" action="cart.php">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Produk</th>
                          <th>Harga</th>
                          <th>Jumlah</th>
                          <th>Subtotal</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($cart_items as $item):
                          $type_class = 'type-' . $item['type'];
                          $subtotal = $item['price'] * $item['quantity'];
                          ?>
                          <tr>
                            <td>
                              <div class="d-flex align-items-center">
                                <img src="assets/images/<?php echo htmlspecialchars($item['image_url']); ?>"
                                  class="cart-item-img me-3" alt="<?php echo htmlspecialchars($item['type']); ?>">
                                <div>
                                  <span class="type-badge <?php echo $type_class; ?> mb-1">
                                    <?php echo ucfirst($item['type']); ?>
                                  </span>
                                  <h6 class="mb-0"><?php echo htmlspecialchars($item['weight_group']); ?></h6>
                                  <small><?php echo htmlspecialchars($item['description']); ?></small>
                                </div>
                              </div>
                            </td>
                            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td>
                              <input type="number" name="quantity[<?php echo $item['id']; ?>]"
                                class="form-control quantity-input" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            <td>
                              <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer text-end">
                  <button type="submit" name="update_cart" class="btn btn-outline-primary me-2">
                    <i class="fas fa-sync-alt me-1"></i> Update Keranjang
                  </button>
                </div>
              </div>
            </form>
          <?php else: ?>
            <div class="card shadow-sm">
              <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h4 class="mb-3">Keranjang Anda kosong</h4>
                <p class="text-muted mb-4">Silakan tambahkan paket qurban terlebih dahulu</p>
                <a href="paket.php" class="btn btn-primary">Lihat Paket Qurban</a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <?php if (count($cart_items) > 0): ?>
          <div class="col-lg-4">
            <div class="card shadow-sm summary-card">
              <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Belanja</h5>

                <div class="d-flex justify-content-between mb-2">
                  <span>Total Harga:</span>
                  <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold mb-4">
                  <span>Total:</span>
                  <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>

                <a href="checkout.php" class="btn btn-checkout w-100 py-2">
                  <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- End Keranjang -->
  <?php include 'components/footer.php'; ?>