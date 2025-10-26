<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEDIZEN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Exile&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* Base Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4f2fe 100%);
      min-height: 100vh;
      margin: 0;
    }

    /* Navbar Container */
    .navbar {
      padding-top: 0;
      padding-bottom: 0;
    }

    /* Navigation Links */
    .nav-link {
      font-weight: 600;
      color: #212529;
      padding: 0.75rem 1rem !important;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
      display: inline-flex;
      align-items: center;
    }

    .nav-link:hover {
      color: #20c997;
      font-weight: bold;
      background: rgba(32, 201, 151, 0.12);
      text-shadow: 0 0 3px rgba(32, 201, 151, 0.5);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .nav-link.active {
      color: #0d6efd;
      background: rgba(13, 110, 253, 0.1);
      font-weight: bold;
    }

    .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: -0.3rem;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 0.2rem;
      background: #20c997;
      color: #0d6efd;
      border-radius: 0.2rem;
    }

    .nav-link i {
      margin-right: 0.5rem;
      transition: color 0.3s ease;
    }

    /* Utility Classes */
    .border-secondary {
      border-color: #dee2e6 !important;
    }

    /* Emergency Number */
    .emergency-contact {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: none;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .emergency-contact:hover {
      background: rgba(255, 255, 255, 0.1);
      box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
      transform: translateY(-1px);
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
        color: #fff;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    
    .hover-underline {
        position: relative;
    }
    
    .hover-underline::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1.5px;
        bottom: -2px;
        left: 0;
        background-color: white;
        transition: width 0.3s ease;
    }
    
    .hover-underline:hover::after {
        width: 100%;
    }

    /* Login Button */
    .custom-login-btn {
      border-radius: 0.3rem;
      font-weight: 700;
      padding: 0.5rem 1.25rem;
      box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      font-size: 1.125rem;
      margin-right: 1.25rem;
      border: none;
      display: inline-flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.1);
    }

    .custom-login-btn:hover {
      color: white;
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      border-color: rgba(255, 255, 255, 0.25);
    }

    .custom-login-btn i {
      margin-right: 0.5rem;
    }

    /* Logout Button */
    .custom-logout-btn {
      border-radius: 0.3rem;
      font-weight: 700;
      padding: 0.5rem 1.25rem;
      box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      font-size: 1.125rem;
      margin-right: 1.25rem;
      border: none;
      display: inline-flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.1);
    }

    .custom-logout-btn:hover {
      color: white;
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      border-color: rgba(255, 255, 255, 0.25);
    }

    .custom-logout-btn i {
      margin-right: 0.5rem;
    }

    /* Top navbar specific styles */
    .top-navbar {
      background: linear-gradient(90deg, #00b894, #00cec9);
    }

    /* Main navbar specific styles */
    .main-navbar {
      background-color: #e3edf7;
      min-height: 50px;
    }

    /* Logo styles */
    .logo-text {
      font-family: 'Josefin Sans';
      font-size: 30px;
    }
    .highlight {
      background-color: yellow;
      color: black;
      font-weight: bold;
      padding: 0 2px;
      border-radius: 3px;
    }

    /* Mobile specific styles */
    @media (max-width: 992px) {
      .emergency-contact {
          font-size: 14px;
          padding: 8px 16px;
          backdrop-filter: blur(8px);
          -webkit-backdrop-filter: blur(8px);
      }
      
      .logo-text {
        font-size: 24px;
      }
      
      .custom-login-btn {
        padding: 0.4rem 1rem;
        font-size: 1rem;
        margin-right: 0.5rem;
      }

      .custom-logout-btn {
        padding: 0.4rem 1rem;
        font-size: 1rem;
        margin-right: 0.5rem;
      }
      
      .top-navbar .container-fluid {
        flex-wrap: wrap;
        padding: 0.5rem 1rem;
      }
      
      .search-container {
        order: 3;
        width: 100%;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
      }
      
      .search-container .input-group {
        max-width: 100% !important;
      }
      
      .emergency-contact {
        margin: 0.5rem 0;
      }
      
      .main-navbar .navbar-nav {
        padding: 1rem 0;
      }
      
      .main-navbar .nav-item {
        padding: 0.5rem 1rem;
      }
      
      .main-navbar .nav-item .border-end {
        display: none;
      }
      
      .main-navbar .nav-link {
        padding: 0.5rem !important;
      }
      
      .main-navbar .nav-link i {
        margin-right: 0.5rem;
        width: 20px;
        text-align: center;
      }
    }

    @media (max-width: 576px) {
      .logo-text {
        font-size: 20px;
      }
      
      .emergency-contact {
        font-size: 12px;
        padding: 6px 12px;
      }
      
      .custom-login-btn {
        padding: 0.3rem 0.8rem;
        font-size: 0.9rem;
      }
      .custom-logout-btn {
        padding: 0.3rem 0.8rem;
        font-size: 0.9rem;
      }
      
      .top-navbar .navbar-brand {
        margin-left: 0;
      }
    }

    .user-dropdown {
      border: none;
      background: transparent;
      padding: 0.25rem 0.5rem;
      box-shadow: none;

    }
    .user-dropdown:after {
      display: none; /* Removes default dropdown caret */
    }
    .user-dropdown:focus {
      box-shadow: none;
    }
  </style>
</head>

<body>

<!-- Top Navbar (Logo + Phone + Buttons) -->
<nav class="navbar navbar-expand-lg p-2 top-navbar">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="index.php" style="gap: 10px;">
      <div style="height:35px;width:35px;overflow:hidden; display:flex; align-items:center; justify-content:center;">
        <img src="hms/logo_no_bg.png" alt="MediZen Logo" style="height: 35px; width: auto;">
      </div>
      <div class="logo d-flex align-items-center justify-content-center" style="height: 40px;">
          <span class="logo-text">MEDI</span>
          <span class="logo-text" style="color: #006400;">ZEN</span>
      </div>
    </a>

    <!-- Emergency Number - Hidden on smallest screens -->
    <span class="emergency-contact d-none d-sm-flex align-items-center px-3 px-lg-4 py-2 rounded-pill text-dark fw-medium">
      <?php
          $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
          while ($row = mysqli_fetch_array($ret)) {
      ?>
          <i class="fas fa-phone-alt text-dark me-2 pulse-animation"></i> 
          <span class="d-none d-md-inline me-2">Emergency:</span> 
          <a href="tel:<?php echo $row['MobileNumber']; ?>" class="text-dark text-decoration-none fw-semibold hover-underline">
              <?php echo $row['MobileNumber']; ?>
          </a>
      <?php } ?>
    </span>

    <!-- Search Bar - Will wrap on mobile -->
    <!-- <div class="search-container">
      <form class="d-flex align-items-center me-lg-3">
        <div class="input-group" style="height: 36px;">
          <input type="text" id="text" class="form-control form-control-sm border-0 ps-3" placeholder="Search..." 
                 style="border-radius: 10px 0 0 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
          <button type="submit" id="search" class="btn btn-light border-0" 
                  style="border-radius: 0 10px 10px 0; height: 100%; width: 40px; padding: 0;">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div> -->

    <?php
    $userName = 'User'; // Default fallback
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
        
        // Query to fetch name
        $query = mysqli_query($con, "SELECT * FROM users WHERE id = '$userId' LIMIT 1");
        if ($row = mysqli_fetch_assoc($query)) {
            $userName = htmlspecialchars($row['fullName']);
        }
    }
    ?>

    <!-- User Authentication Section -->
    <?php if (isset($_SESSION['id'])): ?>
      <div class="user-auth-container d-flex align-items-center position-relative">
        <span class="username-display fw-semibold text-capitalize text-truncate" style="max-width: 150px;">
          <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
        </span>
        
        <div class="dropdown">
          <button class="btn dropdown-toggle user-dropdown" type="button" id="userDropdown" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle fs-5 text-primary"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="position: absolute; right: 0; left: auto;">
            <li>
              <a class="dropdown-item" href="hms/dashboard.php">
                <i class="fas fa-user me-2"></i> Profile
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item text-danger" href="hms/logout_home.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    <?php else: ?>
      <a href="hms/user-login.php" class="btn custom-login-btn px-3" title="Login">
        <i class="fas fa-sign-in-alt me-1"></i> 
        <span class="d-none d-sm-inline">Login</span>
      </a>
    <?php endif; ?>

  </div>
