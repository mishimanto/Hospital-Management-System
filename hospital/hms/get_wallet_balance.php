<?php
session_start();
require_once('include/config.php');
require_once('include/checklogin.php');
check_login();

$user_id = $_SESSION['id'];
$user = $con->query("SELECT wallet_balance FROM users WHERE id = $user_id")->fetch_assoc();

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'balance' => $user['wallet_balance']
]);
exit();
?>