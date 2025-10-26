<?php
session_start();
error_reporting(0);
include('include/config.php');

if(strlen($_SESSION['id'])==0){
    header('location:logout.php');
} else {
    $techid = intval($_GET['id']); // Get technician id

    // Update technician details
    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $lab_id = $_POST['lab_id'];
        $qualification = $_POST['qualification'];

        $sql = mysqli_query($con,"UPDATE lab_technicians SET 
            name='$name',
            address='$address',
            phone='$phone',
            lab_id='$lab_id',
            qualification='$qualification'
            WHERE id='$techid'");

        if ($sql) {
            echo "<script>
                alert('Lab Technician Details updated Successfully');
                window.location.href = 'manage-lab-technicians.php';
            </script>";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Lab Technician Details</title>
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
                            <h1 class="mainTitle">Admin | Edit Lab Technician Details</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Edit Lab Technician Details</span></li>
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
                                            <h5 class="panel-title">Edit Lab Technician Info</h5>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            $sql = mysqli_query($con,"SELECT * FROM lab_technicians WHERE id='$techid'");
                                            while($data = mysqli_fetch_array($sql)){
                                            ?>
                                            <h4><?php echo htmlentities($data['name']); ?>'s Profile</h4>
                                            <p><b>Profile Reg. Date:</b> <?php echo htmlentities($data['creationDate']); ?></p>
                                            <?php if($data['updationDate']){ ?>
                                            <p><b>Last Updated:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                            <?php } ?>
                                            <hr />
                                            <form role="form" name="edittech" method="post">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo htmlentities($data['name']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?php echo htmlentities($data['email']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <textarea name="address" class="form-control" required><?php echo htmlentities($data['address']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlentities($data['phone']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lab_id">Assigned Lab</label>
                                                    <select name="lab_id" class="form-control" required>
                                                        <option value="<?php echo htmlentities($data['lab_id']); ?>">
                                                            Lab <?php echo htmlentities($data['lab_id']); ?>
                                                        </option>
                                                        <?php
                                                        $ret = mysqli_query($con,"SELECT * FROM labs");
                                                        while($row = mysqli_fetch_array($ret)){
                                                        ?>
                                                        <option value="<?php echo htmlentities($row['id']); ?>">
                                                            Lab <?php echo htmlentities($row['id']); ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualification">Qualification</label>
                                                    <input type="text" name="qualification" class="form-control" value="<?php echo htmlentities($data['qualification']); ?>" required>
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