<?php
session_start();
include('include/config.php');

header('Content-Type: application/json');

if (!isset($_POST['test_id']) || !isset($_POST['result'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

if (empty($_POST['result'])) {
    echo json_encode(['status' => 'error', 'message' => 'Result cannot be empty']);
    exit();
}

$test_id = mysqli_real_escape_string($con, $_POST['test_id']);
$result = mysqli_real_escape_string($con, $_POST['result']);

$update_query = "UPDATE ordered_tests 
                SET result = '$result',
                    status = 'Completed',
                    completed_at = NOW()
                WHERE id = '$test_id'";

if (mysqli_query($con, $update_query)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($con)]);
}
?>