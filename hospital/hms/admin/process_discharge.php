<?php
session_start();
require_once('include/config.php');
require_once('class.user.php');
if(strlen($_SESSION['id'])==0) {
    header('location:logout.php');
    exit();
}

$requests = $con->query("
    SELECT dr.id as request_id, 
           ba.id as assignment_id,
           p.user_id,
           p.PatientName, 
           p.PatientContno,
           b.bed_number, 
           b.bed_type, 
           b.price_per_day,
           w.ward_name, 
           w.ward_type,
           ba.admission_date,
           ba.discharge_date,
           ba.total_charge,
           ba.payment_status,
           dr.requested_by,
           dr.status,
           dr.payment_requested,
           dr.payment_done
    FROM discharge_requests dr
    JOIN bed_assignments ba ON dr.assignment_id = ba.id
    JOIN patient p ON ba.patient_id = p.id
    JOIN beds b ON ba.bed_id = b.bed_id
    JOIN wards w ON b.ward_id = w.ward_id
    WHERE dr.status = 'Pending'
    ORDER BY ba.admission_date ASC
");

// Process discharge request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['process_discharge'])) {
        $request_id = intval($_POST['request_id']);
        $action = $_POST['action'];
        $admin_id = $_SESSION['id'];

        $con->begin_transaction();
        try {
            // Get assignment details with payment status
            $stmt = $con->prepare("
                SELECT ba.*, dr.payment_done, p.PatientName, p.user_id, ba.bed_id, ba.patient_id
                FROM discharge_requests dr
                JOIN bed_assignments ba ON dr.assignment_id = ba.id
                JOIN patient p ON ba.patient_id = p.id
                WHERE dr.id = ?
            ");
            
            if ($stmt === false) {
                throw new Exception("Failed to prepare query: " . $con->error);
            }
            
            $stmt->bind_param("i", $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $assign = $stmt->get_result()->fetch_assoc();
            
            if(!$assign) {
                throw new Exception("Invalid discharge request");
            }
            
            if($action == 'approve') {
                // Verify payment is done if it was requested
                if($assign['payment_requested'] == 1 && $assign['payment_done'] != 1) {
                    throw new Exception("Cannot approve discharge - payment not completed");
                }
                
                $admission_date = new DateTime($assign['admission_date']);
                $discharge_date = new DateTime();
                
                // Calculate days - admission date counts as day 1
                $days = $admission_date->diff($discharge_date)->days + 1;
                $total_charge = $days * $assign['price_per_day'];
                $formatted_discharge_date = $discharge_date->format('Y-m-d H:i:s');

                // Determine payment status - preserve if already paid
                $payment_status = ($assign['payment_done'] == 1) ? 'Paid' : 'Pending';

                // Update bed assignment with prepared statement
                $update_query = "
                    UPDATE bed_assignments 
                    SET discharge_date = ?,
                        total_charge = ?,
                        payment_status = ?,
                        status = 'Discharged'
                    WHERE id = ?
                ";
                
                $stmt = $con->prepare($update_query);
                if ($stmt === false) {
                    throw new Exception("Failed to prepare update query: " . $con->error);
                }
                
                if (!$stmt->bind_param("sdsi", $formatted_discharge_date, $total_charge, $payment_status, $assign['id'])) {
                    throw new Exception("Failed to bind parameters: " . $stmt->error);
                }
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to execute update: " . $stmt->error);
                }
                
                if($stmt->affected_rows === 0) {
                    throw new Exception("Failed to update bed assignment");
                }

                // Update bed status
                $stmt = $con->prepare("
                    UPDATE beds 
                    SET status = 'Available' 
                    WHERE bed_id = ? 
                    AND status = 'Occupied'
                ");
                
                if ($stmt === false) {
                    throw new Exception("Failed to prepare bed update query: " . $con->error);
                }
                
                $stmt->bind_param("i", $assign['bed_id']);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update bed status: " . $stmt->error);
                }
                
                if($stmt->affected_rows === 0) {
                    throw new Exception("Failed to update bed status - bed may already be available");
                }

                // Update discharge request
                $stmt = $con->prepare("
                    UPDATE discharge_requests 
                    SET status = 'Approved',
                        processed_by = ?,
                        processed_at = NOW(),
                        rejection_reason = NULL
                    WHERE id = ?
                ");
                
                if ($stmt === false) {
                    throw new Exception("Failed to prepare discharge request update: " . $con->error);
                }
                
                $stmt->bind_param("ii", $admin_id, $request_id);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update discharge request: " . $stmt->error);
                }
                
                if($stmt->affected_rows === 0) {
                    throw new Exception("Failed to update discharge request");
                }

                // Send discharge confirmation email
                try {
                    // Get patient and user details
                    $stmt = $con->prepare("
                        SELECT p.PatientName, p.PatientContno, u.email 
                        FROM patient p
                        JOIN users u ON p.user_id = u.id
                        WHERE p.id = ?
                    ");
                    
                    if ($stmt) {
                        $stmt->bind_param("i", $assign['patient_id']);
                        $stmt->execute();
                        $patient_data = $stmt->get_result()->fetch_assoc();
                        
                        if($patient_data) {
                            $subject = "Discharge Approved - Hospital Management System";
                            $email_message = "
                                <html>
                                <head>
                                    <title>Discharge Approved</title>
                                </head>
                                <body>
                                    <h2>Dear {$patient_data['PatientName']},</h2>
                                    <p>Your discharge has been successfully processed.</p>
                                    <p><strong>Discharge Summary:</strong></p>
                                    <ul>
                                        <li>Admission Date: ".date('M j, Y', strtotime($assign['admission_date']))."</li>
                                        <li>Discharge Date: ".date('M j, Y')."</li>
                                        <li>Total Days Stayed: $days days</li>
                                        <li>Total Charges: ৳".number_format($total_charge, 2)."</li>
                                        <li>Payment Status: $payment_status</li>
                                    </ul>
                                    <p>Please collect invoice from user panel and show it to receiption to get your discharge documents.</p>
                                    <p>Thank you for choosing our survice.</p>
                                    <p>MEDIZEN</p>
                                </body>
                                </html>
                            ";
                            
                            // Send email
                            $user = new USER();
                            $send_email = $user->sendMail($patient_data['email'], $email_message, $subject);
                            
                            if(!$send_email) {
                                error_log("Failed to send discharge email to {$patient_data['email']}");
                            }
                        }
                    }
                } catch(Exception $e) {
                    error_log("Error sending discharge email: ".$e->getMessage());
                }

                $_SESSION['success'] = "Discharge approved successfully. Total charge for $days days: ৳".number_format($total_charge, 2);
            } else {
                // Reject discharge request
                $stmt = $con->prepare("
                    UPDATE discharge_requests 
                    SET status = 'Rejected',
                        processed_by = ?,
                        processed_at = NOW(),
                        rejection_reason = ?
                    WHERE id = ?
                ");
                
                if ($stmt === false) {
                    throw new Exception("Failed to prepare reject query: " . $con->error);
                }
                
                $rejection_reason = isset($_POST['rejection_reason']) ? $_POST['rejection_reason'] : 'Not specified';
                $stmt->bind_param("isi", $admin_id, $rejection_reason, $request_id);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to reject discharge request: " . $stmt->error);
                }
                
                if($stmt->affected_rows === 0) {
                    throw new Exception("Failed to reject discharge request");
                }

                // Send rejection email notification
                try {
                    // Get patient details
                    $stmt = $con->prepare("
                        SELECT u.email 
                        FROM patient p
                        JOIN users u ON p.user_id = u.id
                        WHERE p.id = ?
                    ");
                    
                    if ($stmt) {
                        $stmt->bind_param("i", $assign['patient_id']);
                        $stmt->execute();
                        $patient_data = $stmt->get_result()->fetch_assoc();
                        
                        if($patient_data) {
                            $subject = "Discharge Request Rejected - Hospital Management System";
                            $email_message = "
                                <html>
                                <head>
                                    <title>Discharge Request Rejected</title>
                                </head>
                                <body>
                                    <h2>Dear {$assign['PatientName']},</h2>
                                    <p>We regret to inform you that your discharge request has been rejected.</p>
                                    <p><strong>Reason:</strong> $rejection_reason</p>
                                    <p>Please contact hospital administration for further clarification.</p>
                                    <p>Thank you,<br>Hospital Administration</p>
                                </body>
                                </html>
                            ";
                            
                            // Send email
                            $user = new USER();
                            $send_email = $user->sendMail($patient_data['email'], $email_message, $subject);
                            
                            if(!$send_email) {
                                error_log("Failed to send rejection email to {$patient_data['email']}");
                            }
                        }
                    }
                } catch(Exception $e) {
                    error_log("Error sending rejection email: ".$e->getMessage());
                }
                
                $_SESSION['success'] = "Discharge request rejected and notification sent.";
            }

            $con->commit();
        } catch(Exception $e) {
            $con->rollback();
            $_SESSION['error'] = "Error processing discharge: ".$e->getMessage();
        }
    } 
    elseif(isset($_POST['request_payment'])) {
        // Handle payment request
        $request_id = intval($_POST['request_id']);
        $admin_id = $_SESSION['id'];
        
        try {
            // First get the user_id from the request
            $stmt = $con->prepare("
                SELECT p.user_id, ba.price_per_day, p.PatientName,
                       ba.admission_date
                FROM discharge_requests dr
                JOIN bed_assignments ba ON dr.assignment_id = ba.id
                JOIN patient p ON ba.patient_id = p.id
                WHERE dr.id = ?
            ");
            
            if ($stmt === false) {
                throw new Exception("Failed to prepare payment request query: " . $con->error);
            }
            
            $stmt->bind_param("i", $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute payment request query: " . $stmt->error);
            }
            
            $result = $stmt->get_result()->fetch_assoc();

            if(!$result) {
                throw new Exception("Invalid discharge request");
            }

            $user_id = $result['user_id'];
            $patient_name = $result['PatientName'];

            // Calculate estimated total based on current date
            $admission_date = new DateTime($result['admission_date']);
            $current_date = new DateTime();
            $days = $admission_date->diff($current_date)->days + 1;
            $est_total = $days * $result['price_per_day'];

            // Update payment request status
            $stmt = $con->prepare("
                UPDATE discharge_requests 
                SET payment_requested = 1,
                    processed_by = ?,
                    processed_at = NOW()
                WHERE id = ?
            ");
            
            if ($stmt === false) {
                throw new Exception("Failed to prepare payment status update: " . $con->error);
            }
            
            $stmt->bind_param("ii", $admin_id, $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update payment request status: " . $stmt->error);
            }

            if($stmt->affected_rows > 0) {
                $_SESSION['success'] = "Payment request sent to patient successfully.";

                // Get user email and current balance
                $stmt = $con->prepare("
                    SELECT email, wallet_balance 
                    FROM users 
                    WHERE id = ?
                ");
                
                if ($stmt) {
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $user_data = $stmt->get_result()->fetch_assoc();
                    
                    if($user_data) {
                        $email = $user_data['email'];
                        $balance = $user_data['wallet_balance'];
                        
                        // Prepare email content with estimated total
                        $subject = "Payment Request for Hospital Discharge";
                        $email_message = "
                            <html>
                            <head>
                                <title>Payment Request</title>
                            </head>
                            <body>
                                <h2>Dear $patient_name,</h2>
                                <p>Your discharge request has been processed and requires payment.</p>
                                <p><strong>Estimated Total Amount:</strong> ৳".number_format($est_total, 2)."</p>
                                <p><strong>Current Wallet Balance:</strong> ৳".number_format($balance, 2)."</p>
                                <p>Please log in to your account to complete the payment and finalize your discharge.</p>
                                <p>Thank you,<br>MEDIZEN Administration</p>
                            </body>
                            </html>
                        ";
                        
                        // Send email
                        $user = new USER();
                        $send_email = $user->sendMail($email, $email_message, $subject);
                        
                        if(!$send_email) {
                            error_log("Failed to send payment request email to $email");
                        }
                    }        
                }
            } else {
                throw new Exception("Failed to send payment request");
            }
        } catch(Exception $e) {
            $_SESSION['error'] = "Error requesting payment: ".$e->getMessage();
        }
    }

    header("Location: process_discharge.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Process Discharge Requests</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hospital Management System">
    <meta name="author" content="Your Name">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,600,700|Raleway:400,500,600|Crete+Round:400italic" rel="stylesheet" type="text/css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Modal CSS -->
    <style>
        .modal-reject .modal-dialog {
            max-width: 500px;
        }
        .modal-reject .modal-body {
            padding: 20px;
        }
        .modal-reject textarea {
            min-height: 100px;
            resize: vertical;
        }
    </style>
</head>
<body>
<div id="app">
    <!-- Sidebar -->
    <?php include('include/sidebar.php'); ?>
    
    <div class="app-content">
        <!-- Header -->
        <?php include('include/header.php'); ?>
        
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <!-- Page Title -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | Process Discharge Requests</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Discharge Requests</span></li>
                        </ol>
                    </div>
                </section>

                <!-- Main Content -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="section-title">Pending Discharge Requests</h5>
                            
                            <!-- Status Messages -->
                            <?php if(isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="fa fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="fa fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Requests Table -->
                            <?php if($requests->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="dischargeTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Patient Name</th>
                                                <th class="text-center">Contact</th>
                                                <th class="text-center">Ward</th>
                                                <th class="text-center">Bed</th>
                                                <th class="text-center">Admission Date</th>
                                                <th class="text-center">Days Stayed</th>
                                                <th class="text-center">Price/Day</th>
                                                <th class="text-center">Est. Total</th>
                                                <th class="text-center">Checked By</th>
                                                <th class="text-center">Payment Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $counter = 1; while($req = $requests->fetch_assoc()): 
                                                $admission = new DateTime($req['admission_date']);
                                                $now = new DateTime();
                                                $days = $admission->diff($now)->days + 1;
                                                $est_total = $days * $req['price_per_day'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $counter++; ?></td>
                                                <td class="text-center"><?= htmlspecialchars($req['PatientName']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($req['PatientContno']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($req['ward_name']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($req['bed_number']) ?> (<?= htmlspecialchars($req['bed_type']) ?>)</td>
                                                <td class="text-center"><?= date('M j, Y H:i', strtotime($req['admission_date'])) ?></td>
                                                <td class="text-center"><?= $days ?></td>
                                                <td class="text-center">৳<?= number_format($req['price_per_day'], 2) ?></td>
                                                <td class="text-center">৳<?= number_format($est_total, 2) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($req['requested_by']) ?></td>
                                                <td class="text-center">
                                                    <?php if($req['payment_done'] == 1): ?>
                                                        <span class="badge bg-success">Paid</span>
                                                    <?php elseif($req['payment_requested'] == 1): ?>
                                                        <span class="badge bg-warning">Payment Requested</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Not Requested</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php if($req['status'] == 'Pending'): ?>
                                                            <!-- Payment Request Button (shown when payment not yet requested) -->
                                                            <?php if($req['payment_requested'] == 0): ?>
                                                                <form method="post" class="d-inline">
                                                                    <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                                                    <button type="submit" name="request_payment" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-money-bill-wave"></i> Request Payment
                                                                    </button>

                                                                    <!-- Reject Button (always shown) -->
                                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal<?= $req['request_id'] ?>">
                                                                        <i class="fa fa-times"></i> Reject
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            
                                                            <!-- Approve Button (shown when payment is done or wasn't requested) -->
                                                            <?php if($req['payment_done'] == 1 ): ?>
                                                                <form method="post" class="d-inline">
                                                                    <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                                                    <input type="hidden" name="action" value="approve">
                                                                    <button type="submit" name="process_discharge" class="btn btn-success btn-sm">
                                                                        <i class="fa fa-check"></i> Approve
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            
                                                            
                                                            
                                                            <!-- Rejection Reason Modal -->
                                                            <div class="modal fade modal-reject" id="rejectModal<?= $req['request_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title" id="rejectModalLabel">Reject Discharge Request</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post">
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                                                                <input type="hidden" name="action" value="reject">
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="rejection_reason">Reason for Rejection</label>
                                                                                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" required><?= htmlspecialchars($req['rejection_reason'] ?? '') ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                                <button type="submit" name="process_discharge" class="btn btn-danger">Confirm Rejection</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Processed</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> No pending discharge requests found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('include/footer.php'); ?>
    
    <!-- Settings Panel -->
    <?php include('include/setting.php'); ?>
</div>

<!-- JavaScript Files -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>

<!-- DataTables -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap.min.js"></script>

<!-- App JS -->
<script src="assets/js/main.js"></script>

<script>
    jQuery(document).ready(function() {
        Main.init();
        
        // Initialize DataTable
        $('#dischargeTable').DataTable({
            responsive: true,
            "order": [[5, "desc"]],
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": {
                "search": "",
                "searchPlaceholder": "Search patient, ward, bed...",
                "lengthMenu": "Show _MENU_ records per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "paginate": {
                    "first": "<i class='fas fa-angle-double-left'></i>",
                    "last": "<i class='fas fa-angle-double-right'></i>",
                    "next": "<i class='fas fa-angle-right'></i>",
                    "previous": "<i class='fas fa-angle-left'></i>"
                },
                "emptyTable": "No discharge requests found",
                "zeroRecords": "No matching records found"
            },
            "initComplete": function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm')
                    .attr('style', 'width: 250px; display: inline-block; margin-bottom: 10px;');
                
                $('.dataTables_length select').addClass('form-control form-control-sm')
                    .attr('style', 'width: 80px; display: inline-block;');
                
                $('.paginate_button').addClass('btn btn-sm btn-outline-primary mx-1');
            },
            "drawCallback": function() {
                $('.paginate_button').removeClass('current').addClass('btn btn-sm btn-outline-primary mx-1');
                $('.paginate_button.current').removeClass('btn-outline-primary').addClass('btn-primary');
            },
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 },
                { "responsivePriority": 2, "targets": 1 },
                { "responsivePriority": 3, "targets": -1 },
                { "orderable": false, "targets": -1 },
                { "className": "text-center", "targets": [0, 6, 7, 8, 9, 10] },
                { "width": "40px", "targets": 0 }
            ],
            "stateSave": true,
            "autoWidth": false
        });
        
        // Confirmation for actions
        $('form').submit(function() {
            var action = $(this).find('input[name="action"]').val();
            var message = '';
            
            if(action) {
                message = action === 'approve' 
                    ? 'Are you sure you want to APPROVE this discharge?' 
                    : 'Are you sure you want to REJECT this discharge?';
            } else {
                message = 'Are you sure you want to send payment request to this patient?';
            }
            
            return confirm(message);
        });
    });
</script>
</body>
</html>