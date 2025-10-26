<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $lab_id = $_POST['lab_id'];
        $qualification = $_POST['qualification'];

        $sql = mysqli_query($con, "INSERT INTO lab_technicians(name, email, password, address, phone, lab_id, qualification) 
            VALUES ('$name', '$email', '$password', '$address', '$phone', '$lab_id', '$qualification')");

        if ($sql) {
            echo "<script>alert('Lab Technician added Successfully');</script>";
            echo "<script>window.location.href ='manage-lab-technicians.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Add Lab Technician</title>
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
    <script type="text/javascript">
        function valid() {
            if (document.addtech.password.value != document.addtech.cpassword.value) {
                alert("Password and Confirm Password Field do not match !!");
                document.addtech.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
    <script>
        function checkemailAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'emailid=' + $("#email").val(),
                type: "POST",
                success: function(data) {
                    $("#email-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
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
                            <h1 class="mainTitle">Admin | Add Lab Technician</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Add Lab Technician</span></li>
                        </ol>
                    </div>
                </section>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row margin-top-30">
                                <div class="col-lg-8 col-md-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">Add Lab Technician</h5>
                                        </div>
                                        <div class="panel-body">
                                            <form role="form" name="addtech" method="post" onSubmit="return valid();">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" required onBlur="checkemailAvailability()">
                                                    <span id="email-availability-status"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <textarea name="address" class="form-control" placeholder="Enter Address" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lab_id">Assigned Lab</label>
                                                    <select name="lab_id" class="form-control" required>
                                                        <option value="">Select Lab</option>
                                                        <?php 
                                                        $ret = mysqli_query($con, "select * from labs");
                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                            <option value="<?php echo htmlentities($row['id']); ?>">
                                                                Lab <?php echo htmlentities($row['id']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualification">Qualification</label>
                                                    <input type="text" name="qualification" class="form-control" placeholder="Enter Qualification" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cpassword">Confirm Password</label>
                                                    <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
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