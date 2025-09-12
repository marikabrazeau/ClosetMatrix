<?php
/**
 * Session Check API
 * Returns JSON indicating if user is logged in
 */

// Start session
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Return JSON response
echo json_encode([
    'logged_in' => $logged_in,
    'user_id' => $logged_in ? $_SESSION['user_id'] : null,
    'username' => $logged_in ? ($_SESSION['username'] ?? null) : null
]);
?>