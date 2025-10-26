<?php
include('include/config.php');

if(isset($_POST['appointment_number'])){
    $aptNumber = $_POST['appointment_number'];

    $result = mysqli_query($con, "
        SELECT u.fullName, u.email,  u.gender, u.address
        FROM appointment a
        JOIN users u ON a.userId = u.id
        WHERE a.appointment_number = '$aptNumber'
    ");

    // Debug query error if any
    if (!$result) {
        echo "Query Error: " . mysqli_error($con);
        exit();
    }

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        echo "
        <h5>Patient Information:</h5>
        <table class='table table-bordered'>
            <tr><th>Name</th><td>{$row['fullName']}</td></tr>
            <tr><th>Email</th><td>{$row['email']}</td></tr>
            
            <tr><th>Gender</th><td>{$row['gender']}</td></tr>
            <tr><th>Address</th><td>{$row['address']}</td></tr>
            
        </table>
        ";
    }else{
        echo "<div class='alert alert-danger'>No patient info found for this appointment.</div>";
    }
}
?>
