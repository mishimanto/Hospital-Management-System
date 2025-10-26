<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediZen Hospital - Premium Healthcare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Exile&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

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
    .emergency-number {
      color: #7CFC00;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .emergency-number:hover {
      color: #008000;
      text-decoration: none;
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
    }

    .custom-login-btn:hover {
      background-color: #1E90FF;
      color: white;
      box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
      transform: translateY(-1px);
    }

    .custom-login-btn i {
      margin-right: 0.5rem;
    }
</style>

</head>
<body>


<!-- Compact Navbar with Borders -->
<nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #e3edf7; min-height: 50px;">
  <div class="container-fluid">
    <button class="navbar-toggler border border-secondary rounded py-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-1 mb-lg-0 align-items-center">
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link active py-1" href="#"><i class="fas fa-home me-2"></i>Home</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#services"><i class="fas fa-procedures me-2"></i>Services</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#doctors"><i class="fas fa-user-md me-2"></i>Doctors</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#services"><i class="fas fa-clinic-medical me-2"></i>Services</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#departments"><i class="fas fa-clinic-medical me-2"></i>Departments</a>
        </li>
        <li class="nav-item px-2" style="position: relative; padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <div class="border-end border-secondary" style="position: absolute; right: 0; top: 25%; bottom: 25%; width: 1px;"></div>
          <a class="nav-link py-1" href="#about_us"><i class="fas fa-info-circle me-2"></i>About Us</a>
        </li>
        <li class="nav-item px-2" style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
          <a class="nav-link py-1" href="#contact"><i class="fas fa-phone-alt me-2"></i>Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Active class toggle on click
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
      document.querySelectorAll('.nav-link').forEach(item => item.classList.remove('active'));
      this.classList.add('active');
    });
  });
</script>

</body>
</html>
