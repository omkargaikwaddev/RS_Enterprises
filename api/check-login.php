<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    // Store the current URL for redirect after login
    $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'] ?? 'index.php';
}

echo json_encode([
    'logged_in' => isset($_SESSION['user_id']),
    'user_id' => $_SESSION['user_id'] ?? null
]);
?>
