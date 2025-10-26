<?php
session_start();
require_once('include/config.php');

if(!isset($_SESSION['id']) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$assignment_id = intval($_GET['id']);

// Update payment status to Requested
$con->query("UPDATE bed_assignments SET payment_status = 'Requested' WHERE id = $assignment_id");

echo json_encode(['success' => true, 'message' => 'Payment request sent']);