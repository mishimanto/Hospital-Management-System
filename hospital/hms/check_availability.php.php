<?php
include('include/config.php');

if(isset($_POST['doctor']) && isset($_POST['date']) && isset($_POST['time'])) {
    $doctorId = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    $query = mysqli_query($con, "SELECT id FROM appointment WHERE doctorId = '$doctorId' AND appointmentDate = '$date' AND appointmentTime = '$time'");
    
    if(mysqli_num_rows($query) > 0) {
        echo "booked";
    } else {
        echo "available";
    }
}
?>