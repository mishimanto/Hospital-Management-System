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
    $orderId = mysqli_real_escape_string($con, $_GET['id']);

    // First get test order details
    $order_query = mysqli_query($con, "SELECT * FROM test_orders WHERE id = '$orderId'");
    $order_data = mysqli_fetch_assoc($order_query);

    if($order_data) {
        $userId = $order_data['user_id'];
        $paymentAmount = $order_data['total_amount'];
        $paymentStatus = $order_data['payment_status'];

        // Update test order status
        mysqli_query($con, "UPDATE test_orders SET status='Cancelled' WHERE id ='$orderId'");
        
        if(mysqli_affected_rows($con) > 0) {
            $_SESSION['msg'] = "Test order canceled !!";

            // Process refund if payment was completed
            if($paymentStatus == 'Paid' && $paymentAmount > 0) {
                // Add amount to user's wallet
                mysqli_query($con, "UPDATE users SET wallet_balance = wallet_balance + $paymentAmount WHERE id = '$userId'");
                
                // Update payment status
                mysqli_query($con, "UPDATE test_orders SET payment_status = 'cancelled' WHERE id ='$orderId'");
                
                $_SESSION['msg'] .= " Payment refunded to user wallet.";
            }

            // Get user details for email
            $user_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$userId'");
            $user_data = mysqli_fetch_assoc($user_query);

            if($user_data) {
                $email = $user_data['email'];
                $subject = "Test Order Canceled";

                $message = "
                    <h2 style='color:red;'>Test Order Canceled</h2>
                    <p>Dear <b>".htmlspecialchars($user_data['fullName'])."</b>,</p>
                    <p>Your test order has been canceled by the doctor.</p>
                    <p>We sincerely apologize for this inconvenience.</p>
                    <p>Please place a new test order at your convenience.</p>
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
            $_SESSION['msg'] = "Failed to cancel test order.";
        }
    } else {
        $_SESSION['msg'] = "Test order not found.";
    }
    
    header('Location: test-orders-history.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Test Orders</title>
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
                            <h1 class="mainTitle">Doctor | Test Orders</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Test Orders</span></li>
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
                            
                            <!-- Today's Test Orders -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Today's Test Orders</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover" id="today-orders">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th class="center">Order Number</th>
                                                <th class="hidden-xs">Patient Name</th>
                                                <th class="center">Patient ID</th>
                                                <th class="center">Test Date</th>
                                                <th class="center">Test Time</th>
                                                <th class="center">Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $today = date('Y-m-d');
                                            $sql = mysqli_query($con, "SELECT users.fullName as fname, test_orders.* FROM test_orders JOIN users ON users.id=test_orders.user_id WHERE test_orders.test_date = '$today' ORDER BY test_orders.test_time ASC");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                $cnt = 1;
                                                while($row = mysqli_fetch_array($sql)) {
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $cnt;?></td>
                                                <td class="center"><?php echo $row['order_number'];?></td>
                                                <td class="hidden-xs"><?php echo $row['fname'];?></td>
                                                <td class="center"><?php echo $row['user_id'];?></td>
                                                <td class="center"><?php echo date('d/m/Y', strtotime($row['test_date']));?></td>
                                                <td class="center"><?php echo $row['test_time'];?></td>
                                                <td class="center"><?php echo $row['total_amount'];?></td>
                                                <td>
                                                    <?php 
                                                    if($row['status'] == 'Pending') {
                                                        echo "Pending";
                                                    } elseif($row['status'] == 'Sample Collected') {
                                                        echo "Sample Collected";
                                                    } elseif($row['status'] == 'Processing') {
                                                        echo "Processing";
                                                    } elseif($row['status'] == 'Completed') {
                                                        echo "Completed";
                                                    } elseif($row['status'] == 'Cancelled') {
                                                        echo "Cancelled";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php if($row['status'] != 'Cancelled') { ?>
                                                            <a href="test-orders-history.php?id=<?php echo $row['id'];?>&cancel=update"
                                                               onClick="return confirm('Are you sure you want to cancel this test order?')"
                                                               class="btn btn-info btn-xs" title="Cancel Test Order">
                                                                Cancel
                                                            </a>
                                                        <?php } else { 
                                                            echo "Canceled";
                                                        } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php 
                                            $cnt++;
                                            } 
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>No test orders for today</td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Upcoming Test Orders -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Upcoming Test Orders</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover" id="upcoming-orders">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th class="center">Order Number</th>
                                                <th class="hidden-xs">Patient Name</th>
                                                <th class="center">Patient ID</th>
                                                <th class="center">Test Date</th>
                                                <th class="center">Test Time</th>
                                                <th class="center">Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $today = date('Y-m-d');
                                            $nextWeek = date('Y-m-d', strtotime('+7 days'));
                                            $sql = mysqli_query($con, "SELECT users.fullName as fname, test_orders.* FROM test_orders JOIN users ON users.id=test_orders.user_id WHERE test_orders.test_date > '$today' AND test_orders.test_date <= '$nextWeek' ORDER BY test_orders.test_date ASC, test_orders.test_time ASC");
                                            
                                            if(mysqli_num_rows($sql) > 0) {
                                                $cnt = 1;
                                                while($row = mysqli_fetch_array($sql)) {
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $cnt;?></td>
                                                <td class="center"><?php echo $row['order_number'];?></td>
                                                <td class="hidden-xs"><?php echo $row['fname'];?></td>
                                                <td class="center"><?php echo $row['user_id'];?></td>
                                                <td class="center"><?php echo date('d/m/Y', strtotime($row['test_date']));?></td>
                                                <td class="center"><?php echo $row['test_time'];?></td>
                                                <td class="center"><?php echo $row['total_amount'];?></td>
                                                <td>
                                                    <?php 
                                                    if($row['status'] == 'Pending') {
                                                        echo "Pending";
                                                    } elseif($row['status'] == 'Sample Collected') {
                                                        echo "Sample Collected";
                                                    } elseif($row['status'] == 'Processing') {
                                                        echo "Processing";
                                                    } elseif($row['status'] == 'Completed') {
                                                        echo "Completed";
                                                    } elseif($row['status'] == 'Cancelled') {
                                                        echo "Cancelled";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php if($row['status'] != 'Cancelled') { ?>
                                                            <a href="test-orders-history.php?id=<?php echo $row['id'];?>&cancel=update"
                                                               onClick="return confirm('Are you sure you want to cancel this test order?')"
                                                               class="btn btn-info btn-xs" title="Cancel Test Order">
                                                                Cancel
                                                            </a>
                                                        <?php } else { 
                                                            echo "Canceled";
                                                        } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php 
                                            $cnt++;
                                            } 
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>No upcoming test orders in next 7 days</td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>
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