<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
    header('location:logout.php');
} else {
    if(isset($_GET['del'])) {
        $uid=$_GET['id'];
        mysqli_query($con,"delete from users where id ='$uid'");
        $_SESSION['msg']="User deleted successfully!";
        header('location:manage-users.php');
        exit();
    }
    
    if(isset($_GET['block'])) {
        $uid=$_GET['id'];
        mysqli_query($con,"update users set status='blocked' where id='$uid'");
        $_SESSION['msg']="User blocked successfully!";
        header('location:manage-users.php');
        exit();
    }
    
    if(isset($_GET['unblock'])) {
        $uid=$_GET['id'];
        mysqli_query($con,"update users set status='active' where id='$uid'");
        $_SESSION['msg']="User unblocked successfully!";
        header('location:manage-users.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin | Manage Users</title>
        
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
            .action-btn {
                margin: 2px;
                padding: 5px 8px;
            }
            .status-active {
                background-color: #2ecc71;
                color: white;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
            }
            .status-blocked {
                background-color: #e74c3c;
                color: white;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
            }
            .dropdown-actions {
                min-width: 120px;
            }
            .dropdown-actions i {
                margin-right: 5px;
                width: 15px;
                text-align: center;
            }
        </style>
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
                                    <h1 class="mainTitle">Admin | Manage Users</h1>
                                </div>
                                <ol class="breadcrumb">
                                    <li>
                                        <span>Admin</span>
                                    </li>
                                    <li class="active">
                                        <span>Manage Users</span>
                                    </li>
                                </ol>
                            </div>
                        </section>

                        <div class="container-fluid container-fullw bg-white">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Users</span></h5>
                                    <?php if(isset($_SESSION['msg'])) { ?>
                                        <div class="alert alert-<?php echo strpos($_SESSION['msg'], 'success') !== false ? 'success' : 'info' ?> alert-dismissible fade in">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?>
                                        </div>
                                    <?php } ?>
                                    <table class="table table-hover" id="sample-table-1">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Full Name</th>
                                                <th class="hidden-xs">Address</th>
                                                <th>City</th>
                                                <th>Gender</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Creation Date</th>
                                                <th>Updation Date</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql=mysqli_query($con,"select * from users");
                                            $cnt=1;
                                            while($row=mysqli_fetch_array($sql))
                                            {
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $cnt;?>.</td>
                                                <td class="hidden-xs"><?php echo $row['fullName'];?></td>
                                                <td><?php echo $row['address'];?></td>
                                                <td><?php echo $row['city'];?></td>
                                                <td><?php echo $row['gender'];?></td>
                                                <td><?php echo $row['email'];?></td>
                                                <td>
                                                    <?php if($row['status']=='active') { ?>
                                                        <span class="status-active">Active</span>
                                                    <?php } else { ?>
                                                        <span class="status-blocked">Blocked</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row['regDate'];?></td>
                                                <td><?php echo $row['updationDate'];?></td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php if($row['status']=='active') { ?>
                                                            <a href="manage-users.php?id=<?php echo $row['id']?>&block=block" onClick="return confirm('Are you sure you want to block this user?')" class="btn btn-warning btn-xs action-btn" title="Block User">
                                                                <i class="fa fa-ban"></i> Block
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="manage-users.php?id=<?php echo $row['id']?>&unblock=unblock" onClick="return confirm('Are you sure you want to unblock this user?')" class="btn btn-success btn-xs action-btn" title="Unblock User">
                                                                <i class="fa fa-check-circle"></i> Unblock
                                                            </a>
                                                        <?php } ?>
                                                        <a href="manage-users.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-xs action-btn" title="Delete User">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                    <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                <i class="fa fa-cog"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right dropdown-actions" role="menu">
                                                                <?php if($row['status']=='active') { ?>
                                                                    <li>
                                                                        <a href="manage-users.php?id=<?php echo $row['id']?>&block=block" onClick="return confirm('Are you sure you want to block this user?')">
                                                                            <i class="fa fa-ban text-warning"></i> Block User
                                                                        </a>
                                                                    </li>
                                                                <?php } else { ?>
                                                                    <li>
                                                                        <a href="manage-users.php?id=<?php echo $row['id']?>&unblock=unblock" onClick="return confirm('Are you sure you want to unblock this user?')">
                                                                            <i class="fa fa-check-circle text-success"></i> Unblock User
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <a href="manage-users.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this user?')">
                                                                        <i class="fa fa-trash text-danger"></i> Delete User
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php 
                                            $cnt=$cnt+1;
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
                
                // Auto-dismiss alerts after 3 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 3000);
            });
        </script>
    </body>
</html>
<?php } ?>