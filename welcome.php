<?php
session_start();

// Check if the user has just signed up
if (!isset($_SESSION['just_signed_up']) || $_SESSION['just_signed_up'] !== true) {
    header("Location: index.php"); // Redirect to home if accessed directly
    exit();
}

// Unset the flag after forwarding
unset($_SESSION['just_signed_up']);

// Redirect to the dashboard (or any page)
header("Location: dashboard.php"); 
exit();
?>
