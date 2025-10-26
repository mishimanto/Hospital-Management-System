<?php
session_start();
require_once('include/config.php');
require_once('include/checklogin.php');
check_login();

// Get user's active bed assignments
$user_id = $_SESSION['id'];
$assignments = $con->query("
    SELECT ba.*, b.bed_number, w.ward_name, p.PatientName
    FROM bed_assignments ba
    JOIN beds b ON ba.bed_id = b.bed_id
    JOIN wards w ON b.ward_id = w.ward_id
    JOIN patient p ON ba.patient_id = p.id
    WHERE ba.user_id = $user_id AND ba.status = 'Admitted'
");

// Handle discharge request
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_discharge'])){
    $assignment_id = intval($_POST['assignment_id']);
    
    // Check if already has a pending request
    $check = $con->query("SELECT id FROM discharge_requests 
                         WHERE assignment_id = $assignment_id AND status = 'Pending'");
    if($check->num_rows > 0) {
        $_SESSION['error'] = "You already have a pending discharge request for this bed.";
        header("Location: request_discharge.php");
        exit();
    }
    
    // Create discharge request
    $con->query("INSERT INTO discharge_requests (assignment_id) VALUES ($assignment_id)");
    
    $_SESSION['success'] = "Discharge request submitted. Please wait for admin approval.";
    header("Location: my_bed_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Discharge</title>
    <!-- Include your CSS/JS files -->
</head>
<body>
    <?php include('include/header_logins_page.php'); ?>
    
    <div class="container py-5">
        <h2 class="mb-4">Request Discharge</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if($assignments->num_rows > 0): ?>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Ward</th>
                                <th>Bed</th>
                                <th>Admission Date</th>
                                <th>Price/Day</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assign = $assignments->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($assign['PatientName']) ?></td>
                                <td><?= htmlspecialchars($assign['ward_name']) ?></td>
                                <td><?= htmlspecialchars($assign['bed_number']) ?></td>
                                <td><?= date('M j, Y', strtotime($assign['admission_date'])) ?></td>
                                <td>৳<?= number_format($assign['price_per_day'], 2) ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="assignment_id" value="<?= $assign['id'] ?>">
                                        <button type="submit" name="request_discharge" class="btn btn-danger">
                                            Request Discharge
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">You have no active bed assignments.</div>
        <?php endif; ?>
    </div>
    
    <?php include('include/footer.php'); ?>
</body>
</html>