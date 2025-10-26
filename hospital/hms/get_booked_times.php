<?php
include('include/config.php');

if (isset($_POST['doctorId']) && isset($_POST['date'])) {
    $doctorId = $_POST['doctorId'];
    $date = $_POST['date'];

    $result = mysqli_query($con, "SELECT appointmentTime FROM appointment WHERE doctorId='$doctorId' AND appointmentDate='$date' AND userStatus=1 AND doctorStatus=1");

    $bookedTimes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = $row['appointmentTime'];
    }
    echo json_encode($bookedTimes);
}
?>
