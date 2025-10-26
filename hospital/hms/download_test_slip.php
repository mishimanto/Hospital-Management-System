<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if(isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    $user_id = $_SESSION['id'];
    
    // Verify user owns this order
    $check_query = mysqli_query($con, "SELECT id FROM test_orders WHERE id = '$order_id' AND user_id = '$user_id'");
    
    if(mysqli_num_rows($check_query) > 0) {
        require_once 'generate_test_pdf.php'; // Inside this, fpdf.php should be included
        
        $filepath = generateTestPDF($order_id, $con);
        
        if(file_exists($filepath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.basename($filepath).'"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    }
}

// If anything fails, redirect
header("Location: dashboard.php");
exit();
?>
