<?php
// Start session (optional if you want session-based login later)
session_start();

// Get form values
$username = $_POST['n1'] ?? '';
$password = $_POST['n2'] ?? '';

// Admin credentials
$correct_username = "kloth";
$correct_password = "kloth";

// Check credentials
if ($username === $correct_username && $password === $correct_password) {
    
    $_SESSION['admin_logged_in'] = true;

    
    header("Location: admin_panal.html");
    exit();
} else {
    
    header("Location: admin_login.html?error=1");
    exit();
}
?>
