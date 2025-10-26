<?php
include_once ('include/config.php');

if(isset($_POST['doctorId'])) 
{
    $doctorId = $_POST['doctorId'];
    $query = mysqli_query($con, "SELECT visiting_start_time, visiting_end_time FROM doctors WHERE id = '$doctorId'");
    $data = mysqli_fetch_assoc($query);
    
    header('Content-Type: application/json');
    echo json_encode([
        'start_time' => $data['visiting_start_time'] ?? null,
        'end_time' => $data['visiting_end_time'] ?? null
    ]);
    exit;
}
?>