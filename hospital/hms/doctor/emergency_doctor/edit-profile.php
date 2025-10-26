<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $docname         = $_POST['docname'];
    $doccontact      = $_POST['doccontact'];
    $docspecialization = $_POST['Doctorspecialization'];
    $docqualification = $_POST['docqualification'];
    $docshift        = $_POST['docshift'];
    $docavailable    = $_POST['docavailable'];

    $sql = mysqli_query($con, "UPDATE em_doctors SET 
        name='$docname',
        contact='$doccontact',
        specialization='$docspecialization',
        qualification='$docqualification',
        shift='$docshift',
        available='$docavailable'
        WHERE id='" . $_SESSION['id'] . "'");

    if ($sql) {
        echo "<script>alert('Emergency Doctor Details updated successfully');</script>";
        echo "<script>window.location.href='dashboard.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Emergency Doctor | Edit Profile</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet">
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
                            <h1 class="mainTitle">Emergency Doctor | Edit Profile</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Emergency Doctor</span></li>
                            <li class="active"><span>Edit Profile</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Edit Profile</h5>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $eid = $_SESSION['dlogin'];
                                    $sql = mysqli_query($con, "SELECT * FROM em_doctors WHERE email='$eid'");
                                    while ($data = mysqli_fetch_array($sql)) {
                                    ?>
                                        <h4><?php echo htmlentities($data['name']); ?>'s Profile</h4>
                                        <p><b>Created At:</b> <?php echo htmlentities($data['creationDate']); ?></p>
                                        <?php if ($data['updationDate']) { ?>
                                            <p><b>Last Updated:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                        <?php } ?>
                                        <hr />

                                        <form role="form" method="post">
                                            <div class="form-group">
                                                <label for="docname">Name</label>
                                                <input type="text" name="docname" class="form-control" value="<?php echo htmlentities($data['name']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="doccontact">Contact No</label>
                                                <input type="text" name="doccontact" class="form-control" value="<?php echo htmlentities($data['contact']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="docemail">Email</label>
                                                <input type="email" name="docemail" class="form-control" value="<?php echo htmlentities($data['email']); ?>" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="Doctorspecialization">Specialization</label>
                                                <input type="text" name="Doctorspecialization" class="form-control" value="<?php echo htmlentities($data['specialization']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="docqualification">Qualification</label>
                                                <input type="text" name="docqualification" class="form-control" value="<?php echo htmlentities($data['qualification']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="docshift">Shift Timing</label>
                                                <input type="text" name="docshift" class="form-control" value="<?php echo htmlentities($data['shift']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="docavailable">Availability</label>
                                                <select name="docavailable" class="form-control" required>
                                                    <option value="1" <?php if ($data['available'] == 1) echo "selected"; ?>>Available</option>
                                                    <option value="0" <?php if ($data['available'] == 0) echo "selected"; ?>>Not Available</option>
                                                </select>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-o btn-primary">Update</button>
                                        </form>
                                    <?php } ?>
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
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
</body>
</html>
