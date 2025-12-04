<?php
require_once 'config.php';
$page_title = 'Shopping Cart';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        $cart_id = (int)$_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity > 0) {
            $update_query = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_id, $user_id);
            mysqli_stmt_execute($stmt);
            $message = 'Cart updated!';
        }
    } elseif (isset($_POST['remove_item'])) {
        $cart_id = (int)$_POST['cart_id'];
        
        $delete_query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
        $message = 'Item removed from cart!';
    }
}

// Get cart items
$cart_query = "SELECT c.*, p.name, p.description, p.price, p.image 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ? 
               ORDER BY c.added_at DESC";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_result = mysqli_stmt_get_result($stmt);
$cart_items = mysqli_fetch_all($cart_result, MYSQLI_ASSOC);

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Shopping Cart</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (count($cart_items) > 0): ?>
        <div class="cart-container">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 onerror="this.src='images/placeholder.jpg'">
                        </div>
                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <div class="cart-item-actions">
                            <form method="POST" action="cart.php" class="cart-form">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="99" class="quantity-input">
                                <button type="submit" name="update_cart" class="btn btn-small">Update</button>
                                <button type="submit" name="remove_item" class="btn btn-small btn-danger">Remove</button>
                            </form>
                            <p class="subtotal">Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Delivery Fee:</span>
                    <span>$5.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total + 5, 2); ?></span>
                </div>
                <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                <a href="menu.php" class="btn btn-secondary btn-block">Continue Shopping</a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <p>Your cart is empty</p>
            <a href="menu.php" class="btn btn-primary">Browse Menu</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
