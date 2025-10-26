<?php
session_start();
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

// Validate if order_id exists in GET request
if(!isset($_GET['order_id']) || empty($_GET['order_id'])){
    header("Location: test_orders.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order info
$order_query = "SELECT to.*, u.fullName as patient_name, u.email as patient_email
                FROM test_orders to
                JOIN users u ON to.user_id = u.id
                WHERE to.id = $order_id";
$order_result = mysqli_query($con, $order_query);

// Check if query succeeded and record exists
if(!$order_result || mysqli_num_rows($order_result) == 0){
    header("Location: test_orders.php");
    exit();
}

$order = mysqli_fetch_assoc($order_result);

// Fetch ordered tests
$tests_query = "SELECT ot.*, dt.name as test_name, dt.description, dt.normal_range
                FROM ordered_tests ot
                JOIN diagnostic_tests dt ON ot.test_id = dt.id
                WHERE ot.order_id = $order_id";
$tests_result = mysqli_query($con, $tests_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Order Details</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
</head>
<body>
<div id="app">
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>
        <div class="main-content">
            <div class="wrap-content container" id="container">

                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Order Details: <?php echo htmlspecialchars($order['order_number']); ?></h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><a href="test_orders.php">Test Orders</a></li>
                            <li class="active"><span>Details</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($order['patient_name']); ?></p>
                            <p><strong>Patient Email:</strong> <?php echo htmlspecialchars($order['patient_email']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></p>
                            <p><strong>Total Amount:</strong> ৳<?php echo number_format($order['total_amount'],2); ?></p>
                            <p><strong>Payment:</strong> 
                                <span class="badge <?php echo $order['payment_status']=='Paid' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo htmlspecialchars($order['payment_status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <h4 class="mb-3">Ordered Tests</h4>

                    <?php while($test = mysqli_fetch_assoc($tests_result)): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><?php echo htmlspecialchars($test['test_name']); ?></strong>
                            <span class="badge pull-right 
                                <?php echo $test['status']=='Completed' ? 'badge-success' :
                                            ($test['status']=='Cancelled' ? 'badge-danger' : 'badge-warning'); ?>">
                                <?php echo $test['status']; ?>
                            </span>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="update_test_status.php">
                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Description:</strong> <?php echo htmlspecialchars($test['description']); ?></p>
                                        <p><strong>Normal Range:</strong> <?php echo htmlspecialchars($test['normal_range']); ?></p>
                                        <p><strong>Price:</strong> ৳<?php echo number_format($test['price'], 2); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option <?php if($test['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                                <option <?php if($test['status']=='Sample Collected') echo 'selected'; ?>>Sample Collected</option>
                                                <option <?php if($test['status']=='Processing') echo 'selected'; ?>>Processing</option>
                                                <option <?php if($test['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                                <option <?php if($test['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Result</label>
                                            <textarea class="form-control" name="result" rows="3"><?php echo htmlspecialchars($test['result']); ?></textarea>
                                        </div>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>

                    <a href="test_orders.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>

                </div>

            </div>
        </div>
        <?php include('include/footer.php'); ?>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>

</body>
</html>
