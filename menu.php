<?php
require_once 'config.php';
$page_title = 'Menu';

$message = '';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    // Check if item already in cart
    $check_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Update quantity
        $update_query = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        $message = 'Cart updated successfully!';
    } else {
        // Insert new item
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $product_id, $quantity);
        mysqli_stmt_execute($stmt);
        $message = 'Item added to cart!';
    }
}

// Get all categories
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

// Get filter
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get products
if ($category_filter > 0) {
    $products_query = "SELECT p.*, c.name as category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.available = 1 AND p.category_id = ? 
                       ORDER BY p.name";
    $stmt = mysqli_prepare($conn, $products_query);
    mysqli_stmt_bind_param($stmt, "i", $category_filter);
    mysqli_stmt_execute($stmt);
    $products_result = mysqli_stmt_get_result($stmt);
} else {
    $products_query = "SELECT p.*, c.name as category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.available = 1 
                       ORDER BY c.name, p.name";
    $products_result = mysqli_query($conn, $products_query);
}

$products = mysqli_fetch_all($products_result, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<div class="container">
    <h2>Our Menu</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="filter-section">
        <a href="menu.php" class="filter-btn <?php echo $category_filter == 0 ? 'active' : ''; ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="menu.php?category=<?php echo $category['id']; ?>" 
               class="filter-btn <?php echo $category_filter == $category['id'] ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="product-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='images/placeholder.jpg'">
                        <span class="category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-footer">
                            <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
                            <form method="POST" action="menu.php" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="99" class="quantity-input">
                                <button type="submit" name="add_to_cart" class="btn btn-small">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">No products found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
