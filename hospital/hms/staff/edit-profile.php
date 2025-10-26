<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
} else {
    // Get technician ID from session or URL
    $tech_id = isset($_GET['id']) ? $_GET['id'] : (isset($_SESSION['tech_id']) ? $_SESSION['tech_id'] : 0);
    
    // Fetch technician data
    $query = mysqli_query($con, "SELECT * FROM lab_technicians WHERE id='$tech_id'");
    $technician = mysqli_fetch_array($query);
    
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $lab_id = $_POST['lab_id'];
        $qualification = $_POST['qualification'];
        
        // Update password only if provided
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            $sql = "UPDATE lab_technicians SET name='$name', email='$email', password='$password', address='$address', phone='$phone', lab_id='$lab_id', qualification='$qualification' WHERE id='$tech_id'";
        } else {
            $sql = "UPDATE lab_technicians SET name='$name', email='$email', address='$address', phone='$phone', lab_id='$lab_id', qualification='$qualification' WHERE id='$tech_id'";
        }
        
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            echo "<script>alert('Profile updated successfully');</script>";
            echo "<script>window.location.href ='dashboard.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lab Technician | Edit Profile</title>
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
            if (document.edittech.password.value != document.edittech.cpassword.value) {
                alert("Password and Confirm Password Field do not match !!");
                document.edittech.cpassword.focus();
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
                data: 'emailid=' + $("#email").val() + '&tech_id=<?php echo $tech_id; ?>',
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
                            <h1 class="mainTitle">Lab Technician | Edit Profile</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Lab Technician</span></li>
                            <li class="active"><span>Edit Profile</span></li>
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
                                            <h5 class="panel-title">Edit Profile</h5>
                                        </div>
                                        <div class="panel-body">

                                            <?php 
                                                $sid = $_SESSION['id'];
                                                $sql = mysqli_query($con, "SELECT * FROM lab_technicians WHERE id ='$sid'");
                                                while ($data = mysqli_fetch_array($sql)) {
                                                ?>
                                                <h4><?php echo htmlentities($data['name']); ?>'s Profile</h4>
                                                <p><b>Profile Reg. Date:</b> <?php echo htmlentities($data['creationDate']); ?></p>
                                                <?php if ($data['updationDate']) { ?>
                                                <p><b>Profile Last Updation Date:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                                <?php } ?>
                                                <hr />

                                            <form role="form" name="edittech" method="post" onSubmit="return valid();">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter Full Name" value="<?php echo htmlentities($data['name']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" value="<?php echo htmlentities($data['email']); ?>" required onBlur="checkemailAvailability()">
                                                    <span id="email-availability-status"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <textarea name="address" class="form-control" placeholder="Enter Address" required><?php echo htmlentities($data['address']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" value="<?php echo htmlentities($data['phone']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lab_id">Assigned Lab</label>
                                                    <select name="lab_id" class="form-control" required>
                                                        <option value="">Select Lab</option>
                                                        <?php 
                                                        $ret = mysqli_query($con, "select * from labs");
                                                        while ($row = mysqli_fetch_array($ret)) { 
                                                            $selected = ($row['id'] == $technician['lab_id']) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo htmlentities($row['id']); ?>" <?php echo $selected; ?>>
                                                                Lab <?php echo htmlentities($row['id']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualification">Qualification</label>
                                                    <input type="text" name="qualification" class="form-control" placeholder="Enter Qualification" value="<?php echo htmlentities($data['qualification']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">New Password (Leave blank if you don't want to change)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Enter New Password">
                                                </div>
                                                <div class="form-group">
                                                    <label for="cpassword">Confirm New Password</label>
                                                    <input type="password" name="cpassword" class="form-control" placeholder="Confirm New Password">
                                                </div>

                                                <?php } ?>
                                                <button type="submit" name="submit" class="btn btn-o btn-primary">Update</button>
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