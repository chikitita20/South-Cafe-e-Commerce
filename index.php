<?php
require_once 'config.php';
$page_title = 'Home';

// Get featured products
$query = "SELECT * FROM products WHERE available = 1 ORDER BY created_at DESC LIMIT 6";
$result = mysqli_query($conn, $query);
$featured_products = mysqli_fetch_all($result, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<div class="hero">
    <div class="container">
        <h1>Welcome to South Cafe</h1>
        <p>Your favorite coffee shop for quality beverages and delicious food</p>
        <a href="menu.php" class="btn btn-primary">Order Now</a>
    </div>
</div>

<div class="container">
    <section class="featured-section">
        <h2>Featured Items</h2>
        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='images/placeholder.jpg'">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-footer">
                            <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
                            <a href="menu.php" class="btn btn-small btn-primary">View Menu</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
