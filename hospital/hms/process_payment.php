<?php
session_start();
require_once('include/config.php');
require_once('include/checklogin.php');
check_login();

// Get user's discharged but unpaid bed assignments
$user_id = $_SESSION['id'];
$assignments = $con->query("
    SELECT ba.*, b.bed_number, w.ward_name, p.PatientName
    FROM bed_assignments ba
    JOIN beds b ON ba.bed_id = b.bed_id
    JOIN wards w ON b.ward_id = w.ward_id
    JOIN patient p ON ba.patient_id = p.id
    WHERE ba.user_id = $user_id 
    AND ba.status = 'Discharged'
    AND ba.payment_status = 'Pending'
");

// Handle payment
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make_payment'])){
    $assignment_id = intval($_POST['assignment_id']);
    
    // Get assignment details
    $assign = $con->query("
        SELECT total_charge FROM bed_assignments 
        WHERE id = $assignment_id AND user_id = $user_id
    ")->fetch_assoc();
    
    if(!$assign) {
        $_SESSION['error'] = "Invalid bed assignment";
        header("Location: process_payment.php");
        exit();
    }
    
    // Check wallet balance
    $user = $con->query("SELECT wallet_balance FROM users WHERE id = $user_id")->fetch_assoc();
    if($user['wallet_balance'] < $assign['total_charge']) {
        $_SESSION['error'] = "Insufficient wallet balance";
        header("Location: process_payment.php");
        exit();
    }
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Deduct from wallet
        $con->query("
            UPDATE users 
            SET wallet_balance = wallet_balance - {$assign['total_charge']}
            WHERE id = $user_id
        ");
        
        // Update payment status
        $con->query("
            UPDATE bed_assignments 
            SET payment_status = 'Paid'
            WHERE id = $assignment_id
        ");
        
        $con->commit();
        $_SESSION['success'] = "Payment successful!";
        header("Location: my_bed_bookings.php");
        exit();
    } catch(Exception $e) {
        $con->rollback();
        $_SESSION['error'] = "Payment failed: ".$e->getMessage();
        header("Location: process_payment.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Process Payment</title>
    <!-- Include your CSS/JS files -->
</head>
<body>
    <?php include('include/header_logins_page.php'); ?>
    
    <div class="container py-5">
        <h2 class="mb-4">Process Payment</h2>
        
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
                                <th>Discharge Date</th>
                                <th>Total Charge</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assign = $assignments->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($assign['PatientName']) ?></td>
                                <td><?= htmlspecialchars($assign['ward_name']) ?></td>
                                <td><?= htmlspecialchars($assign['bed_number']) ?></td>
                                <td><?= date('M j, Y', strtotime($assign['admission_date'])) ?></td>
                                <td><?= date('M j, Y', strtotime($assign['discharge_date'])) ?></td>
                                <td>৳<?= number_format($assign['total_charge'], 2) ?></td>
                                <td>
                                    <!-- <a href="generate_invoice.php?id=<?= $assign['id'] ?>" 
                                       class="btn btn-info btn-sm" target="_blank">
                                        View Invoice
                                    </a> -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="assignment_id" value="<?= $assign['id'] ?>">
                                        <button type="submit" name="make_payment" class="btn btn-success btn-sm">
                                            Pay Now
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
            <div class="alert alert-info">You have no pending payments.</div>
        <?php endif; ?>
    </div>
    
    <?php include('include/footer.php'); ?>
</body>
</html>