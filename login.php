<?php
require_once 'config.php';
$page_title = 'Login';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        // Check credentials
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                redirect('index.php');
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Login to Your Account</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        
        <p class="form-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
