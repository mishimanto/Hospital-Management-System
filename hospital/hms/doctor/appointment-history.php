<?php
session_start();
error_reporting(0);
include('include/config.php');
include 'class.user.php';
$user = new USER();

if(!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

if(isset($_GET['cancel'])) {
    $appointmentId = mysqli_real_escape_string($con, $_GET['id']);

    // First get appointment details
    $appointment_query = mysqli_query($con, "SELECT * FROM appointment WHERE id = '$appointmentId'");
    $appointment_data = mysqli_fetch_assoc($appointment_query);

    if($appointment_data) {
        $userId = $appointment_data['userId'];
        $paymentAmount = $appointment_data['payment_amount'];
        $paymentStatus = $appointment_data['payment_status'];

        // Update appointment status
        mysqli_query($con, "UPDATE appointment SET doctorStatus='0' WHERE id ='$appointmentId'");
        
        if(mysqli_affected_rows($con) > 0) {
            $_SESSION['msg'] = "Appointment canceled !!";

            // Process refund if payment was completed
            if($paymentStatus == 'Paid' && $paymentAmount > 0) {
                // Add amount to user's wallet
                mysqli_query($con, "UPDATE users SET wallet_balance = wallet_balance + $paymentAmount WHERE id = '$userId'");
                
                // Update payment status
                mysqli_query($con, "UPDATE appointment SET payment_status = 'cancelled' WHERE id ='$appointmentId'");
                
                $_SESSION['msg'] .= " Payment refunded to user wallet.";
            }

            // Get user details for email
            $user_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$userId'");
            $user_data = mysqli_fetch_assoc($user_query);

            if($user_data) {
                $email = $user_data['email'];
                $subject = "Appointment Canceled";

                $message = "
                    <h2 style='color:red;'>Appointment Canceled</h2>
                    <p>Dear <b>".htmlspecialchars($user_data['fullName'])."</b>,</p>
                    <p>Your appointment has been canceled by the doctor.</p>
                    <p>We sincerely apologize for this inconvenience.</p>
                    <p>Please book a new appointment at your convenience.</p>
                ";

                if($paymentStatus == 'Paid' && $paymentAmount > 0) {
                    $message .= "<p>Your payment amount: <strong>".htmlspecialchars($paymentAmount)."</strong> has been added to your wallet balance.</p>";
                }

                $message .= "<p>Thank you for choosing our service and staying with us.</p><br><small>MEDIZEN</small>";

                if($user->sendMail($email, $message, $subject)) {
                    $_SESSION['msg'] .= " Notification email sent to patient.";
                } else {
                    $_SESSION['msg'] .= " Failed to send notification email.";
                }
            }
        } else {
            $_SESSION['msg'] = "Failed to cancel appointment.";
        }
    } else {
        $_SESSION['msg'] = "Appointment not found.";
    }
    
    header('Location: appointment-history.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Today's Appointments</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link rel="stylesheet" href="vendor/animate.css/animate.min.css">
    <link rel="stylesheet" href="vendor/perfect-scrollbar/perfect-scrollbar.min.css">
    <link rel="stylesheet" href="vendor/switchery/switchery.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
<div id="app">
    <?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Doctor | Today's Appointments</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Today's Appointments</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <p style="color:red;">
                                <?php echo htmlentities($_SESSION['msg']);?>
                                <?php echo htmlentities($_SESSION['msg']="");?>
                            </p>
                            <table class="table table-hover" id="sample-table-1">
                                <thead>
                                    <tr>
                                        <th class="center">SI No.</th>
                                        <th class="center">Appointment Number</th>
                                        <th class="hidden-xs">Patient Name</th>
                                        <th class="center">Patient ID</th>
                                        <th class="center">Specialization</th>                                        
                                        <th class="center">Appointment Date</th>
                                        <th class="center">Appointment Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $today = date('Y-m-d');
                                    $sql = mysqli_query($con, "SELECT users.fullName as fname, appointment.* FROM appointment JOIN users ON users.id=appointment.userId WHERE appointment.doctorId='".$_SESSION['id']."' AND DATE(appointment.appointmentDate) = '$today' ORDER BY appointment.appointmentDate ASC");

                                    /*$sql = mysqli_query($con, "
                                        SELECT users.fullName as fname, appointment.* 
                                        FROM appointment 
                                        JOIN users ON users.id = appointment.userId 
                                        WHERE appointment.doctorId = '".$_SESSION['id']."' 
                                        ORDER BY appointment.appointmentDate ASC
                                    ");*/

                                    
                                    if(mysqli_num_rows($sql) > 0) {
                                        $cnt = 1;
                                        while($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <tr>
                                        <td class="center"><?php echo $row['serialNumber'];?>.</td>
                                        <td class="center"><?php echo $row['appointment_number'];?></td>
                                        <td class="hidden-xs"><?php echo $row['fname'];?></td>
                                        <td class="center"><?php echo $row['userId'];?></td>
                                        <td class="center"><?php echo $row['doctorSpecialization'];?></td>                                       
                                        <td class="center"> <?php echo date('d/m/Y', strtotime($row['appointmentDate']));?></td>
                                        <td class="center"><?php echo date('h:i A', strtotime($row['appointmentTime']));?></td>
                                        <td>
                                            <?php 
                                            if(($row['userStatus']==1) && ($row['doctorStatus']==1)) {
                                                echo "Active";
                                            }
                                            if(($row['userStatus']==0) && ($row['doctorStatus']==1)) {
                                                echo "Cancel by Patient";
                                            }
                                            if(($row['userStatus']==1) && ($row['doctorStatus']==0)) {
                                                echo "Cancel by You";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1)) { ?>
                                                    <a href="appointment-history.php?id=<?php echo $row['id'];?>&cancel=update"
                                                       onClick="return confirm('Are you sure you want to cancel this appointment?')"
                                                       class="btn btn-info btn-xs" title="Cancel Appointment">
                                                        Cancel
                                                    </a>
                                                   <!--  <a href="add-patient.php" class="btn btn-success btn-xs" title="Add New Patient">
                                                        Add Patient
                                                    </a> -->
                                                <?php } else { 
                                                    echo "Canceled";
                                                } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $cnt=$cnt+1; } 
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No appointments for today</td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>
</div>

<!-- JS FILES -->
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