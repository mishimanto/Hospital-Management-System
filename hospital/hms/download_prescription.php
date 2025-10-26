<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if(isset($_GET['id'])) {
    $appointmentId = intval($_GET['id']);
    
    // Verify the appointment belongs to the logged-in user
    $query = mysqli_query($con, "SELECT id FROM appointment WHERE id='$appointmentId' AND userId='".$_SESSION['id']."'");
    if(mysqli_num_rows($query) == 0) {
        die("Invalid appointment or access denied");
    }
    
    // Generate the PDF
    require('generate_pdf_prescription.php');
    
    $filepath = generatePrescriptionPDF($appointmentId, $con);
    
    if($filepath) {
        // Output the PDF for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        
        // Delete the file after download
        unlink($filepath);
        exit;
    } else {
        die("Failed to generate prescription");
    }
} else {
    die("Invalid request");
}
?>