<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

header('Content-Type: application/json');

if(isset($_POST['doctor']) && isset($_POST['date'])) {
    $doctorId = $_POST['doctor'];
    $appDate = $_POST['date'];
    $userId = $_SESSION['id'];
    
    $query = mysqli_query($con, "SELECT id, appointmentDate FROM appointment WHERE 
                                doctorId = '$doctorId' AND 
                                userId = '$userId' AND 
                                appointmentDate = '$appDate' AND
                                (userStatus = 1 OR doctorStatus = 1)");
    
    if(mysqli_num_rows($query) > 0) {
        $appointment = mysqli_fetch_assoc($query);
        echo json_encode([
            'exists' => true,
            'date' => date('F j, Y', strtotime($appointment['appointmentDate']))
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>