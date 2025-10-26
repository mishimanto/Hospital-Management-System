<?php
session_start();
include('include/config.php');

if (!isset($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['id'];
$today = date('Y-m-d');

// Upcoming Tests
$upcoming = mysqli_query($con, "
    SELECT * FROM test_orders 
    WHERE user_id = '$user_id' AND DATE(test_date) > '$today'
    ORDER BY test_date ASC
");

// Today's Tests
$todayTests = mysqli_query($con, "
    SELECT * FROM test_orders 
    WHERE user_id = '$user_id' AND DATE(test_date) = '$today'
    ORDER BY test_time ASC
");

// Previous Tests
$previous = mysqli_query($con, "
    SELECT * FROM test_orders 
    WHERE user_id = '$user_id' AND (DATE(test_date) < '$today' OR status = 'Completed' OR status = 'Cancelled')
    ORDER BY test_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Test Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .appointment-section {
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .section-header {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }
        .today-header { background-color: #d4edda; color: #155724; }
        .previous-header { background-color: #f8d7da; color: #721c24; }
        .upcoming-header { background-color: #cce5ff; color: #004085; }
        .no-appointments {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php include('include/header_logins_page.php'); ?>

<div class="app-content">
    <div class="main-content" style="min-height: 100vh;">
        <div class="wrap-content container py-4">
            <!-- <h2 class="mb-4">My Test Appointments</h2> -->

            <!-- Upcoming -->
            <div class="appointment-section mb-4">
                <div class="section-header upcoming-header">
                    <i class="fas fa-calendar-plus me-2"></i> Upcoming Test Appointments
                </div>
                <?php if (mysqli_num_rows($upcoming) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>#</th><th>Order Number</th><th>Date</th><th>Time</th><th>Amount</th><th>Status</th><th>Action</th><th>Slip</th></tr></thead>
                            <tbody>
                            <?php $i=1; while ($row = mysqli_fetch_assoc($upcoming)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['order_number']) ?></td>
                                    <td><?= htmlspecialchars($row['test_date']) ?></td>
                                    <td><?= htmlspecialchars($row['test_time']) ?></td>
                                    <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                                    <td>
                                        <span class="fw-bold text-warning">Pending</span>
                                    </td>
                                    <td>
                                        <a href="order_details.php?order_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                                        <a href="cancel_order.php?order_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Cancel</a>
                                    </td>
                                    <td>
                                        <a href="download_test_slip.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-appointments">No upcoming test appointments.</div>
                <?php endif; ?>
            </div>

            <!-- Today's Tests -->
            <div class="appointment-section mb-4">
                <div class="section-header today-header">
                    <i class="fas fa-calendar-day me-2"></i> Today's Test Appointments
                </div>
                <?php if (mysqli_num_rows($todayTests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>#</th><th>Order Number</th><th>Time</th><th>Amount</th><th>Status</th><th>Action</th><th>Slip</th><th>Report</th></tr></thead>
                            <tbody>
                            <?php $i=1; while ($row = mysqli_fetch_assoc($todayTests)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['order_number']) ?></td>
                                    <td><?= htmlspecialchars($row['test_time']) ?></td>
                                    <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Completed'): ?>
                                            <span class="fw-bold text-success">Completed</span>
                                        <?php elseif ($row['status'] == 'Cancelled'): ?>
                                            <span class="fw-bold text-danger">Cancelled</span>
                                        <?php else: ?>
                                            <span class="fw-bold text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="order_details.php?order_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                    <td>
                                        <a href="download_test_slip.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'Completed') { ?>
                                            <a href="download-report.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php } else {
                                            echo "N/A";
                                        } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-appointments">No tests scheduled for today.</div>
                <?php endif; ?>
            </div>

            <!-- Previous Tests -->
            <div class="appointment-section">
                <div class="section-header previous-header">
                    <i class="fas fa-history me-2"></i> Previous Test Appointments
                </div>
                <?php if (mysqli_num_rows($previous) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>#</th><th>Order Number</th><th>Date</th><th>Amount</th><th>Status</th><th>Action</th><th>Slip</th><th>Report</th></tr></thead>
                            <tbody>
                            <?php $i=1; while ($row = mysqli_fetch_assoc($previous)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['order_number']) ?></td>
                                    <td><?= htmlspecialchars($row['test_date']) ?></td>
                                    <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Completed'): ?>
                                            <span class="fw-bold text-success">Completed</span>
                                        <?php elseif ($row['status'] == 'Cancelled'): ?>
                                            <span class="fw-bold text-danger">Cancelled</span>
                                        <?php else: ?>
                                            <span class="fw-bold text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="order_details.php?order_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                    <td>
                                        <a href="download_test_slip.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'Completed') { ?>
                                            <a href="download-report.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php } else {
                                            echo "N/A";
                                        } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-appointments">No previous test records found.</div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
