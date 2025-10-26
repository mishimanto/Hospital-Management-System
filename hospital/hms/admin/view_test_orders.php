<?php
session_start();
include('include/config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | View Test Orders</title>

    <!-- Include CSS (from add_tests.php style) -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/plugins.css">
    <link rel="stylesheet" href="../assets/css/themes/theme-1.css" id="skin_color" />
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
                            <h1 class="mainTitle">Admin | View Test Orders</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Test Orders</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT o.*, u.fullName 
                                          FROM test_orders o
                                          JOIN users u ON o.user_id = u.id
                                          ORDER BY o.created_at DESC";
                                $result = mysqli_query($con, $query);

                                while($order = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo htmlspecialchars($order['fullName']); ?></td>
                                    <td><?php echo date('M j, Y h:i A', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 2); ?> BDT</td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($order['status']) {
                                                case 'Pending': echo 'warning'; break;
                                                case 'Sample Collected': echo 'info'; break;
                                                case 'Processing': echo 'primary'; break;
                                                case 'Completed': echo 'success'; break;
                                                case 'Cancelled': echo 'danger'; break;
                                                default: echo 'secondary';
                                            }
                                        ?>">
                                            <?php echo $order['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="test_order_details.php?id=<?php echo $order['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            View Details
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

<!-- Include JS (from add_tests.php style) -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/modernizr/modernizr.js"></script>
<script src="../vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="../vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../vendor/switchery/switchery.min.js"></script>
<script src="../vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script src="../vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="../vendor/autosize/autosize.min.js"></script>
<script src="../vendor/selectFx/classie.js"></script>
<script src="../vendor/selectFx/selectFx.js"></script>
<script src="../vendor/select2/select2.min.js"></script>
<script src="../vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="../vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/form-elements.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>

</body>
</html>
