<?php
require_once 'config.php';
$page_title = 'Register';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = 'Username or email already exists';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users (username, email, password, full_name, phone, address) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $hashed_password, $full_name, $phone, $address);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Create an Account</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php" class="auth-form">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required 
                       value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        
        <p class="form-footer">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
