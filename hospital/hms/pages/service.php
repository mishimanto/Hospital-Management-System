<?php 
// Fetch ambulance count
$ambulanceQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM ambulances WHERE status = 'available'");
$ambulance = mysqli_fetch_assoc($ambulanceQuery);
$availableAmbulance = $ambulance['total'];

// Fetch doctors
$doctorQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM doctors ORDER BY id ASC");
$total_doctor = mysqli_fetch_assoc($doctorQuery);
$availableDoctor = $total_doctor['total'];

// Fetch emergency doctors
$em_doctorQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM em_doctors ORDER BY id ASC");
$em_total_doctor = mysqli_fetch_assoc($em_doctorQuery);
$em_availableDoctor = $em_total_doctor['total'];

// Count General Beds
$generalBedsQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM beds WHERE bed_type = 'General'");
$generalBedsRow = mysqli_fetch_assoc($generalBedsQuery);
$generalBeds = $generalBedsRow['total'];


// Total Lab Count
$totalLabResult = mysqli_query($con, "SELECT SUM(count) AS total FROM labs");
$totalLabRow = mysqli_fetch_assoc($totalLabResult);
$totalLabs = $totalLabRow['total'];

// Count ICU Beds
$icuBedsQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM beds WHERE bed_type = 'ICU'");
$icuBedsRow = mysqli_fetch_assoc($icuBedsQuery);
$icuBeds = $icuBedsRow['total'];

// Count NICU Beds
$nicuBedsQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM beds WHERE bed_type = 'NICU'");
$nicuBedsRow = mysqli_fetch_assoc($nicuBedsQuery);
$nicuBeds = $nicuBedsRow['total'];

