<?php
require_once 'config.php';
$page_title = 'My Orders';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Verify order belongs to user and can be cancelled
    $check_query = "SELECT * FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'processing')";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Update order status to cancelled
        $cancel_query = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $cancel_query);
        mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = 'Order #' . $order_id . ' has been cancelled successfully.';
        } else {
            $message = 'Failed to cancel order. Please try again.';
        }
    } else {
        $message = 'This order cannot be cancelled.';
    }
}

// Get user's orders
$orders_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $orders_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$orders_result = mysqli_stmt_get_result($stmt);
$orders = mysqli_fetch_all($orders_result, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<div class="container">
    <h2>My Orders</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (count($orders) > 0): ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <?php
                // Get order items
                $items_query = "SELECT oi.*, p.name, p.image 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.id 
                               WHERE oi.order_id = ?";
                $stmt = mysqli_prepare($conn, $items_query);
                mysqli_stmt_bind_param($stmt, "i", $order['id']);
                mysqli_stmt_execute($stmt);
                $items_result = mysqli_stmt_get_result($stmt);
                $items = mysqli_fetch_all($items_result, MYSQLI_ASSOC);
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <p class="order-date">
                                <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?>
                            </p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($items as $item): ?>
                            <div class="order-item">
                                <div class="order-item-image">
                                    <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         onerror="this.src='images/placeholder.jpg'">
                                </div>
                                <div class="order-item-details">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                                    <p class="item-price">₱<?php echo number_format($item['price'], 2); ?> each</p>
                                </div>
                                <div class="order-item-total">
                                    ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-details">
                            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact_number']); ?></p>
                            <?php if (!empty($order['notes'])): ?>
                                <p><strong>Notes:</strong> <?php echo htmlspecialchars($order['notes']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="order-total">
                            <h3>Total: ₱<?php echo number_format($order['total_amount'], 2); ?></h3>
                            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                                <form method="POST" action="orders.php" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" name="cancel_order" class="btn btn-danger btn-small" style="margin-top: 1rem;">
                                        Cancel Order
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-orders">
            <p>You haven't placed any orders yet</p>
            <a href="menu.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
