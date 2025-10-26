<?php
include('include/config.php');

if (isset($_POST['spec'])) {
    $spec = $_POST['spec'];
    $stmt = $con->prepare("SELECT * FROM doctors WHERE specilization = ?");
    $stmt->bind_param("s", $spec);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Doctor</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['doctorName'].'</option>';
    }
    $stmt->close();
}
?>
