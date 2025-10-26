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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --card-bg: #ffffff;
            --body-bg: #f5f7fb;
            --text-color: #2b2d42;
            --muted-color: #8d99ae;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        .profile-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background-color: var(--card-bg);
            transition: transform 0.3s ease;
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .profile-header {
            background: linear-gradient(90deg, #00b894, #00cec9);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .profile-icon {
            width: 80px;
            height: 80px;
            background: white;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 32px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .profile-body {
            padding: 2.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 18px;
            transition: all 0.3s;
            background-color: #f8fafc;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
            background-color: #fff;
        }
        
        .input-group-text {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-outline-secondary
        {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover, .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(63, 55, 201, 0.3);
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.1), transparent);
            margin: 2rem 0;
            border: none;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .alert-success {
            background-color: rgba(75, 181, 67, 0.15);
            color: var(--success-color);
            border: none;
            border-radius: 10px;
        }
        
        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
            100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
        }
        .pulse {
		  animation: pulse 3s infinite;
		}
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="profile-card fade-in pulse">
        <div class="profile-header">
            <div class="profile-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <?php 
            $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
            while ($data = mysqli_fetch_array($sql)) {
            ?>
            <h3 style="margin-bottom: 30px;">Edit <span style="color: #383838;"><?php echo htmlentities($data['fullName']); ?></span>'s Profile</h3>
         	<p><i class="fas fa-calendar-alt me-1 mr-3"></i><span style="color: black;">Registered:</span> <?php echo htmlentities($data['regDate']); ?>

        	<?php if ($data['updationDate']) { ?>
            <span style="margin-left: 10px;"><i class="fas fa-sync-alt me-1"></i><span style="color: black;">Last Updated:</span> <?php echo htmlentities($data['updationDate']); ?></span>
        	</p>
                <?php } ?>
        </div>
        
        <div class="profile-body">
            <?php if(isset($msg)): ?>
                <div class="alert alert-success mb-4 slide-up">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $msg; ?>
                </div>
            <?php endif; ?>           
            
            <form method="post" action="">
                <div class="row mb-4 slide-up" style="animation-delay: 0.1s">
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
                
                <div class="row mb-4 slide-up" style="animation-delay: 0.2s">
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
                
                <div class="mb-4 slide-up" style="animation-delay: 0.3s">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="address" class="form-control" rows="3"><?php echo htmlentities($data['address']); ?></textarea>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4 slide-up" style="animation-delay: 0.4s">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary d-flex justify-content-center align-items-center">
					    Cancel
					</a>

                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effects to form inputs
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.boxShadow = '0 0 0 3px rgba(67, 97, 238, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.boxShadow = 'none';
            });
        });
    });
</script>

</body>
</html>