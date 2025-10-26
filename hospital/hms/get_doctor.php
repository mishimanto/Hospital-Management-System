<?php
include('include/config.php');

if(!empty($_POST["specilizationid"])) {
    $sql = mysqli_query($con, "SELECT doctorName, id FROM doctors WHERE specilization='".$_POST['specilizationid']."'");
    echo '<option selected="selected">Select Doctor</option>';
    while($row = mysqli_fetch_array($sql)) {
        echo '<option value="'.htmlentities($row['id']).'">'.htmlentities($row['doctorName']).'</option>';
    }
}

if(!empty($_POST["doctor"])) {
    $sql = mysqli_query($con, "SELECT docFees FROM doctors WHERE id='".$_POST['doctor']."'");
    while($row = mysqli_fetch_array($sql)) {
        echo '<option value="'.htmlentities($row['docFees']).'">'.htmlentities($row['docFees']).'</option>';
    }
}

if(!empty($_POST["visitingDoctorId"])) {
    $sql = mysqli_query($con, "SELECT visiting_start_time, visiting_end_time FROM doctors WHERE id='".$_POST['visitingDoctorId']."'");
    $data = mysqli_fetch_assoc($sql);
    header('Content-Type: application/json');
    echo json_encode([
        'start_time' => $data['visiting_start_time'] ?? null,
        'end_time'   => $data['visiting_end_time'] ?? null
    ]);
    exit;
}
?>
