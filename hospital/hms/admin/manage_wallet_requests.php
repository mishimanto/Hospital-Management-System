<?php
session_start();
error_reporting(0);
include('include/config.php');

if(strlen($_SESSION['id'])==0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Wallet Requests</title>
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
    <style>
        .wallet-request-card {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            margin-bottom: 15px;
            background: #fff;
            border-left: 4px solid #f1f1f1;
            transition: all 0.3s ease;
            width: 50%;
        }
        .wallet-request-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left-color: #20c997;
        }
        .wallet-request-card.pending {
            border-left-color: #FFC107;
        }
        .wallet-request-card.approved {
            border-left-color: #28a745;
        }
        .wallet-request-card.rejected {
            border-left-color: #dc3545;
        }
        .request-details {
            padding: 15px;
        }
        .request-amount {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .request-user {
            font-size: 15px;
            color: #7f8c8d;
        }
        .request-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }
        .status-approved {
            background-color: #D4EDDA;
            color: #155724;
        }
        .status-rejected {
            background-color: #F8D7DA;
            color: #721C24;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .no-requests {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
            font-size: 16px;
        }
        .section-title {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 20px;
            font-weight: bold;
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
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | Manage Wallet Requests</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Manage Wallet Requests</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="section-title">Wallet Requests Management</h5>
                            <div class="wallet-requests-container">
                                <?php
                                $result = mysqli_query($con, "SELECT * FROM wallet_requests WHERE status IN ('Pending', 'Approved') ORDER BY requested_at DESC");


                                $request_count = mysqli_num_rows($result);

                                if($request_count == 0) {
                                    echo '<div class="no-requests">';
                                    echo '<i class="fa fa-money fa-3x" style="color:#ddd; margin-bottom:15px;"></i>';
                                    echo '<p>No wallet requests found</p>';
                                    echo '</div>';
                                } else {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $status_class = strtolower($row['status']);
                                        echo '<div class="wallet-request-card '.$status_class.'">';
                                        echo '<div class="request-details">';
                                        echo '<div class="row">';
                                        echo '<div class="col-md-6">';
                                        echo '<div class="request-user">';
                                        echo '<strong>User ID:</strong> '.htmlentities($row['user_id']).'<br>';
                                        echo '<strong>Name:</strong> '.htmlentities($row['userName']).'<br>';
                                        echo '<strong>Tnx ID:</strong> '.htmlentities($row['tnx_id']).'<br>';
                                        echo '<strong>Requested Time:</strong> '.htmlentities($row['requested_at']);
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="col-md-6 text-right">';
                                        echo '<div class="request-amount">'.htmlentities($row['amount']).'</div>';
                                        echo '<span class="request-status status-'.$status_class.'">'.htmlentities($row['status']).'</span>';
                                        echo '</div>';
                                        echo '</div>';

                                        if($row['status']=='Pending') {
                                            echo '<div class="action-buttons text-right">';
                                            echo '<a href="approve_wallet_request.php?id='.urlencode($row['id']).'" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure to approve this request?\')">';
                                            echo '<i class="fa fa-check"></i> Approve';
                                            echo '</a> ';
                                            echo '<a href="reject_wallet_request.php?id='.urlencode($row['id']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure to reject this request?\')">';
                                            echo '<i class="fa fa-times"></i> Reject';
                                            echo '</a>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php include('include/footer.php'); ?>
    <?php include('include/setting.php'); ?>
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
