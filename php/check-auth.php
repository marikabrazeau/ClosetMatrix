<?php
/**
 * Authentication Check
 * Redirects to login if user is not authenticated
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Not logged in, redirect to login page
    header('Location: login.html');
    exit();
}

// If we get here, user is authenticated
// You can optionally load user data here
?>