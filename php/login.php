<?php
/**
 * User Login Handler
 */

session_start();
require_once '../config.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html?error=Invalid request method');
    exit();
}

// Get form data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$rememberMe = isset($_POST['remember_me']);

// Basic validation
if (empty($email) || empty($password)) {
    header('Location: ../login.html?error=' . urlencode('Please fill in all fields'));
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../login.html?error=' . urlencode('Invalid email format'));
    exit();
}

try {
    // Get database connection
    $db = Database::getInstance()->getConnection();
    
    // Check for user
    $stmt = $db->prepare("SELECT id, username, email, password_hash, first_name, last_name, is_active FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: ../login.html?error=' . urlencode('Invalid email or password'));
        exit();
    }
    
    // Check if account is active
    if (!$user['is_active']) {
        header('Location: ../login.html?error=' . urlencode('Account is deactivated'));
        exit();
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        header('Location: ../login.html?error=' . urlencode('Invalid email or password'));
        exit();
    }
    
    // Login successful - set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    
    // Update last login timestamp
    $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Set remember me cookie if requested (30 days)
    if ($rememberMe) {
        setcookie('remember_user', $user['email'], time() + (30 * 24 * 60 * 60), '/');
    }
    
    // Redirect to main application
    $redirectUrl = $_SESSION['redirect_after_login'] ?? '../index.php';
    unset($_SESSION['redirect_after_login']); // Clear redirect URL
    
    header('Location: ' . $redirectUrl . '?success=' . urlencode('Welcome back, ' . $user['first_name'] . '!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Login error: ' . $e->getMessage());
    
    header('Location: ../login.html?error=' . urlencode('Login failed. Please try again.'));
    exit();
}
?>