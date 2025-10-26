<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Fetch user's bed bookings
$bookings = $con->query("
    SELECT ba.*, b.bed_number, w.ward_name, p.PatientName, p.PatientContno
    FROM bed_assignments ba
    JOIN beds b ON ba.bed_id = b.bed_id
    JOIN wards w ON b.ward_id = w.ward_id
    JOIN patient p ON ba.patient_id = p.ID
    WHERE ba.user_id = {$_SESSION['id']}
    ORDER BY ba.admission_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bed Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('include/header_logins_page.php'); ?>
    
    <div class="container mt-4">
        <h2 class="mb-4">My Bed Bookings</h2>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <?php if($bookings->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Patient</th>
                                    <th>Ward</th>
                                    <th>Bed</th>
                                    <th>Admission Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($booking = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $booking['id'] ?></td>
                                    <td>
                                        <?= htmlspecialchars($booking['PatientName']) ?><br>
                                        <small><?= htmlspecialchars($booking['PatientContno']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($booking['ward_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['bed_number']) ?></td>
                                    <td><?= date('d M Y h:i A', strtotime($booking['admission_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $booking['status'] == 'Admitted' ? 'success' : 
                                            ($booking['status'] == 'Discharged' ? 'secondary' : 'info')
                                        ?>">
                                            <?= $booking['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">You haven't booked any beds yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('include/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>