<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'south_cafe');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to get current user
function getCurrentUser() {
    global $conn;
    if (!isLoggedIn()) {
        return null;
    }
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
