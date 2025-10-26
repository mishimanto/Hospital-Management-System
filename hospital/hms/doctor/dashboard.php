<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
  } else{

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doctor  | Dashboard</title>
		
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

		<style>
        .dashboard-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            background-color: #f8f9fc;
            text-align: center;
            padding: 15px;
            margin: 15px 0;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            padding: 20px 10px;
        }

        .card-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
            color: #fff;
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
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #343a40;
            margin-bottom: 20px;
        }

        .card-value {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 14px;
            color: #212529;
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
    </style>


	</head>
	<body>
		<div id="app">		
			<?php include('include/sidebar.php');?>

			<div class="app-content">				
				<?php include('include/header.php');?>
						
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: BASIC EXAMPLE -->
							<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<!-- Manage Doctors Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-primary">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fa fa-user-md"></i>
                                                </div>
                                                <h3 class="card-title">My Profile</h3>
                                                
                                                <p class="links cl-effect-1">
													<a href="edit-profile.php">
														Update Profile
													</a>
												</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manage Doctors Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-success">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <h3 class="card-title">My Appointments</h3>
                                                
                                                <p class="links cl-effect-1">
													<a href="appointment-history.php">
														View Appointment History
													</a>
												</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add Patient Card -->
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card dashboard-card card-info">
                                            <div class="card-body">
                                                <div class="card-icon">
                                                    <i class="fas fa-user-plus"></i>
                                                </div>
                                                <h3 class="card-title">Add Patient</h3>
                                                
                                                <p class="links cl-effect-1">
													<a href="add-patient.php">
														Add New Patient
													</a>
												</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manage Patients Card -->
									<div class="col-md-4 col-sm-6 mb-4">
									    <div class="card dashboard-card card-warning">
									        <div class="card-body">
									            <div class="card-icon">
									                <i class="fa fa-users"></i>
									            </div>
									            <h3 class="card-title">Manage Patients</h3>
									            
									            <p class="links cl-effect-1">
									                <a href="manage-patient.php">
									                    View All Patients
									                </a>
									            </p>
									        </div>
									    </div>
									</div>
								
							</div>
						</div>					
					
						<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			</div>
			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
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
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
<?php } ?>
