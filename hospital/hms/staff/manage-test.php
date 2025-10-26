<?php
session_start();
include('include/config.php');

if (isset($_POST['submit'])) {
    $ordered_test_id = intval($_POST['ordered_test_id']);
    $test_result = mysqli_real_escape_string($con, $_POST['test_result']);

    // Step 1: Get the order_id for the ordered_test
    $get_order = mysqli_query($con, "SELECT order_id FROM ordered_tests WHERE id = '$ordered_test_id'");
    $row = mysqli_fetch_assoc($get_order);
    $order_id = $row['order_id'];

    // Step 2: Update the ordered_test with the result and status
    $update = mysqli_query($con, "
        UPDATE ordered_tests
        SET result = '$test_result',
            status = 'Completed',
            completed_at = NOW()
        WHERE id = '$ordered_test_id'
    ");

    if ($update) {
        // Step 3: Check if all ordered_tests for this order are completed
        $check = mysqli_query($con, "
            SELECT COUNT(*) AS pending_count
            FROM ordered_tests
            WHERE order_id = '$order_id' AND status != 'Completed'
        ");
        $check_row = mysqli_fetch_assoc($check);

        // Step 4: If all tests are completed, update test_orders status
        if ($check_row['pending_count'] == 0) {
            mysqli_query($con, "
                UPDATE test_orders
                SET status = 'Completed', updated_at = NOW()
                WHERE id = '$order_id'
            ");
        }

        echo "<script>alert('Test result submitted successfully.');</script>";
        echo "<script>window.location.href='manage-test.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lab Technician | Submit Test Result</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,600,700|Raleway:400,500,700|Crete+Round:400italic" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet">
    <link href="vendor/select2/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f0f8ff;
            padding: 20px;
            font-size: 16px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .panel-heading h5 {
            font-weight: 600;
        }
        .form-group label {
            font-weight: 500;
        }
        .mt-4 { margin-top: 1.5rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
    </style>
</head>
<body>
<div id="app">
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>

        <div class="main-content">
            <div class="wrap-content container" id="container">
                <!-- PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Lab Technician | Submit Test Result</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Lab</span></li>
                            <li class="active"><span>Submit Test Result</span></li>
                        </ol>
                    </div>
                </section>

                <!-- CONTENT FORM -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><i class="fa fa-flask"></i> Submit Result for Lab Test</h5>
                                </div>
                                <div class="panel-body">
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label for="order_number"><i class="fa fa-hashtag"></i> Order Number</label>
                                            <select class="form-control form-control-lg" name="order_number" id="order_number" required>
                                                <option value="">-- Select Order --</option>
                                                <?php
                                                $orders = mysqli_query($con, "SELECT id, order_number FROM test_orders WHERE payment_status = 'Paid'");
                                                while ($row = mysqli_fetch_assoc($orders)) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['order_number'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div id="user_info_container" class="mt-4 mb-4"></div>
                                        <div id="test_list_container" class="mb-4"></div>

                                        <div class="form-group">
                                            <label for="test_result"><i class="fa fa-pencil-square-o"></i> Test Result</label>
                                            <textarea name="test_result" class="form-control" rows="5" required placeholder="Enter test result here..."></textarea>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" name="submit" class="btn btn-success btn-lg">
                                                <i class="fa fa-check"></i> Submit Result
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- col -->
                    </div> <!-- row -->
                </div> <!-- container -->
            </div>
        </div>

        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
    </div>
</div>

<!-- JS Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
$(document).ready(function () {
    Main.init();

    $('#order_number').change(function () {
        var orderId = $(this).val();
        if (orderId !== '') {
            $.ajax({
                url: 'fetch-order-details.php',
                type: 'POST',
                data: { order_id: orderId },
                success: function (data) {
                    var parts = data.split('---SPLIT---');
                    $('#user_info_container').html(parts[0]);
                    $('#test_list_container').html(parts[1]);
                }
            });
        } else {
            $('#user_info_container').html('');
            $('#test_list_container').html('');
        }
    });
});
</script>
</body>
</html>
