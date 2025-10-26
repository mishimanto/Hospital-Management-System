<?php
session_start();
include('include/config.php');

header('Content-Type: application/json');

// Check if order_number is provided
if(!isset($_POST['order_number']) || empty($_POST['order_number'])) {
    echo json_encode(['error' => 'Order number not provided']);
    exit();
}

$order_number = mysqli_real_escape_string($con, $_POST['order_number']);

// Get order and user info
$orderQuery = mysqli_query($con, "SELECT t.*, u.* 
                                FROM test_orders t
                                JOIN users u ON t.user_id = u.id
                                WHERE t.order_number = '$order_number'
                                LIMIT 1");

if(!$orderQuery || mysqli_num_rows($orderQuery) === 0) {
    echo json_encode(['error' => 'Order not found']);
    exit();
}

$orderData = mysqli_fetch_assoc($orderQuery);

// Get tests for this order
$testsQuery = mysqli_query($con, "SELECT ot.*, dt.name 
                                FROM ordered_tests ot
                                JOIN diagnostic_tests dt ON ot.test_id = dt.id
                                WHERE ot.order_id = '".$orderData['id']."'");

if(!$testsQuery) {
    echo json_encode(['error' => 'Failed to fetch tests']);
    exit();
}

$tests = [];
while($test = mysqli_fetch_assoc($testsQuery)) {
    $tests[] = [
        'id' => $test['id'],
        'name' => $test['name'],
        'status' => $test['status'],
        'result' => $test['result'] ? nl2br($test['result']) : 'No result yet'
    ];
}

echo json_encode([
    'user' => [
        'fullName' => $orderData['fullName'],
        'gender' => $orderData['gender'],
        'address' => $orderData['address']
    ],
    'tests' => $tests
]);
?><?php
session_start();
include('include/config.php');

header('Content-Type: application/json');

// Check if order_number is provided
if(!isset($_POST['order_number']) || empty($_POST['order_number'])) {
    echo json_encode(['error' => 'Order number not provided']);
    exit();
}

$order_number = mysqli_real_escape_string($con, $_POST['order_number']);

// Get order and user info
$orderQuery = mysqli_query($con, "SELECT t.*, u.* 
                                FROM test_orders t
                                JOIN users u ON t.user_id = u.id
                                WHERE t.order_number = '$order_number'
                                LIMIT 1");

if(!$orderQuery || mysqli_num_rows($orderQuery) === 0) {
    echo json_encode(['error' => 'Order not found']);
    exit();
}

$orderData = mysqli_fetch_assoc($orderQuery);

// Get tests for this order
$testsQuery = mysqli_query($con, "SELECT ot.*, dt.name 
                                FROM ordered_tests ot
                                JOIN diagnostic_tests dt ON ot.test_id = dt.id
                                WHERE ot.order_id = '".$orderData['id']."'");

if(!$testsQuery) {
    echo json_encode(['error' => 'Failed to fetch tests']);
    exit();
}

$tests = [];
while($test = mysqli_fetch_assoc($testsQuery)) {
    $tests[] = [
        'id' => $test['id'],
        'name' => $test['name'],
        'status' => $test['status'],
        'result' => $test['result'] ? nl2br($test['result']) : 'No result yet'
    ];
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