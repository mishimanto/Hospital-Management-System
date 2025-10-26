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
        // Include the report generator (make sure the path is correct)
        require_once 'generate_report.php'; // Changed from generate_report.php
        
        // Fix: Use $order_id instead of undefined $orderId
        $filepath = generateDiagnosticReportPDF($order_id, $con); // Corrected variable name
        
        if(file_exists($filepath)) {
            // Set proper headers for PDF download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            ob_clean();
            flush();
            readfile($filepath);
            
            // Optional: Delete the file after download if you don't want to keep it
            // unlink($filepath);
            exit;
        }
    }
}

// If anything fails, redirect
header("Location: dashboard.php");
exit();
?>