<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    $stmt = $con->prepare("UPDATE users SET fullName=?, address=?, city=?, gender=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $fname, $address, $city, $gender, $email, $_SESSION['id']);

    if ($stmt->execute()) {
        $msg = "Your profile has been updated successfully.";
    } else {
        $msg = "Error updating profile. Please try again.";
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Edit Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-header h5 {
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        textarea.form-control {
            resize: none;
            min-height: 100px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(63, 55, 201, 0.2);
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .profile-icon {
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .profile-info h4 {
            margin-bottom: 0.2rem;
            font-weight: 600;
        }
        
        .profile-info p {
            margin-bottom: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 1.5rem 0;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .alert-success {
            background-color: rgba(75, 181, 67, 0.15);
            color: var(--success-color);
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include('include/header_patient_page.php'); ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user-edit me-1"></i> Edit Profile</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Edit Profile</h5>
        </div>
        <div class="card-body">
            <?php if (isset($msg)): ?>
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlentities($msg); ?>
                </div>
            <?php endif; ?>

            <?php 
            $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
            while ($data = mysqli_fetch_array($sql)) {
            ?>

            <div class="profile-header">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                    <h4><?php echo htmlentities($data['fullName']); ?>'s Profile</h4>
                    <p><i class="fas fa-calendar-alt me-1"></i> Registered: <?php echo htmlentities($data['regDate']); ?></p>
                    <?php if ($data['updationDate']) { ?>
                        <p><i class="fas fa-sync-alt me-1"></i> Last Updated: <?php echo htmlentities($data['updationDate']); ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="divider"></div>

            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="fname" class="form-control" value="<?php echo htmlentities($data['fullName']); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?php if ($data['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                <option value="female" <?php if ($data['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                <option value="other" <?php if ($data['gender'] == 'other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                            <input type="text" name="city" class="form-control" value="<?php echo htmlentities($data['city']); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlentities($data['email']); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="address" class="form-control" rows="3"><?php echo htmlentities($data['address']); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Profile
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </form>

            <?php } ?>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>