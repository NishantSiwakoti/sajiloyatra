<?php
session_start(); 

// Clear all session variables
$_SESSION = array();

// If session cookies are being used, destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 60*60,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Unset a specific session variable named 'login'
unset($_SESSION['login']);

// Destroy the session
session_destroy();

// Redirect the user to the index.php page
header("location:index.php"); 
?>