// Count Tests
$testsQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM diagnostic_tests");
$tests = mysqli_fetch_assoc($testsQuery);
$total_tests = $tests['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enhanced Card Counter Slider | SwiperJS</title>
  
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4a6bff;
      --secondary-color: #ff6b6b;
      --dark-color: #2c3e50;
      --light-color: #f8f9fa;
    }
    
    body {
      margin: 0;
      font-family: 'Poppins', 'Segoe UI', sans-serif;
      background: #f5f5f5;
    }
    .container {
      padding: 80px 20px;
      max-width: 1400px;
      margin: auto;
    }
    .section-header {
      text-align: center;
      margin-bottom: 50px;
    }
    .section-header h2 {
      font-size: 2.5rem;
      color: var(--dark-color);
      margin-bottom: 15px;
      font-weight: 700;
    }
    .section-header p {
      color: #666;
      font-size: 1.1rem;
      max-width: 700px;
      margin: 0 auto;
    }
    .divider {
      width: 80px;
      height: 3px;
      background: var(--primary-color);
      margin: 20px auto;
    }
    .swiper {
      padding: 40px 0 60px;
      width: 100%;
    }
    .swiper-wrapper {
      align-items: center;
    }
    .swiper-slide {
      display: flex;
      justify-content: center;
      transition: transform 0.3s ease;
      width: auto !important;
    }
    .swiper-slide:hover {
      transform: translateY(-10px);
    }
    .single_counter {
      background: white;
      color: var(--dark-color);
      padding: 40px 25px;
      text-align: center;
      border-radius: 16px;
      width: 320px;
      height: 350px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .single_counter:hover {
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }
    .single_counter::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, #00b894, #00cec9);
    }
    .single_counter i {
      font-size: 60px;
      margin-bottom: 25px;
      background: linear-gradient(90deg, #00b894, #00cec9);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    .single_counter h2 {
      font-size: 52px;
      font-weight: 700;
      margin: 15px 0;
      color: var(--primary-color);
    }
    .single_counter h4 {
      font-size: 40px;
      font-weight: 700;
      margin: 15px 0;
      color: var(--primary-color);
    }
    .single_counter p {
      font-size: 20px;
      margin: 0;
      color: #555;
      font-weight: 500;
    }
    .swiper-button-next, .swiper-button-prev {
      width: 50px;
      height: 50px;
      background: white;
      border-radius: 50%;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      color: var(--primary-color);
      transition: all 0.3s ease;
    }
    .swiper-button-next:hover, .swiper-button-prev:hover {
      background: var(--primary-color);
      color: white;
    }
    .swiper-button-next::after, .swiper-button-prev::after {
      font-size: 20px;
      font-weight: bold;
    }
    .swiper-pagination {
      bottom: 20px !important;
    }
    .swiper-pagination-bullet {
      width: 12px;
      height: 12px;
      background: #ddd;
      opacity: 1;
      transition: all 0.3s ease;
    }
    .swiper-pagination-bullet-active {
      background: linear-gradient(90deg, #00b894, #00cec9);
      transform: scale(1.2);
    }
    
    /* Responsive Styles */
    @media (max-width: 1400px) {
      .single_counter {
        width: 300px;
        height: 320px;
      }
    }

    @media (max-width: 1200px) {
      .single_counter {
        width: 280px;
        height: 300px;
      }
    }

    @media (max-width: 992px) {
      .container {
        padding: 60px 20px;
      }
      .single_counter {
        width: 240px;
        height: 260px;
      }
      .single_counter i {
        font-size: 50px;
        margin-bottom: 20px;
      }
      .single_counter h2 {
        font-size: 46px;
      }
      .single_counter p {
        font-size: 18px;
      }
    }

    @media (max-width: 768px) {
      .section-header h2 {
        font-size: 2rem;
      }
      .section-header p {
        font-size: 1rem;
        max-width: 90%;
      }
      .single_counter {
        width: 220px;
        height: 240px;
        padding: 25px 15px;
      }
      .single_counter i {
        font-size: 45px;
      }
      .single_counter h2 {
        font-size: 40px;
      }
      .swiper-button-next, 
      .swiper-button-prev {
        width: 40px;
        height: 40px;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding: 50px 15px;
      }
      .section-header h2 {
        font-size: 1.8rem;
        margin-bottom: 10px;
      }
      .swiper {
        padding: 30px 0 50px;
      }
      .swiper-button-next, 
      .swiper-button-prev {
        display: none;
      }
      .single_counter {
        width: 100%;
        max-width: 280px;
        height: 220px;
        margin: 0 auto;
      }
      .single_counter i {
        font-size: 40px;
        margin-bottom: 15px;
      }
      .single_counter h2 {
        font-size: 36px;
        margin: 10px 0;
      }
      .single_counter p {
        font-size: 16px;
        line-height: 1.3;
      }
      .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        margin: 0 5px !important;
      }
    }

    @media (max-width: 400px) {
      .single_counter {
        height: 200px;
        padding: 20px 10px;
      }
      .single_counter i {
        font-size: 36px;
      }
      .single_counter h2 {
        font-size: 32px;
      }
    }
  </style>
</head>
<body>

<div class="container" id="services">
  <div class="section-header">
      <h2 class="text-primary">Our Services</h2>
      <p class="text-muted">Providing exceptional healthcare services with state of the art facilities and dedicated professionals</p>
      <div class="divider"></div>
  </div>
  
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">

      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-ambulance"></i>
          <h2 class="statistic-counter"><?= $availableAmbulance ?></h2>
          <p><span style="color: red;">Emergency</span> Ambulance Service</p>
        </div>
      </div>

      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-user-md"></i>
          <h2 class="statistic-counter"><?= $em_availableDoctor ?></h2>
          <p><span style="color: red;">Emergency</span> Doctors</p>
        </div>
      </div>

      <!-- General Beds Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-bed"></i>
          <h2 class="statistic-counter"><?= $generalBeds ?></h2>
          <p>General Beds</p>
        </div>
      </div>


      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-baby"></i>
          <!-- <h2 class="statistic-counter">14</h2> -->
          <h2 class="statistic-counter"><?= $nicuBeds ?></h2>
          <p>NICU Beds</p>
        </div>
      </div>

      <!-- ICU Beds Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-heartbeat"></i>
          <h2 class="statistic-counter"><?= $icuBeds ?></h2>
          <p>ICU Beds</p>
        </div>
      </div>

      
      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-user-md"></i>
          <h2 class="statistic-counter"><?= $availableDoctor ?></h2>
          <p>Specialist Doctors</p>
        </div>
      </div>
      
      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-flask"></i>
          <h2 class="statistic-counter"><?= $totalLabs ?></h2>
          <p>Research Labs</p>
        </div>
      </div>

      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-vial"></i>
          <h2 class="statistic-counter"><?= $total_tests ?></h2>
          <p>Tests</p>
        </div>
      </div>

    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>

    <!-- Navigation Buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- jQuery + Counter-Up -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

<script>
  // Initialize Swiper with enhanced settings
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 30,
    loop: true,
    grabCursor: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      dynamicBullets: true,
      dynamicMainBullets: 0
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    }
  });

  // Enhanced Counter Animation
  $(document).ready(function() {
    $('.statistic-counter').each(function() {
      $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
      }, {
        duration: 2500,
        easing: 'swing',
        step: function(now) {
          $(this).text(Math.ceil(now));
        }
      });
    });
    
    // Re-init counters when slide changes
    swiper.on('slideChange', function() {
      $('.statistic-counter').each(function() {
        if($(this).is(':visible')) {
          $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
          }, {
            duration: 5000,
            easing: 'swing',
            step: function(now) {
              $(this).text(Math.ceil(now));
            }
          });
        }
      });
    });
  });
</script>

</body>
</html>