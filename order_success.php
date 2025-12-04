<?php
require_once 'config.php';
$page_title = 'Order Confirmed';

// Check if user is logged in and has order success flag
if (!isLoggedIn() || !isset($_SESSION['order_success'])) {
    redirect('index.php');
}

$order_id = $_SESSION['order_id'] ?? 0;

// Clear the success flag
unset($_SESSION['order_success']);
unset($_SESSION['order_id']);

include 'includes/header.php';
?>

<div class="container">
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2>Order Placed Successfully!</h2>
        <p class="success-message">Thank you for your order. Your order has been received and is being processed.</p>
        
        <div class="order-confirmation">
            <p><strong>Order Number:</strong> #<?php echo $order_id; ?></p>
            <p>You will receive a confirmation shortly.</p>
        </div>
        
        <div class="success-actions">
            <a href="orders.php" class="btn btn-primary">View My Orders</a>
            <a href="menu.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
