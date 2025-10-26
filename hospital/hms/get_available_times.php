<?php
include('include/config.php');

if(isset($_POST['doctor']) && isset($_POST['date'])) {
    $doctorId = $_POST['doctor'];
    $date = $_POST['date'];
    
    $query = mysqli_query($con, "SELECT appointmentTime FROM appointment WHERE doctorId = '$doctorId' AND appointmentDate = '$date'");
    
    $bookedTimes = array();
    while($row = mysqli_fetch_assoc($query)) {
        $bookedTimes[] = $row['appointmentTime'];
    }
    
    echo json_encode($bookedTimes);
}
?>