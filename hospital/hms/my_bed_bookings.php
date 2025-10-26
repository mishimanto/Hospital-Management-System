<?php
session_start();
require_once('include/config.php');
require_once('include/checklogin.php');
check_login();

$user_id = $_SESSION['id'];

// Handle discharge request
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_discharge'])){
    $assignment_id = intval($_POST['assignment_id']);
    
    // Check if already has a pending request
    $stmt = $con->prepare("SELECT id FROM discharge_requests WHERE assignment_id = ? AND status = 'Pending'");
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if($check->num_rows > 0) {
        $_SESSION['error'] = "You already have a pending discharge request for this bed.";
        header("Location: my_bed_bookings.php");
        exit();
    }
    
    // Create discharge request
    $stmt = $con->prepare("INSERT INTO discharge_requests (assignment_id, requested_by) VALUES (?, ?)");
    $stmt->bind_param("ii", $assignment_id, $user_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Discharge request submitted. Please wait for admin approval.";
    header("Location: my_bed_bookings.php");
    exit();
}

// Handle payment request
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make_payment'])){
    $assignment_id = intval($_POST['assignment_id']);
    $discharge_request_id = isset($_POST['discharge_request_id']) ? intval($_POST['discharge_request_id']) : null;
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Get assignment details
        $stmt = $con->prepare("
            SELECT total_charge, price_per_day, 
                   DATEDIFF(IFNULL(discharge_date, NOW()), admission_date) + 1 as days_stayed
            FROM bed_assignments 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $assignment_id, $user_id);
        $stmt->execute();
        $assign = $stmt->get_result()->fetch_assoc();
        
        if(!$assign) {
            throw new Exception("Invalid bed assignment");
        }
        
        // Calculate total charge if not already set
        $total_charge = $assign['total_charge'];
        if($total_charge == 0) {
            $total_charge = $assign['price_per_day'] * $assign['days_stayed'];
        }
        
        // Check wallet balance
        $stmt = $con->prepare("SELECT wallet_balance FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if($user['wallet_balance'] < $total_charge) {
            throw new Exception("Insufficient wallet balance");
        }
        
        // Deduct from wallet
        $stmt = $con->prepare("
            UPDATE users 
            SET wallet_balance = wallet_balance - ?
            WHERE id = ?
        ");
        $stmt->bind_param("di", $total_charge, $user_id);
        $stmt->execute();
        
        // Update payment status in bed_assignments
        $stmt = $con->prepare("
            UPDATE bed_assignments 
            SET payment_status = 'Paid',
                total_charge = ?
            WHERE id = ?
        ");
        $stmt->bind_param("di", $total_charge, $assignment_id);
        $stmt->execute();
        
        // Update payment in discharge_requests if exists
        if($discharge_request_id) {
            $stmt = $con->prepare("
                UPDATE discharge_requests 
                SET payment_requested = 1,
                    payment_done = 1
                WHERE id = ?
            ");
            $stmt->bind_param("i", $discharge_request_id);
            $stmt->execute();
        }
        
        $con->commit();
        $_SESSION['success'] = "Payment successful!";
        header("Location: my_bed_bookings.php");
        exit();
    } catch(Exception $e) {
        $con->rollback();
        $_SESSION['error'] = "Payment failed: ".$e->getMessage();
        header("Location: my_bed_bookings.php");
        exit();
    }
}

// Fetch bed bookings with discharge and payment status
$stmt = $con->prepare("
    SELECT ba.*, b.bed_number, b.bed_type, b.price_per_day,
           w.ward_name, w.ward_type,
           p.id as patient_id, p.PatientName, p.PatientContno, p.PatientGender,
           dr.id as discharge_request_id, dr.status as discharge_status, 
           dr.request_date, dr.payment_requested, dr.payment_done,
           ba.admission_date,
           ba.discharge_date,
           CASE 
               WHEN ba.discharge_date IS NOT NULL THEN 
                   DATEDIFF(ba.discharge_date, ba.admission_date) + 1
               ELSE 
                   DATEDIFF(NOW(), ba.admission_date) + 1
           END as days_stayed
    FROM bed_assignments ba
    JOIN beds b ON ba.bed_id = b.bed_id
    JOIN wards w ON b.ward_id = w.ward_id
    JOIN patient p ON ba.patient_id = p.id
    LEFT JOIN discharge_requests dr ON dr.assignment_id = ba.id AND (dr.status = 'Pending' OR dr.status = 'Approved')
    WHERE ba.user_id = ?
    ORDER BY 
        CASE ba.status 
            WHEN 'Admitted' THEN 1 
            WHEN 'Discharged' THEN 2 
            WHEN 'Transferred' THEN 3 
            ELSE 4 
        END,
        ba.admission_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Bed Bookings | MEDIZEN Hospital</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --table-header-bg: #3498db;
            --table-header-color: #ffffff;
            --table-border-color: #dee2e6;
            --row-hover-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .table-container {
            border: 1px solid var(--table-border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table th {
            background-color: var(--table-header-bg);
            color: var(--table-header-color);
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--table-border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--table-border-color);
            font-size: 0.9rem;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover td {
            background-color: var(--row-hover-bg);
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }
        
        .status-admitted {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .status-discharged {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--warning-color);
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .status-transferred {
            background-color: rgba(23, 162, 184, 0.15);
            color: var(--info-color);
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        .payment-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .payment-paid {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .payment-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--info-color);
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-action {
            margin: 2px;
            min-width: 100px;
        }
        
        .ward-type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .general-ward {
            background-color: #e2f0fd;
            color: #1a73e8;
            border: 1px solid #bbdefb;
        }
        
        .icu-ward {
            background-color: #fce8e6;
            color: #d93025;
            border: 1px solid #ffcdd2;
        }
        
        .pediatric-ward {
            background-color: #e6f4ea;
            color: #0d652d;
            border: 1px solid #c8e6c9;
        }
        
        .date-value {
            font-weight: 500;
            color: var(--primary-color);
            white-space: nowrap;
        }
        
        .days-value {
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary-color);
        }
        
        .no-bookings {
            text-align: center;
            padding: 40px 20px;
        }
        
        .no-bookings i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 15px;
        }

        /* Payment Modal Styles */
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .payment-summary-card {
            background-color: #f8f9fa;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        
        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
        }
        
        #insufficientFundsAlert {
            border-left: 4px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border: 1px solid var(--table-border-color);
                border-radius: 8px;
            }
            
            .table {
                min-width: 800px;
            }
            
            .btn-action {
                margin-bottom: 5px;
                width: 100%;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include('include/header_logins_page.php'); ?>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="book_bed.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Book New Bed
            </a>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if($bookings->num_rows > 0): ?>
            <div class="card">
                <div class="card-body p-0">
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center"><b>Bed Number</b></th>
                                        <th class="text-center"><b>Charge/Day</b></th>
                                        <th class="text-center"><b>Admission Date</b></th>
                                        <th class="text-center"><b>Discharge Date</b></th>
                                        <th class="text-center"><b>Days Stayed</b></th>
                                        <th class="text-center"><b>Payment</b></th>
                                        <th class="text-center"><b>Status</b></th>
                                        <th class="text-center"><b>Actions</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($booking = $bookings->fetch_assoc()): 
                                        // Calculate estimated charge
                                        $estimated_charge = $booking['price_per_day'] * $booking['days_stayed'];
                                        $total_charge = $booking['total_charge'] > 0 ? $booking['total_charge'] : $estimated_charge;
                                        
                                        // Format dates
                                        $admission_date = date('M j, Y', strtotime($booking['admission_date']));
                                        $discharge_date = $booking['discharge_date'] ? date('M j, Y', strtotime($booking['discharge_date'])) : 'N/A';
                                        
                                        // Determine status display
                                        $status_text = ucfirst($booking['status']);
                                        $status_class = 'status-' . strtolower($booking['status']);
                                        
                                        if($booking['status'] == 'Admitted' && $booking['discharge_status'] == 'Pending') {
                                            $status_text = 'Discharge Requested';
                                            $status_class = 'status-pending';
                                        }
                                        
                                        // Determine ward type class
                                        $ward_type_class = strtolower(str_replace(' ', '-', $booking['ward_type'])) . '-ward';
                                        $ward_type_class = str_replace(['(', ')'], '', $ward_type_class);
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <div>
                                                <?= htmlspecialchars($booking['bed_number']) ?>
                                                <span class="ward-type-badge <?= $ward_type_class ?>">
                                                    <?= $booking['ward_name'] ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            BDT <?= number_format($booking['price_per_day'], 2) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="date-value"><?= $admission_date ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="date-value"><?= $discharge_date ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="days-value"><?= $booking['days_stayed'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($booking['status'] == 'Admitted'): ?>
                                                <span class="payment-badge payment-pending">
                                                    <b>Estimate:</b> BDT <?= number_format($estimated_charge, 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="payment-badge <?= $booking['payment_status'] == 'Paid' ? 'payment-paid' : 'payment-pending' ?>">
                                                    <b><?= $booking['payment_status'] ?></b>
                                                    <?php if($booking['payment_status'] == 'Paid'): ?>
                                                        <span> - BDT <?= number_format($total_charge, 2) ?></span>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge <?= $status_class ?>">
                                                <?= $status_text ?>
                                            </span>
                                            <?php if($booking['discharge_status'] == 'Pending'): ?>
                                                <div class="text-muted mt-1 small">
                                                    Requested: <?= date('M j', strtotime($booking['request_date'])) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap justify-content-center">
                                                <?php if($booking['status'] == 'Admitted'): ?>
                                                    <?php if($booking['discharge_request_id']): ?>
                                                        <?php if($booking['payment_requested'] == 1 && $booking['payment_done'] == 0): ?>
                                                            <!-- Pay Now Button -->
                                                            <button type="button" class="btn btn-sm btn-success btn-action" 
                                                                    data-bs-toggle="modal" data-bs-target="#paymentModal"
                                                                    data-assignment-id="<?= $booking['id'] ?>"
                                                                    data-total-charge="<?= $total_charge ?>"
                                                                    data-discharge-request-id="<?= $booking['discharge_request_id'] ?>">
                                                                <i class="fas fa-wallet me-1"></i> Pay Now
                                                            </button>
                                                        <?php elseif($booking['payment_done'] == 1 && $booking['discharge_status'] == 'Approved'): ?>
                                                            <!-- Invoice Button -->
                                                            <a href="generate_invoice.php?id=<?= $booking['id'] ?>" 
                                                               class="btn btn-sm btn-info btn-action" target="_blank">
                                                                <i class="fas fa-file-invoice me-1"></i> Invoice
                                                            </a>
                                                        <?php elseif($booking['payment_done'] == 1): ?>
                                                            <!-- Payment Done (waiting for discharge) -->
                                                            <button class="btn btn-sm btn-secondary btn-action" disabled>
                                                                <i class="fas fa-check-circle me-1"></i> Payment Done
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Discharge Requested -->
                                                            <button class="btn btn-sm btn-secondary btn-action" disabled>
                                                                <i class="fas fa-clock me-1"></i> Processing
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <!-- Request Discharge Button -->
                                                        <form method="post" class="d-inline">
                                                            <input type="hidden" name="assignment_id" value="<?= $booking['id'] ?>">N/A
                                                            <!-- <button type="submit" name="request_discharge" 
                                                                    class="btn btn-sm btn-warning btn-action"
                                                                    onclick="return confirm('Are you sure you want to request discharge?')">
                                                                <i class="fas fa-sign-out-alt me-1"></i> Request Discharge
                                                            </button> -->
                                                        </form>
                                                    <?php endif; ?>
                                                <?php elseif($booking['status'] == 'Discharged' && $booking['payment_status'] == 'Pending'): ?>
                                                    <!-- Pay Now Button for discharged patients -->
                                                    <button type="button" class="btn btn-sm btn-success btn-action" 
                                                            data-bs-toggle="modal" data-bs-target="#paymentModal"
                                                            data-assignment-id="<?= $booking['id'] ?>"
                                                            data-total-charge="<?= $total_charge ?>">
                                                        <i class="fas fa-wallet me-1"></i> Pay Now
                                                    </button>
                                                <?php elseif($booking['status'] == 'Discharged' && $booking['payment_status'] == 'Paid'): ?>
                                                    <!-- Invoice Button -->
                                                    <a href="generate_invoice.php?id=<?= $booking['id'] ?>" 
                                                       class="btn btn-sm btn-info btn-action" target="_blank">
                                                        <i class="fas fa-file-invoice me-1"></i> Invoice
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="no-bookings">
                    <i class="fas fa-bed"></i>
                    <h4>No Bed Bookings Found</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <div class="text-center w-100 position-relative">
                        <h5 class="modal-title w-100" id="paymentModalLabel">
                            <i class="fas fa-credit-card me-2"></i> Payment
                        </h5>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold mb-1">Confirm Your Payment</h5>
                    </div>

                    <div class="payment-summary-card border rounded p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Payment Method:</span>
                            <span class="fw-bold">
                                <i class="fas fa-wallet text-success me-1"></i> Wallet Balance
                            </span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Current Balance:</span>
                            <span class="fw-bold text-success" id="walletBalance">BDT 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Amount to Pay:</span>
                            <span class="fw-bold text-primary" id="paymentAmount">BDT 0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Remaining Balance:</span>
                            <div id="balanceDisplay">
                                <span class="fw-bold text-success" id="remainingBalance">BDT 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="alert alert-danger d-flex align-items-center mb-4" id="insufficientFundsAlert" style="display: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>
                            <strong>Insufficient Balance!</strong> Please add funds to your wallet to complete this payment.
                        </div>
                    </div> -->

                    <form id="paymentForm" method="post">
                        <input type="hidden" name="assignment_id" id="modalAssignmentId">
                        <input type="hidden" name="discharge_request_id" id="dischargeRequestId">
                        <div class="d-grid gap-2">
                            <button type="submit" name="make_payment" class="btn btn-primary btn-lg py-3 fw-bold" id="confirmPaymentBtn">
                                <i class="fas fa-check-circle me-2"></i> Confirm Payment
                            </button>
                            <button type="button" class="btn btn-outline-secondary py-3" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Cancel Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('include/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Payment modal script
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const assignmentId = button.getAttribute('data-assignment-id');
            const totalCharge = button.getAttribute('data-total-charge');
            const dischargeRequestId = button.getAttribute('data-discharge-request-id');
            
            // Set the form values
            document.getElementById('modalAssignmentId').value = assignmentId;
            if(dischargeRequestId) {
                document.getElementById('dischargeRequestId').value = dischargeRequestId;
            }
            
            // Display payment amount
            document.getElementById('paymentAmount').textContent = 'BDT ' + parseFloat(totalCharge).toFixed(2);
            
            // Fetch wallet balance via AJAX
            fetch('get_wallet_balance.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const walletBalance = parseFloat(data.balance);
                        const paymentAmount = parseFloat(totalCharge);
                        const remainingBalance = walletBalance - paymentAmount;
                        
                        document.getElementById('walletBalance').textContent = 'BDT ' + walletBalance.toFixed(2);
                        
                        if (remainingBalance >= 0) {
                            // Sufficient funds
                            document.getElementById('remainingBalance').textContent = 'BDT ' + remainingBalance.toFixed(2);
                            document.getElementById('insufficientFundsAlert').style.display = 'none';
                            document.getElementById('confirmPaymentBtn').disabled = false;
                        } else {
                            // Insufficient funds
                            document.getElementById('balanceDisplay').innerHTML = `
                                <span class="fw-bold text-danger">Insufficient Balance</span>
                            `;
                            document.getElementById('insufficientFundsAlert').style.display = 'flex';
                            document.getElementById('confirmPaymentBtn').disabled = true;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching wallet balance:', error);
                });
        });
    }
    </script>
</body>
</html>