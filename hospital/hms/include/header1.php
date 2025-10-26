<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MEDIZEN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.1);
    transform: translateY(-2px);
  }

  .nav-link.active {
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.1);
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

<!-- Top Navbar (Logo + Phone + Buttons) -->

<nav class="navbar navbar-expand-lg p-2" style="background: linear-gradient(90deg, #00b894, #00cec9);">
  <div class="container-fluid d-flex justify-content-between align-items-center">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="../index.php" style="gap: 10px; margin-left: 15px;">
      <div style="height:35px;width:35px;overflow:hidden; display:flex; align-items:center; justify-content:center;">
        <img src="hms/logo_no_bg.png" alt="MediZen Logo" style="height: 35px; width: auto;">
      </div>
      <div class="logo d-flex align-items-center; justify-content-center;" style="height: 40px;">
          <span style="font-family: 'Josefin Sans'; font-size: 30px;">Medi</span>
          <span style="color: #006400; font-family: 'Josefin Sans'; font-size: 30px;">Zen</span>
      </div>

    </a>


    <!-- Emergency Number -->
    <span style="font-size: 17px;" class="text-white fw-medium d-none d-lg-inline me-3">
      For emergency:
      <i style="margin-left: 10px;" class="fas fa-phone-alt"></i>
      <a href="tel:01949854504" style="text-decoration: none;" class="emergency-number">01949854504</a>
    </span>

    <!-- Search Bar -->
    <form class="d-flex align-items-center me-3" style="max-width: 400px;">
      <div class="input-group" style="height: 36px;">
        <input type="text" class="form-control form-control-sm border-0 ps-3" placeholder="Search..." 
               style="border-radius: 6px 0 0 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); width: 250px;">
        <button type="submit" class="btn btn-light border-0" 
                style="border-radius: 0 6px 6px 0; height: 100%; width: 60px; padding: 0;">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>


    <!-- Login Button -->
    <a href="logins.php" class="btn btn-light custom-login-btn px-3" style="border-radius: 6px; padding: 5px;">
      <i class="fas fa-sign-in-alt me-1"></i> Login
    </a>

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
