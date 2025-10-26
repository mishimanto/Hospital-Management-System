<?php
session_start();
error_reporting(0);
include('include/config.php');

if(strlen($_SESSION['id'])==0){
    header('location:logout.php');
} else {

$did = intval($_GET['id']); // Get doctor id

// Update doctor details
if(isset($_POST['submit'])) {
    $docspecialization = $_POST['Doctorspecialization'];
    $docname = $_POST['docname'];
    $docaddress = $_POST['clinicaddress'];
    $docfees = $_POST['docfees'];
    $doccontactno = $_POST['doccontact'];
    $docemail = $_POST['docemail'];
    $visiting_start_time = $_POST['visiting_start_time'];
    $visiting_end_time = $_POST['visiting_end_time'];

    $sql = mysqli_query($con,"UPDATE doctors SET 
        specilization='$docspecialization',
        doctorName='$docname',
        address='$docaddress',
        docFees='$docfees',
        contactno='$doccontactno',
        docEmail='$docemail',
        visiting_start_time='$visiting_start_time',
        visiting_end_time='$visiting_end_time'
        WHERE id='$did'");

    if ($sql) {
            echo "<script>
                alert('Doctor Details updated Successfully');
                window.location.href = 'manage-doctors.php';
            </script>";
            exit();
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Doctor Details</title>

    <!-- CSS links -->
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

            <!-- PAGE TITLE -->
            <section id="page-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h1 class="mainTitle">Admin | Edit Doctor Details</h1>
                    </div>
                    <ol class="breadcrumb">
                        <li><span>Admin</span></li>
                        <li class="active"><span>Edit Doctor Details</span></li>
                    </ol>
                </div>
            </section>

            <!-- FORM SECTION -->
            <div class="container-fluid container-fullw bg-white">
                <div class="row">
                    <div class="col-md-12">
                        <h5 style="color: green; font-size:18px;">
                            <?php if($msg){ echo htmlentities($msg); } ?>
                        </h5>

                        <div class="row margin-top-30">
                            <div class="col-lg-8 col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Edit Doctor Info</h5>
                                    </div>
                                    <div class="panel-body">

                                    <?php
                                    $sql = mysqli_query($con,"SELECT * FROM doctors WHERE id='$did'");
                                    while($data = mysqli_fetch_array($sql)){
                                    ?>
                                    <h4><?php echo htmlentities($data['doctorName']); ?>'s Profile</h4>
                                    <p><b>Profile Reg. Date:</b> <?php echo htmlentities($data['creationDate']); ?></p>
                                    <?php if($data['updationDate']){ ?>
                                    <p><b>Last Updated:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                    <?php } ?>
                                    <hr />

                                    <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                        <div class="form-group">
                                            <label for="DoctorSpecialization">Doctor Specialization</label>
                                            <select name="Doctorspecialization" class="form-control" required>
                                                <option value="<?php echo htmlentities($data['specilization']); ?>">
                                                    <?php echo htmlentities($data['specilization']); ?>
                                                </option>
                                                    <?php
                                                    $ret = mysqli_query($con,"SELECT * FROM doctorspecilization");
                                                    while($row = mysqli_fetch_array($ret)){
                                                    ?>
                                                <option value="<?php echo htmlentities($row['specilization']); ?>">
                                                    <?php echo htmlentities($row['specilization']); ?>
                                                </option>
                                                    <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="doctorname">Doctor Name</label>
                                            <input type="text" name="docname" class="form-control" value="<?php echo htmlentities($data['doctorName']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Clinic Address</label>
                                            <textarea name="clinicaddress" class="form-control" required><?php echo htmlentities($data['address']); ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="fees">Consultancy Fees</label>
                                            <input type="text" name="docfees" class="form-control" value="<?php echo htmlentities($data['docFees']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="contact">Contact No</label>
                                            <input type="text" name="doccontact" class="form-control" value="<?php echo htmlentities($data['contactno']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Doctor Email</label>
                                            <input type="email" name="docemail" class="form-control" value="<?php echo htmlentities($data['docEmail']); ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="visiting_start_time">Visiting Start Time</label>
                                            <input type="time" name="visiting_start_time" class="form-control" value="<?php echo htmlentities($data['visiting_start_time']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="visiting_end_time">Visiting End Time</label>
                                            <input type="time" name="visiting_end_time" class="form-control" value="<?php echo htmlentities($data['visiting_end_time']); ?>" required>
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
            <!-- END FORM SECTION -->

        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
<?php include('include/setting.php'); ?>

</div>

<!-- JS Scripts -->
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