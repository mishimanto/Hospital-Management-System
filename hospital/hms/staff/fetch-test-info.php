<?php
session_start();
include('include/config.php');

header('Content-Type: application/json');

if(!isset($_POST['order_number'])) {
    echo json_encode(['error' => 'Order number not provided']);
    exit();
}

$order_number = $_POST['order_number'];

// Get order and user info
$orderQuery = mysqli_query($con, "SELECT t.*, u.* 
                                FROM test_orders t
                                JOIN users u ON t.user_id = u.id
                                WHERE t.order_number = '$order_number'");
$orderData = mysqli_fetch_assoc($orderQuery);

if(!$orderData) {
    echo json_encode(['error' => 'Order not found']);
    exit();
}

// Get tests for this order
$testsQuery = mysqli_query($con, "SELECT ot.*, dt.name 
                                FROM ordered_tests ot
                                JOIN diagnostic_tests dt ON ot.test_id = dt.id
                                WHERE ot.order_id = (SELECT id FROM test_orders WHERE order_number = '$order_number')");

$tests = [];
while($test = mysqli_fetch_assoc($testsQuery)) {
    $tests[] = $test;
}

echo json_encode([
    'user' => [
        'fullName' => $orderData['fullName'],
        'gender' => $orderData['gender'],
        'address' => $orderData['address']
    ],
    'tests' => $tests
]);
?>