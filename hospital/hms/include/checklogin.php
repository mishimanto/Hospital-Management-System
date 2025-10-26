<?php


function check_login() {
    // Timeout period in seconds (e.g., 60 minutes)
    $timeout_duration = 3600;

    // Check if user is logged in
    if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
        redirect_to_login();
    }

    // Check for last activity time
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Session expired due to inactivity
        session_unset();
        session_destroy();
        redirect_to_login();
    }

    // Update last activity time stamp
    $_SESSION['last_activity'] = time();
}

function redirect_to_login() {
    $host = $_SERVER['HTTP_HOST'];
    $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "user-login.php";
    header("Location: http://$host$uri/$extra");
    exit();
}
?>
