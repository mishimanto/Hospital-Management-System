<?php
// process_discharge_request.php
require_once('include/config.php');

session_start();

// Set JSON header
header('Content-Type: application/json');

// Validate session
if (!isset($_SESSION['id']) || empty($_SESSION['id']) || $_SESSION['role'] != 'emergency_doctor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Check database connection
if (!$con) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['assignment_id']) || !is_numeric($_POST['assignment_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid assignment ID']);
        exit();
    }
    
    $assignmentId = (int)$_POST['assignment_id'];
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    $doctorId = $_SESSION['id']; // Using consistent session variable

    // Check if assignment exists and is active
    $checkSql = "SELECT * FROM bed_assignments 
                 WHERE id = ? AND status = 'Admitted'";
    $stmt = mysqli_prepare($con, $checkSql);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database prepare error']);
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $assignmentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid bed assignment or already discharged']);
        exit();
    }
    
    // Insert discharge request
    $insertSql = "INSERT INTO discharge_requests 
                  (assignment_id, request_date, status, processed_by, processed_at)
                  VALUES (?, NOW(), 'Pending', NULL, NULL)";
    
    $stmt = mysqli_prepare($con, $insertSql);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database prepare error']);
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $assignmentId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Optionally store notes if needed
        if (!empty($notes)) {
            // You would need to implement this based on your database structure
            // $noteSql = "INSERT INTO patient_notes...";
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Discharge request submitted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit discharge request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}