<?php
include('include/config.php');
session_start();

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

// Check if order_id is provided
if(!isset($_GET['order_id'])) {
    header("Location: my_tests.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['id'];

// Fetch order details
$order_query = "SELECT * FROM test_orders 
                WHERE id = $order_id AND user_id = $user_id AND status = 'Pending'";
$order_result = mysqli_query($con, $order_query);
$order = mysqli_fetch_assoc($order_result);

if(!$order) {
    $_SESSION['error'] = "Order not found or cannot be cancelled";
    header("Location: my_tests.php");
    exit();
}

// Start transaction
mysqli_begin_transaction($con);

try {
    // Update order status
    $update_order = "UPDATE test_orders SET status = 'Cancelled', updated_at = NOW() WHERE id = $order_id";
    mysqli_query($con, $update_order);
    
    // Update ordered tests status
    $update_tests = "UPDATE ordered_tests SET status = 'Cancelled' WHERE order_id = $order_id";
    mysqli_query($con, $update_tests);
    
    // Refund to wallet if payment was made
    if($order['payment_status'] == 'Paid') {
        $refund_amount = $order['total_amount'];
        $update_wallet = "UPDATE users SET wallet_balance = wallet_balance + $refund_amount WHERE id = $user_id";
        mysqli_query($con, $update_wallet);
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    $_SESSION['success'] = "Order #{$order['order_number']} has been cancelled successfully" . 
                          ($order['payment_status'] == 'Paid' ? " and amount refunded to your wallet." : ".");
} catch (Exception $e) {
    mysqli_rollback($con);
    $_SESSION['error'] = "Error cancelling order: " . $e->getMessage();
}

header("Location: my_tests.php");
exit();
?>