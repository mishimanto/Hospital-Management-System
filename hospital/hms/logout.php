<?php
session_start();

// Destroy session
$_SESSION = array();
session_unset();
session_destroy();

// Prevent back button cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page
header("Location: user-login.php");
exit();
?>
