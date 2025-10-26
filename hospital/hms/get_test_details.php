<?php
include('../include/config.php');
header('Content-Type: application/json');

// Check if test_id is provided
if(!isset($_GET['test_id'])) {
    echo json_encode(['error' => 'Test ID is required']);
    exit();
}

$test_id = intval($_GET['test_id']);

// Fetch test details
$query = "SELECT dt.*, tc.name as category_name 
          FROM diagnostic_tests dt
          JOIN test_categories tc ON dt.category_id = tc.id
          WHERE dt.id = $test_id";
$result = mysqli_query($con, $query);
$test = mysqli_fetch_assoc($result);

if(!$test) {
    echo json_encode(['error' => 'Test not found']);
    exit();
}

echo json_encode([
    'id' => $test['id'],
    'name' => $test['name'],
    'category' => $test['category_name'],
    'description' => $test['description'],
    'price' => $test['price'],
    'preparation' => $test['preparation'],
    'normal_range' => $test['normal_range']
]);
?>