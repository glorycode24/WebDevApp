<?php
session_start();

// Destroy the session variables
$_SESSION = array();

// If you want to kill the session, also delete the session cookie.
// This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Redirect to the admin login page
header('Location: admin_login.php');
exit;
?>
