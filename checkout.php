<?php
require_once 'config.php';
$page_title = 'Checkout';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get user details
$user = getCurrentUser();

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_result = mysqli_stmt_get_result($stmt);
$cart_items = mysqli_fetch_all($cart_result, MYSQLI_ASSOC);

// Calculate total
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = 5.00;
$total = $subtotal + $delivery_fee;

// Check if cart is empty
if (count($cart_items) == 0) {
    redirect('cart.php');
}

// Process checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    if (empty($delivery_address) || empty($contact_number)) {
        $error = 'Please provide delivery address and contact number';
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Insert order
            $order_query = "INSERT INTO orders (user_id, total_amount, delivery_address, contact_number, notes, status) 
                           VALUES (?, ?, ?, ?, ?, 'pending')";
            $stmt = mysqli_prepare($conn, $order_query);
            mysqli_stmt_bind_param($stmt, "idsss", $user_id, $total, $delivery_address, $contact_number, $notes);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);
            
            // Insert order items
            foreach ($cart_items as $item) {
                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $item_query);
                mysqli_stmt_bind_param($stmt, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($stmt);
            }
            
            // Clear cart
            $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $clear_cart_query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            // Commit transaction
            mysqli_commit($conn);
            
            // Redirect to success page
            $_SESSION['order_success'] = true;
            $_SESSION['order_id'] = $order_id;
            redirect('order_success.php');
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = 'Order placement failed. Please try again.';
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Checkout</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <div class="checkout-container">
        <div class="checkout-form">
            <h3>Delivery Information</h3>
            <form method="POST" action="checkout.php">
                <div class="form-group">
                    <label for="delivery_address">Delivery Address *</label>
                    <textarea id="delivery_address" name="delivery_address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="contact_number">Contact Number *</label>
                    <input type="tel" id="contact_number" name="contact_number" required 
                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="notes">Order Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Any special instructions..."></textarea>
                </div>
                
                <h3>Order Summary</h3>
                <div class="order-items-preview">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="order-item-preview">
                            <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                            <span>PHP<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="checkout-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>PHP<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span>PHP<?php echo number_format($delivery_fee, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>PHP<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
                
                <button type="submit" name="place_order" class="btn btn-primary btn-block">Place Order</button>
                <a href="cart.php" class="btn btn-secondary btn-block">Back to Cart</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