</nav>

<!-- Main Navigation Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm main-navbar">
  <div class="container-fluid">
    <button class="navbar-toggler border border-secondary rounded py-1" 
        type="button" 
        data-toggle="collapse" 
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" 
        aria-expanded="false" 
        aria-label="Toggle navigation">
  <i class="fas fa-bars"></i>
</button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-1 mb-lg-0 align-items-center">
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link active py-1" href="#"><i class="fas fa-home me-2"></i>Home</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#services"><i class="fas fa-procedures me-2"></i>Services</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="hms/doctor_list.php"><i class="fas fa-user-md me-2"></i>Doctors</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="hms/emergency.php"><i class="fa-solid fa-truck-medical me-2"></i>Emergency</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="hms/pages/full_pages/all_departments.php"><i class="fas fa-clinic-medical me-2"></i>Departments</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="hms/test_list.php"><i class="fas fa-flask"></i>Test</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary d-none d-lg-block" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#about_us"><i class="fas fa-info-circle me-2"></i>About Us</a>
        </li>
        
        <li class="nav-item px-2" style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <a class="nav-link py-1" href="#contact_us"><i class="fas fa-phone-alt me-2"></i>Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Navbar Active Class Toggle & Collapse Hide on Click (Mobile)
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
      document.querySelectorAll('.nav-link').forEach(item => item.classList.remove('active'));
      this.classList.add('active');

      // Mobile navbar close on link click
      const navbarCollapse = document.querySelector('.navbar-collapse');
      if (navbarCollapse.classList.contains('show')) {
        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
        bsCollapse.hide();
      }
    });
  });

  
</script>


</body>
</html>