<?php
// Handles user logout
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the home page
header('Location: bootstrap.php');
exit;
?>
