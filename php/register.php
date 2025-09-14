<?php
/**
 * User Registration Handler
 */

session_start();
require_once '../config.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.html?error=Invalid request method');
    exit();
}

// Get form data
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$agreeTerms = isset($_POST['agree_terms']);
$newsletter = isset($_POST['newsletter']);

// Validation
$errors = [];

// Name validation
if (strlen($firstName) < 2) {
    $errors[] = 'First name must be at least 2 characters';
}

if (strlen($lastName) < 2) {
    $errors[] = 'Last name must be at least 2 characters';
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}

// Password validation
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters';
}

if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = 'Password must contain at least one uppercase letter';
}

if (!preg_match('/[a-z]/', $password)) {
    $errors[] = 'Password must contain at least one lowercase letter';
}

if (!preg_match('/\d/', $password)) {
    $errors[] = 'Password must contain at least one number';
}

if (!preg_match('/[@$!%*?&]/', $password)) {
    $errors[] = 'Password must contain at least one special character (@$!%*?&)';
}

// Confirm password
if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match';
}

// Terms agreement
if (!$agreeTerms) {
    $errors[] = 'You must agree to the Terms of Service and Privacy Policy';
}

// If there are validation errors, redirect back
if (!empty($errors)) {
    $errorString = implode(', ', $errors);
    header('Location: ../register.html?error=' . urlencode($errorString));
    exit();
}

try {
    // Get database connection
    $db = Database::getInstance()->getConnection();
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        header('Location: ../register.html?error=' . urlencode('Email address already registered'));
        exit();
    }
    
    // Create username from first name and last name
    $baseUsername = strtolower($firstName . $lastName);
    $username = $baseUsername;
    $counter = 1;
    
    // Check for unique username
    while (true) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if (!$stmt->fetch()) {
            break; // Username is unique
        }
        
        $username = $baseUsername . $counter;
        $counter++;
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $db->prepare("
        INSERT INTO users (username, email, password_hash, first_name, last_name, newsletter_subscribed, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $username,
        $email,
        $passwordHash,
        $firstName,
        $lastName,
        $newsletter ? 1 : 0
    ]);
    
    // Get the new user ID
    $userId = $db->lastInsertId();
    
    // Auto-login the user
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    
    // Redirect to success page or dashboard
    header('Location: ../index.php?success=' . urlencode('Account created successfully! Welcome to Closet Matrix.'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Registration error: ' . $e->getMessage());
    
    header('Location: ../register.html?error=' . urlencode('Registration failed. Please try again.'));
    exit();
}
?>