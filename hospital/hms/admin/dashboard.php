<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin | Dashboard</title>
        
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
        <!-- Favicon -->
        <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">

        <style>
            .dashboard-card {
                border: none;
                border-radius: 5px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                transition: all 0.3s ease;
                overflow: hidden;
                height: 100%;
                background-color: #f8f9fc;
                text-align: center;
                padding: 30px;
                margin: 8px 0;
            }

            .dashboard-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            }

            .card-body {
                display: flex;
                align-items: center;
                justify-content: start;
                padding: 15px 20px;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                font-size: 26px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                box-shadow: 0 3px 6px rgba(0,0,0,0.05);
                margin-right: 15px;
                flex-shrink: 0;
            }
            .card-primary {
                background-color: #e0ecff;
            }

            .card-primary .card-icon {
                background: #4e73df;
            }

            .card-info {
                background-color: #d1f4f9;
            }

            .card-info .card-icon {
                background: #36b9cc;
            }

            .card-success {
                background-color: #d3f9d8;
            }

            .card-success .card-icon {
                background: #1cc88a;
            }

            .card-warning {
                background-color: #fff3cd;
            }

            .card-warning .card-icon {
                background: #f6c23e;
            }

            .card-danger {
                background-color: #fde2e1;
            }

            .card-danger .card-icon {
                background: #e74a3b;
            }

            .card-secondary {
                background-color: #e2e3e5;
            }

            .card-secondary .card-icon {
                background: #858796;
            }

            .card-title {
                font-size: 16px;
                font-weight: 600;
                margin: 0;
                color: #343a40;
                text-align: right;
            }

            .card-value {
                font-size: 24px;
                font-weight: 800;
                color: #212529;
                text-align: right;
            }
            .card-text-wrap {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-end;
                flex: 1;
            }

            .card-link {
                display: inline-block;
                padding: 6px 16px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                background-color: rgba(0,0,0,0.05);
                color: #212529;
            }

            .card-link:hover {
                background-color: rgba(0,0,0,0.15);
                color: #000;
            }

            .recent-activity {
                max-height: 400px;
                overflow-y: auto;
            }
            .activity-item {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }
            .activity-time {
                font-size: 12px;
                color: #6c757d;
            }
            .activity-user {
                font-weight: 600;
            }
            .chart-container {
                position: relative;
                height: 300px;
                margin-bottom: 20px;
            }

            .list-group-item {
                transition: background-color 0.2s;
                font-size: 17px;
            }
            .list-group-item:hover {
                background-color: #f8f9fa;
            }
            .badge {
                font-weight: 500;
                padding: 0.35em 0.65em;
            }
            h6
            {
                font-size: 17px;
            }
        </style>
    </head>
    <body>
        <div id="app">      
            <?php include('include/header.php');?>
            <div class="app-content">                       
                <?php include('include/sidebar.php');?>
                <!-- end: TOP NAVBAR -->
                <div class="main-content" >
                    <div class="wrap-content container" id="container">
                        <div class="container-fluid container-fullw bg-white">
                            <div class="row">
                                <!-- Main Dashboard Cards -->
                                <div class="row">
                                    <!-- Manage Users Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-info">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fa fa-user-circle"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Users</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $result = mysqli_query($con,"SELECT * FROM users");
                                                        $num_rows = mysqli_num_rows($result);
                                                        echo htmlentities($num_rows);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Manage Doctors Card -->
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card dashboard-card card-info">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fa fa-user-md"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Doctors</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $result1 = mysqli_query($con,"SELECT * FROM doctors");
                                                        $num_rows1 = mysqli_num_rows($result1);
                                                        echo htmlentities($num_rows1);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="manage-doctors.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manage Emergency Doctors Card -->
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card dashboard-card card-info">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fa fa-user-md"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Emergency Doctors</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $result1 = mysqli_query($con,"SELECT * FROM em_doctors");
                                                        $num_rows1 = mysqli_num_rows($result1);
                                                        echo htmlentities($num_rows1);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="manage-doctors.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Appointments Card -->
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card dashboard-card card-danger">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Appointments</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $sql = mysqli_query($con,"SELECT * FROM appointment");
                                                        $num_rows2 = mysqli_num_rows($sql);
                                                        echo htmlentities($num_rows2);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="appointment-history.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manage Patients Card -->
                                    <!-- <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card dashboard-card card-danger">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fas fa-procedures"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Manage Patients</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $result = mysqli_query($con,"SELECT * FROM tblpatient");
                                                        $num_rows = mysqli_num_rows($result);
                                                        echo htmlentities($num_rows);
                                                        ?>
                                                    </div>
                                                </div>
                                                 <a href="manage-patient.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div> --> 

                                    <!-- New Queries Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-danger">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="ti-email"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">New Queries</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $sql = mysqli_query($con,"SELECT * FROM tblcontactus where IsRead is null");
                                                        $num_rows22 = mysqli_num_rows($sql);
                                                        echo htmlentities($num_rows22);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="unread-queries.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ambulance Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-danger">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fas fa-ambulance"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Ambulance</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $sql = mysqli_query($con,"SELECT * FROM ambulances");
                                                        $num_rows22 = mysqli_num_rows($sql);
                                                        echo htmlentities($num_rows22);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="manage-ambulance.php" class="card-link">
                                                    View Details <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Revenue Summary Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card" style="background-color: #e8f5e9;">
                                            <div class="card-body">
                                                <div class="card-icon" style="background: #4caf50;">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Total Revenue</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $revenue = mysqli_query($con,"SELECT SUM(payment_amount) as total FROM appointment WHERE payment_status='Paid'");
                                                        $row = mysqli_fetch_assoc($revenue);
                                                        echo '৳'.number_format($row['total'], 2);
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="financial-reports.php" class="card-link">
                                                    View Reports <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hospital Capacity Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card" style="background-color: #e8f5e9;">
                                            <div class="card-body">
                                                <div class="card-icon" style="background: #4caf50;">
                                                    <i class="fas fa-bed"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Bed Occupancy</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $total_beds = mysqli_query($con,"SELECT COUNT(*) as total FROM beds");
                                                        $occupied_beds = mysqli_query($con,"SELECT COUNT(*) as occupied FROM bed_assignments WHERE status='Admitted'");
                                                        $beds = mysqli_fetch_assoc($total_beds);
                                                        $occupied = mysqli_fetch_assoc($occupied_beds);
                                                        echo $occupied['occupied'].'/'.$beds['total'];
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="manage-beds.php" class="card-link">
                                                    Manage Beds <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Diagnostic Tests Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card" style="background-color: #e8f5e9;">
                                            <div class="card-body">
                                                <div class="card-icon" style="background: #4caf50;">
                                                    <i class="fas fa-flask"></i>
                                                </div>
                                                <div class="card-text-wrap">
                                                    <h3 class="card-title">Diagnostic Tests</h3>
                                                    <div class="card-value">
                                                        <?php 
                                                        $pending = mysqli_query($con,"SELECT COUNT(*) as pending FROM ordered_tests WHERE status='Pending'");
                                                        $completed = mysqli_query($con,"SELECT COUNT(*) as completed FROM ordered_tests WHERE status='Completed'");
                                                        $p = mysqli_fetch_assoc($pending);
                                                        $c = mysqli_fetch_assoc($completed);
                                                        echo $p['pending'].' Pending / '.$c['completed'].' Done';
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- <a href="manage_tests.php" class="card-link">
                                                    View Tests <i class="fas fa-arrow-circle-right"></i>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Activity and Quick Stats Section -->
                                   <!-- Recent Activity and Quick Stats Section -->
                                <div class="row">
                                    <!-- Recent Activity -->
                                    <div class="col-md-6" style="margin-top: 80px;">
                                        <div class="card">
                                            <div class="">
                                                <h4 class="" style="font-size:20px; text-align: center; ">Recent Top Up</h4>
                                            </div>
                                            <div class=" recent-activity">
                                                <?php
                                                $walletActivity = mysqli_query($con, "
                                                    SELECT wr.*, u.email, u.wallet_balance 
                                                    FROM wallet_requests wr
                                                    JOIN users u ON wr.user_id = u.id
                                                    WHERE wr.status = 'Approved' 
                                                    ORDER BY wr.requested_at DESC 
                                                    LIMIT 15
                                                ");

                                                if (mysqli_num_rows($walletActivity) > 0) {
                                                    while($row = mysqli_fetch_assoc($walletActivity)) {
                                                        echo '<div class="list-group-item py-3 px-2 border-bottom">
                                                                <div class="row align-items-center">
                                                                    <!-- Left: User Info -->
                                                                    <div class="col-md-6 mb-2 mb-md-0">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <i class="fas fa-user-circle text-primary me-2 fs-5"></i>
                                                                            <strong class="mb-0">'.htmlspecialchars($row['userName']).'</strong>
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <i class="far fa-clock me-1"></i> '.date("M d, h:i A", strtotime($row['requested_at'])).'
                                                                        </div>
                                                                        <div class="text-muted small">
                                                                            <i class="fas fa-envelope me-1"></i> '.htmlspecialchars($row['email']).'
                                                                        </div>
                                                                    </div>

                                                                    <!-- Right: Amount and New Balance -->
                                                                    <div class="col-md-6">
                                                                        <div class="d-flex justify-content-md-end justify-content-start align-items-center gap-3">
                                                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fs-6">
                                                                                <i class="fas fa-coins me-1"></i> ৳'.number_format($row['amount'], 2).'
                                                                            </span>
                                                                            <div class="text-end">
                                                                                <div class="text-muted small">New Balance</div>
                                                                                <div class="fw-semibold text-dark">৳'.number_format($row['wallet_balance'], 2).'</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';

                                                    }
                                                } else {
                                                    echo '<div class="text-muted text-center">No approved wallet requests found.</div>';
                                                }
                                                ?>
                                            </div>

                                            <!-- <div class="card-footer text-right">
                                                <a href="activity-logs.php" class="btn btn-sm btn-primary">View All Activity</a>
                                            </div> -->
                                        </div>
                                    </div>

                                    <!-- Quick Stats -->
                                    <div class="col-md-6" style="margin-top: 80px;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="" style="font-size:20px; text-align: center;">Quick Statistics</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 mb-3" style="margin-bottom: 35px;">
                                                        <div class="stat-card">
                                                            <h6>Today's Appointments</h6>
                                                            <?php
                                                            $today = date("Y-m-d");
                                                            $todays_appointments = mysqli_query($con,"SELECT COUNT(*) as total FROM appointment WHERE appointmentDate='$today'");
                                                            $ta = mysqli_fetch_assoc($todays_appointments);
                                                            echo '<h3 class="text-primary" >'.$ta['total'].'</h3>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-3" style="margin-bottom: 35px;">
                                                        <div class="stat-card">
                                                            <h6>Active Patients</h6>
                                                            <?php
                                                            $active_patients = mysqli_query($con,"SELECT COUNT(*) as total FROM bed_assignments WHERE status='Admitted'");
                                                            $ap = mysqli_fetch_assoc($active_patients);
                                                            echo '<h3 class="text-success">'.$ap['total'].'</h3>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-3" style="margin-bottom: 35px;">
                                                        <div class="stat-card">
                                                            <h6>Available Beds</h6>
                                                            <?php
                                                            $available_beds = mysqli_query($con,"SELECT COUNT(*) as total FROM beds WHERE status='Available'");
                                                            $ab = mysqli_fetch_assoc($available_beds);
                                                            echo '<h3 class="text-info">'.$ab['total'].'</h3>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-3" style="margin-bottom: 35px;">
                                                        <div class="stat-card">
                                                            <h6>Pending Tests</h6>
                                                            <?php
                                                            $pending_tests = mysqli_query($con,"SELECT COUNT(*) as total FROM ordered_tests WHERE status='Pending'");
                                                            $pt = mysqli_fetch_assoc($pending_tests);
                                                            echo '<h3 class="text-warning">'.$pt['total'].'</h3>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a href="add-appointment.php" class="btn btn-sm btn-block btn-success">
                                                            <i class="fas fa-plus"></i> New Appointment
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="add-patient.php" class="btn btn-sm btn-block btn-primary">
                                                            <i class="fas fa-user-plus"></i> Add Patient
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>                                       
            </div>
            
            <?php include('include/footer.php');?>                          
            <?php include('include/setting.php');?>    
            
        </div>

        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/modernizr/modernizr.js"></script>
        <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="vendor/switchery/switchery.min.js"></script>
        <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
        <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        <script src="vendor/autosize/autosize.min.js"></script>
        <script src="vendor/selectFx/classie.js"></script>
        <script src="vendor/selectFx/selectFx.js"></script>
        <script src="vendor/select2/select2.min.js"></script>
        <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
        <script src="assets/js/main.js"></script>
        <script src="assets/js/form-elements.js"></script>
        <script>
            jQuery(document).ready(function() {
                Main.init();
                FormElements.init();
            });
        </script>
</body>
</html>
<?php } ?>

