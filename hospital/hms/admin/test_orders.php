<?php
session_start();
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

// Handle status update
if(isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $test_id = intval($_POST['test_id']);
    $status = $_POST['status'];
    $result = $_POST['result'];

    $query = "UPDATE ordered_tests 
              SET status = '$status', 
                  result = '$result',
                  completed_at = " . ($status == 'Completed' ? 'NOW()' : 'NULL') . "
              WHERE id = $test_id AND order_id = $order_id";
    mysqli_query($con, $query);

    if($status == 'Completed') {
        $check_query = "SELECT COUNT(*) as pending 
                        FROM ordered_tests 
                        WHERE order_id = $order_id AND status != 'Completed'";
        $check_result = mysqli_query($con, $check_query);
        $check = mysqli_fetch_assoc($check_result);

        if($check['pending'] == 0) {
            $update_order = "UPDATE test_orders 
                             SET status = 'Completed', 
                                 updated_at = NOW() 
                             WHERE id = $order_id";
            mysqli_query($con, $update_order);
        }
    }

    $_SESSION['success'] = "Test status updated successfully";
    header("Location: test_orders.php");
    exit();
}

// Fetch test orders
$query = "SELECT to.*, u.fullName as patient_name, 
                 COUNT(ot.id) as test_count,
                 SUM(CASE WHEN ot.status = 'Completed' THEN 1 ELSE 0 END) as completed_count
          FROM test_orders to
          JOIN users u ON to.user_id = u.id
          LEFT JOIN ordered_tests ot ON to.id = ot.order_id
          GROUP BY to.id
          ORDER BY to.created_at DESC";
$result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Test Orders</title>

    <!-- Include CSS (from add_tests.php) -->
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
                            <h1 class="mainTitle">Manage Test Orders</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Test Orders</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order #</th>
                                    <th>Patient</th>
                                    <th>Tests</th>
                                    <th>Amount</th>
                                    <th>Progress</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($order = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $order['order_number']; ?></td>
                                        <td><?php echo htmlspecialchars($order['patient_name']); ?></td>
                                        <td><?php echo $order['test_count']; ?></td>
                                        <td>৳<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" 
                                                     style="width: <?php echo ($order['completed_count'] / $order['test_count']) * 100; ?>%">
                                                    <?php echo $order['completed_count']; ?>/<?php echo $order['test_count']; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="order_details.php?order_id=<?php echo $order['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
    </div>
</div>

<!-- Include JS (from add_tests.php) -->
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
    jQuery(document).ready(function () {
        Main.init();
    });
</script>

</body>
</html>
