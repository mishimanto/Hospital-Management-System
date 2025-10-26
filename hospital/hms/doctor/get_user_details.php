<?php
session_start();
include('include/config.php');
header('Content-Type: application/json');

if (!isset($_POST['user_id'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$user_id = intval($_POST['user_id']);

$query = mysqli_query($con, "SELECT fullName, contactno, email, address, age FROM users WHERE id = $user_id");

if ($row = mysqli_fetch_assoc($query)) {
    echo json_encode([
        'name' => $row['fullName'],
        'contact' => $row['contactno'],
        'email' => $row['email'],
        'address' => $row['address'],
        'age' => $row['age']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
