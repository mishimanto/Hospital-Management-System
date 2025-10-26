<?php
date_default_timezone_set('Asia/Dhaka');
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $monthName = date('F', mktime(0, 0, 0, $currentMonth, 10));
    
    // Get appointment totals
    $appointmentSummary = mysqli_query($con, "
        SELECT 
            COUNT(*) as totalAppointments,
            SUM(payment_amount) as totalAmount
        FROM appointment
        WHERE MONTH(appointmentDate) = '$currentMonth'
        AND YEAR(appointmentDate) = '$currentYear'
        AND payment_status = 'Paid'
    ");
    $appointmentData = mysqli_fetch_array($appointmentSummary);
    
    // Get ambulance totals
    $ambulanceSummary = mysqli_query($con, "
        SELECT 
            COUNT(*) as totalBookings,
            SUM(cost) as totalAmount
        FROM ambulance_bookings
        WHERE MONTH(booking_time) = '$currentMonth'
        AND YEAR(booking_time) = '$currentYear'
        AND status = 'Paid'
    ");
    $ambulanceData = mysqli_fetch_array($ambulanceSummary);
    
    // Get diagnostic test totals
    $testSummary = mysqli_query($con, "
        SELECT 
            COUNT(*) as totalTests,
            SUM(total_amount) as totalAmount
        FROM test_orders
        WHERE MONTH(created_at) = '$currentMonth'
        AND YEAR(created_at) = '$currentYear'
        AND payment_status = 'Paid'
    ");
    $testData = mysqli_fetch_array($testSummary);

    // Get bed assignment totals
    $bedSummary = mysqli_query($con, "
        SELECT 
            COUNT(*) as totalAssignments,
            SUM(total_charge) as totalAmount
        FROM bed_assignments
        WHERE MONTH(admission_date) = '$currentMonth'
        AND YEAR(admission_date) = '$currentYear'
        AND payment_status = 'Paid'
    ");
    $bedData = mysqli_fetch_array($bedSummary);
    
    // Calculate combined totals
    $combinedTransactions = $appointmentData['totalAppointments'] + $ambulanceData['totalBookings'] + $testData['totalTests'] + $bedData['totalAssignments'];
    $combinedRevenue = $appointmentData['totalAmount'] + $ambulanceData['totalAmount'] + $testData['totalAmount'] + $bedData['totalAmount'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Monthly Revenue Report</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        .monthly-summary-card {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            border-top: 4px solid #3498db;
        }
        .combined-summary-card {
            background-color: #e8f4fc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #2c3e50;
            border-right: 4px solid #2c3e50;
        }
        .badge-pink {
            background-color: #ff6b9d;
            color: white;
        }
        .day-header {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .test-badge {
            background-color: #6f42c1;
            color: white;
        }
        @media print {
            .no-print {
                display: none;
            }
            .day-header {
                background-color: #e9ecef !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            body {
                padding: 20px;
                font-size: 12px;
            }
            .table td, .table th {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <!-- start: PAGE TITLE -->
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Monthly Revenue Report</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Monthly Revenue</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-xl-9">
                                <!-- Combined Summary Card -->
                                <div class="combined-summary-card" style="margin-bottom: 50px;">
                                    <h2 class="mb-4 text-center text-dark">
                                        <i class="fas fa-chart-pie mr-2"></i>
                                        Combined Monthly Summary
                                    </h2>
                                    <p class="devider" style="border-bottom: 2px solid lightslategrey; width: 40%; margin: auto;"></p>
                                    <div class="row text-center" style="margin-top: 3%;">
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Appointment Revenue</h5>
                                                    <h4 class="text-success">৳ <?php echo number_format($appointmentData['totalAmount'], 2); ?></h4>
                                                    <p class="text-muted mb-0"><?php echo $appointmentData['totalAppointments']; ?> appointments</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Ambulance Revenue</h5>
                                                    <h4 class="text-info">৳ <?php echo number_format($ambulanceData['totalAmount'], 2); ?></h4>
                                                    <p class="text-muted mb-0"><?php echo $ambulanceData['totalBookings']; ?> bookings</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Diagnostic Tests</h5>
                                                    <h4 class="text-purple">৳ <?php echo number_format($testData['totalAmount'], 2); ?></h4>
                                                    <p class="text-muted mb-0"><?php echo $testData['totalTests']; ?> tests</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Bed Assignments</h5>
                                                    <h4 class="text-warning">৳ <?php echo number_format($bedData['totalAmount'], 2); ?></h4>
                                                    <p class="text-muted mb-0"><?php echo $bedData['totalAssignments']; ?> assignments</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4" style="margin-top: 40px;">
                                        <div class="col-12">
                                            <div class="alert alert-success text-center">
                                                <h3 class="mb-0">
                                                    <i class="fas fa-coins mr-2"></i>
                                                    Total Monthly Revenue: 
                                                    <span class="font-weight-bold" style="font-size: 25px; color: green;">৳ <?php echo number_format($combinedRevenue, 2); ?></span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Appointments Report -->
                                <div class="report-header mb-5">
                                    <div class="monthly-summary-card">
                                        <div class="row">
                                            <div class="col-md-6"><h3 class="tittle-w3-agileits mb-3 text-primary">Monthly Appointments Report</h3>
                                                <h5 class="mb-0">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    Month: 
                                                    <span class="font-weight-bold text-info"><?php echo $monthName . ' ' . $currentYear; ?></span>
                                                </h5>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-calendar-check mr-2"></i>
                                                    Total Appointments: 
                                                    <span class="font-weight-bold text-primary"><?php echo $appointmentData['totalAppointments']; ?></span>
                                                </h5>
                                                <h5 class="mb-0">
                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                    Total Revenue: 
                                                    <span class="font-weight-bold text-success">৳ <?php echo number_format($appointmentData['totalAmount'], 2); ?></span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive-lg">
                                    <table class="table table-bordered table-hover" id="report-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>Patient Name</th>
                                                <th width="12%" class="text-center">Contact</th>
                                                <th width="10%" class="text-center">Gender</th>
                                                <th width="15%" class="text-center">Appointment Date</th>
                                                <th width="15%" class="text-right">Payment (৳)</th>
                                                <th width="10%" class="text-center">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            $appointmentTotal = 0;
                                            $currentDay = null;
                                            
                                            $sql = mysqli_query($con, "
                                                SELECT 
                                                    tblpatient.ID AS PatientID,
                                                    tblpatient.PatientName,
                                                    tblpatient.PatientContno,
                                                    tblpatient.PatientGender,
                                                    appointment.appointmentDate,
                                                    appointment.payment_amount AS TotalPayment
                                                FROM tblpatient
                                                INNER JOIN appointment ON tblpatient.ID = appointment.userId
                                                WHERE MONTH(appointment.appointmentDate) = '$currentMonth'
                                                AND YEAR(appointment.appointmentDate) = '$currentYear'
                                                AND appointment.payment_status = 'Paid'
                                                ORDER BY appointment.appointmentDate ASC
                                            ");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_array($sql)) {
                                                    $appointmentTotal += $row['TotalPayment'];
                                                    $appointmentDate = strtotime($row['appointmentDate']);
                                                    $day = date('d', $appointmentDate);
                                                    
                                                    if ($day != $currentDay) {
                                                        $currentDay = $day;
                                                        $dayName = date('l', $appointmentDate);
                                            ?>
                                                        <tr class="day-header">
                                                            <td colspan="7">
                                                                <strong><?php echo $dayName . ', ' . date('F j, Y', $appointmentDate); ?></strong>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                            ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $cnt; ?>.</td>
                                                        <td class="align-middle font-weight-bold"><?php echo $row['PatientName']; ?></td>
                                                        <td class="text-center align-middle"><?php echo $row['PatientContno']; ?></td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge badge-<?php echo ($row['PatientGender'] == 'Male') ? 'primary' : 'pink'; ?>">
                                                                <?php echo $row['PatientGender']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <?php echo date('M d, Y', $appointmentDate); ?>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <span class="text-success font-weight-bold">
                                                                <?php echo number_format($row['TotalPayment'], 2); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="view-patient.php?viewid=<?php echo $row['PatientID']; ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="View Patient Details">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                            <?php 
                                                    $cnt++;
                                                } 
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center py-4 text-muted">No appointments found for this month</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="5" class="text-right">Appointments Total:</th>
                                                <th class="text-right text-success font-weight-bold">
                                                    ৳ <?php echo number_format($appointmentTotal, 2); ?>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Ambulance Report -->
                                <div class="report-header mb-5 mt-5" style="margin-top: 30px;">
                                    <div class="monthly-summary-card">
                                        <div class="row">
                                            <div class="col-md-6"><h3 class="tittle-w3-agileits mb-3 text-primary">Monthly Ambulance Report</h3>
                                                <h5 class="mb-0">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    Month: 
                                                    <span class="font-weight-bold text-info"><?php echo $monthName . ' ' . $currentYear; ?></span>
                                                </h5>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-ambulance mr-2"></i>
                                                    Total Bookings: 
                                                    <span class="font-weight-bold text-primary"><?php echo $ambulanceData['totalBookings']; ?></span>
                                                </h5>
                                                <h5 class="mb-0">
                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                    Total Revenue: 
                                                    <span class="font-weight-bold text-success">৳ <?php echo number_format($ambulanceData['totalAmount'], 2); ?></span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive-lg">
                                    <table class="table table-bordered table-hover" id="ambulance-report-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>User Name</th>
                                                <th>Ambulance No.</th>
                                                <th>Pickup Location</th>
                                                <th width="15%" class="text-center">Booking Date</th>
                                                <th width="15%" class="text-right">Payment (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            $ambulanceTotal = 0;
                                            $currentDay = null;

                                            $sql = mysqli_query($con, "
                                                SELECT 
                                                    ambulance_bookings.id AS booking_id,
                                                    users.fullName AS PatientName,
                                                    ambulance_bookings.booking_time,
                                                    ambulance_bookings.cost,
                                                    ambulance_bookings.pickup_location,
                                                    ambulances.ambulance_number
                                                FROM ambulance_bookings
                                                INNER JOIN users ON ambulance_bookings.user_id = users.id
                                                INNER JOIN ambulances ON ambulance_bookings.ambulance_number = ambulances.ambulance_number
                                                WHERE MONTH(ambulance_bookings.booking_time) = '$currentMonth'
                                                AND YEAR(ambulance_bookings.booking_time) = '$currentYear'
                                                AND ambulance_bookings.status = 'Paid'
                                                ORDER BY ambulance_bookings.booking_time ASC
                                            ");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_array($sql)) {
                                                    $ambulanceTotal += $row['cost'];
                                                    $bookingTime = strtotime($row['booking_time']);
                                                    $day = date('d', $bookingTime);

                                                    if ($day != $currentDay) {
                                                        $currentDay = $day;
                                                        $dayName = date('l', $bookingTime);
                                            ?>
                                                        <tr class="day-header bg-light">
                                                            <td colspan="6">
                                                                <strong><?php echo $dayName . ', ' . date('F j, Y', $bookingTime); ?></strong>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                            ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $cnt++; ?>.</td>
                                                        <td class="align-middle font-weight-bold"><?php echo $row['PatientName']; ?></td>
                                                        <td class="align-middle"><?php echo $row['ambulance_number']; ?></td>
                                                        <td class="align-middle"><?php echo $row['pickup_location']; ?></td>
                                                        <td class="text-center align-middle">
                                                            <?php echo date('h:i A - M d, Y', $bookingTime); ?>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <span class="text-success font-weight-bold">
                                                                ৳ <?php echo number_format($row['cost'], 2); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                            <?php 
                                                } 
                                            } else {
                                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">No ambulance bookings found for this month</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="5" class="text-right">Ambulance Total:</th>
                                                <th class="text-right text-success font-weight-bold">
                                                    ৳ <?php echo number_format($ambulanceTotal, 2); ?>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Diagnostic Tests Report -->
                                <div class="report-header mb-5 mt-5" style="margin-top: 30px;">
                                    <div class="monthly-summary-card">
                                        <div class="row">
                                            <div class="col-md-6"><h3 class="tittle-w3-agileits mb-3 text-primary">Monthly Diagnostic Tests Report</h3>
                                                <h5 class="mb-0">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    Month: 
                                                    <span class="font-weight-bold text-info"><?php echo $monthName . ' ' . $currentYear; ?></span>
                                                </h5>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-flask mr-2"></i>
                                                    Total Tests: 
                                                    <span class="font-weight-bold text-primary"><?php echo $testData['totalTests']; ?></span>
                                                </h5>
                                                <h5 class="mb-0">
                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                    Total Revenue: 
                                                    <span class="font-weight-bold text-success">৳ <?php echo number_format($testData['totalAmount'], 2); ?></span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive-lg">
                                    <table class="table table-bordered table-hover" id="test-report-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>Patient Name</th>
                                                <th>Test Name</th>
                                                <th>Test Date</th>
                                                <th>Status</th>
                                                <th width="15%" class="text-right">Payment (৳)</th>
                                                <th width="10%" class="text-center">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            $testTotal = 0;
                                            $currentDay = null;

                                            $sql = mysqli_query($con, "
                                                SELECT 
                                                    test_orders.id AS order_id,
                                                    users.fullName AS PatientName,
                                                    test_orders.test_date,
                                                    test_orders.total_amount,
                                                    test_orders.created_at,
                                                    test_orders.status,
                                                    diagnostic_tests.name AS test_name
                                                FROM test_orders
                                                INNER JOIN users ON test_orders.user_id = users.id
                                                INNER JOIN ordered_tests ON test_orders.id = ordered_tests.order_id
                                                INNER JOIN diagnostic_tests ON ordered_tests.test_id = diagnostic_tests.id
                                                WHERE MONTH(test_orders.created_at) = '$currentMonth'
                                                AND YEAR(test_orders.created_at) = '$currentYear'
                                                AND test_orders.payment_status = 'Paid'
                                                ORDER BY test_orders.created_at ASC
                                            ");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_array($sql)) {
                                                    $testTotal += $row['total_amount'];
                                                    $testDate = strtotime($row['test_date']);
                                                    $createDate = strtotime($row['created_at']);
                                                    $day = date('d', $createDate);

                                                    if ($day != $currentDay) {
                                                        $currentDay = $day;
                                                        $dayName = date('l', $createDate);
                                            ?>
                                                        <tr class="day-header bg-light">
                                                            <td colspan="7">
                                                                <strong><?php echo $dayName . ', ' . date('F j, Y', $createDate); ?></strong>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                            ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $cnt++; ?>.</td>
                                                        <td class="align-middle font-weight-bold"><?php echo $row['PatientName']; ?></td>
                                                        <td class="align-middle"><?php echo $row['test_name']; ?></td>
                                                        <td class="align-middle">
                                                            <?php echo date('M d, Y', $testDate); ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge badge-<?php 
                                                                echo ($row['status'] == 'Completed') ? 'success' : 
                                                                (($row['status'] == 'Pending') ? 'warning' : 
                                                                (($row['status'] == 'Processing') ? 'info' : 'secondary')); 
                                                            ?>">
                                                                <?php echo $row['status']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <span class="text-success font-weight-bold">
                                                                ৳ <?php echo number_format($row['total_amount'], 2); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="test-order-details.php?id=<?php echo $row['order_id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="View Test Details">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                            <?php 
                                                } 
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center py-4 text-muted">No diagnostic tests found for this month</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="5" class="text-right">Diagnostic Tests Total:</th>
                                                <th class="text-right text-success font-weight-bold">
                                                    ৳ <?php echo number_format($testTotal, 2); ?>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Bed Assignments Report -->
                                <div class="report-header mb-5 mt-5" style="margin-top: 30px;">
                                    <div class="monthly-summary-card">
                                        <div class="row">
                                            <div class="col-md-6"><h3 class="tittle-w3-agileits mb-3 text-primary">Monthly Bed Assignments Report</h3>
                                                <h5 class="mb-0">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    Month: 
                                                    <span class="font-weight-bold text-info"><?php echo $monthName . ' ' . $currentYear; ?></span>
                                                </h5>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-bed mr-2"></i>
                                                    Total Assignments: 
                                                    <span class="font-weight-bold text-primary"><?php echo $bedData['totalAssignments']; ?></span>
                                                </h5>
                                                <h5 class="mb-0">
                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                    Total Revenue: 
                                                    <span class="font-weight-bold text-success">৳ <?php echo number_format($bedData['totalAmount'], 2); ?></span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive-lg">
                                    <table class="table table-bordered table-hover" id="bed-report-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>Patient Name</th>
                                                <th>Bed Number</th>
                                                <th>Ward Type</th>
                                                <th width="12%" class="text-center">Admission Date</th>
                                                <th width="12%" class="text-center">Discharge Date</th>
                                                <th width="8%" class="text-center">Days</th>
                                                <th width="10%" class="text-right">Price/Day (৳)</th>
                                                <th width="12%" class="text-right">Payment (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            $bedTotal = 0;
                                            $currentDay = null;

                                            $sql = mysqli_query($con, "
                                                SELECT 
                                                    bed_assignments.id,
                                                    patient.PatientName,
                                                    beds.bed_number,
                                                    beds.price_per_day,
                                                    wards.ward_type,
                                                    bed_assignments.admission_date,
                                                    bed_assignments.discharge_date,
                                                    bed_assignments.total_charge,
                                                    DATEDIFF(
                                                        IFNULL(bed_assignments.discharge_date, CURDATE()), 
                                                        bed_assignments.admission_date
                                                    ) AS days_stayed
                                                FROM bed_assignments
                                                INNER JOIN patient ON bed_assignments.patient_id = patient.id
                                                INNER JOIN beds ON bed_assignments.bed_id = beds.bed_id
                                                INNER JOIN wards ON beds.ward_id = wards.ward_id
                                                WHERE MONTH(bed_assignments.admission_date) = '$currentMonth'
                                                AND YEAR(bed_assignments.admission_date) = '$currentYear'
                                                AND bed_assignments.payment_status = 'Paid'
                                                ORDER BY bed_assignments.admission_date ASC
                                            ");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_array($sql)) {
                                                    $bedTotal += $row['total_charge'];
                                                    $admissionDate = strtotime($row['admission_date']);
                                                    $discharge_Date = strtotime($row['discharge_date']);
                                                    $day = date('d', $admissionDate);

                                                    if ($day != $currentDay) {
                                                        $currentDay = $day;
                                                        $dayName = date('l', $admissionDate);
                                            ?>
                                                        <tr class="day-header bg-light">
                                                            <td colspan="9">
                                                                <strong><?php echo $dayName . ', ' . date('F j, Y', $admissionDate); ?></strong>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                                    
                                                    $dischargeDate = !empty($row['discharge_date']) ? date('M d, Y', strtotime($row['discharge_date'])) : 'Not Discharged';
                                            ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $cnt++; ?>.</td>
                                                        <td class="align-middle font-weight-bold"><?php echo $row['PatientName']; ?></td>
                                                        <td class="align-middle"><?php echo $row['bed_number']; ?></td>
                                                        <td class="align-middle"><?php echo $row['ward_type']; ?></td>
                                                        <td class="text-center align-middle">
                                                            <?php echo date('M d, Y', $admissionDate); ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <?php echo $dischargeDate; ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <?php echo $row['days_stayed']; ?>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <span class="text-info font-weight-bold">
                                                                ৳ <?php echo number_format($row['price_per_day'], 2); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <span class="text-success font-weight-bold">
                                                                ৳ <?php echo number_format($row['total_charge'], 2); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                            <?php 
                                                } 
                                            } else {
                                                echo '<tr><td colspan="9" class="text-center py-4 text-muted">No bed assignments found for this month</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="8" class="text-right">Bed Assignments Total:</th>
                                                <th class="text-right text-success font-weight-bold">
                                                    ৳ <?php echo number_format($bedTotal, 2); ?>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Print Button -->
                                <div class="report-footer mt-4 text-center">
                                    <button class="btn btn-primary no-print mr-2" onclick="window.print()">
                                        <i class="fas fa-print mr-2"></i> Print Report
                                    </button>
                                </div>
                                <p class="text-muted mt-3 mb-0 text-center">
                                    Report generated on: <?php echo date('M d, Y h:i A'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- start: FOOTER -->
        <?php include('include/footer.php'); ?>
        <!-- end: FOOTER -->

        <!-- start: SETTINGS -->
        <?php include('include/setting.php'); ?>
        <!-- end: SETTINGS -->
    </div>

    <!-- start: MAIN JAVASCRIPTS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <!-- end: MAIN JAVASCRIPTS -->

    <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: CLIP-TWO JAVASCRIPTS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            FormElements.init();
        });
    </script>
    <!-- end: CLIP-TWO JAVASCRIPTS -->
</body>
</html>
<?php } ?>