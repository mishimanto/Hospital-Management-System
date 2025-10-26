<?php
session_start();
include_once('hms/include/config.php');
if(isset($_POST['submit']))
{
$name=$_POST['fullname'];
$email=$_POST['emailid'];
$mobileno=$_POST['mobileno'];
$dscrption=$_POST['description'];
$query=mysqli_query($con,"insert into tblcontactus(fullname,email,contactno,message) value('$name','$email','$mobileno','$dscrption')");
echo "<script>alert('Your information succesfully submitted');</script>";
echo "<script>window.location.href ='index.php'</script>";

} ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MEDIZEN</title>

    <link rel="icon" href="assets/images/logo_no_bg.png?v=1.1" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawsom-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .custom-input {
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 10px 18px;
            font-size: ;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-input:focus {
            border-color: #009688;
            box-shadow: 0 0 6px #009688;
        }

        textarea.custom-input {
            resize: vertical;
            padding: 10px 18px;
            min-height: 120px;
        }
        .key-card {
          background: #ffffff;
          border-radius: 10px; 
          box-shadow: 0 4px 12px rgba(0,0,0,0.08);
          width: 300px;
          height: 300px;
          padding: 40px 20px;
          text-align: center;
          transition: all 0.4s ease;
          cursor: pointer;
          border: 1px solid #f0f0f0;
        }

        .key-card i {
          font-size: 40px;
          color: #28a745;
          margin-top: 20px;
          transition: all 0.4s ease;
        }

        .key-card h5 {
          font-size: 20px;
          font-weight: 600;
          color: #333;
          margin-top: 10px;
        }

        .key-card:hover {
          transform: translateY(-8px);
          box-shadow: 0 12px 24px rgba(0,0,0,0.15);
          border-color: #28a745;
        }

        .key-card:hover i {
          color: #fff;
          background: #28a745;
          padding: 15px;
          border-radius: 50%;
          transition: all 0.4s ease;
        }

        /* Floating Appointment Button Styles */
        .floating-appointment-btn {
            position: absolute;
            margin-left: 50%; /* sidebar width */
            transform: translateX(-50%);
            z-index: 999;
            margin-top: -40px;
            background-color: #28a745;
            color: #fff;
            padding: 12px 26px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .floating-appointment-btn .btn {
            padding: 15px 25px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background: linear-gradient(45deg, #28a745, #218838);
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .floating-appointment-btn .btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #218838, #28a745);
        }
        
        .floating-appointment-btn .btn i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .floating-appointment-btn {
                margin-top: -28px;
                transform: translateX(-50%) scale(0.85);
            }
            
            .floating-appointment-btn .btn {
                padding: 5px 12px;
                font-size: 12px;
                min-width: 120px;
                height: 32px;
                border-radius: 25px;
            }
            
            .floating-appointment-btn .btn i {
                font-size: 12px;
                margin-right: 4px;
            }
            
            /* Mobile Touch Feedback */
            .floating-appointment-btn .btn:active {
                transform: translateY(-1px) scale(0.95);
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                background: linear-gradient(45deg, #218838, #28a745);
            }
        }

        @media (max-width: 480px) {
            .floating-appointment-btn {
                transform: translateX(-50%) scale(0.8);
            }
            
            .floating-appointment-btn .btn {
                padding: 4px 10px;
                font-size: 11px;
                min-width: 110px;
                height: 30px;
            }
            
            .floating-appointment-btn .btn i {
                font-size: 11px;
                margin-right: 3px;
            }
        }

        .hover-shadow {
          transition: all 0.3s ease;
          cursor: pointer;
        }

        .hover-shadow:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
          border-color: #28a745 !important;
        }

        .text-color
        {
            color: #00b894;
        }

        .bg-color
        {
            background: linear-gradient(90deg, #00b894, #00cec9);
        }


    </style>
</head>

<body>

    <!-- Header Starts Here -->
    <?php include_once 'hms/include/header.php'; ?>
    
    <!-- Slider Starts Here -->
    <?php include_once 'hms/include/sidebar.php'; ?>

    <!-- Floating Appointment Button -->
<div class="floating-appointment-btn">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bookingModal">
        <i class="fas fa-calendar-check"></i> Booking Services 
    </button>
</div>


    <!-- Unique Booking Modal -->
<div class="modal fade booking-modal" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0" style="overflow: hidden;">
      <!-- Animated Gradient Header -->
      <div class="modal-header position-relative p-3" style="
          background: linear-gradient(90deg, #00b894, #00cec9);
          background-size: 200% 200%;
          animation: gradientBG 8s ease infinite;
        ">
        <!-- <div class="position-absolute top-0 end-0 p-3">
          <button type="button" class="btn-close btn-close-white opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> -->
        <div class="text-center w-100">
          <h5 class="modal-title fw-bold text-white" id="bookingModalLabel">
            <i class="fas fa-calendar-check me-2"></i> Book a Service
          </h5>
         <!--  <p class="text-white-50 small mb-0">Select your required healthcare service</p> -->
        </div>
      </div>

      <div class="modal-body p-4">
        <div class="row g-4">
          <!-- Doctor Appointment -->
          <div class="col-md-6">
            <div class="service-card-3d rounded-4 h-100" onclick="window.location.href='hms/book-appointment.php'">
              <div class="service-icon">
                <i class="fas fa-user-md"></i>
              </div>
              <h4>Doctor Appointment</h4>
              <!-- <div class="service-hover-content">
                <p>Book an appointment with our specialist doctors</p>
                <span class="service-cta">Book Now <i class="fas fa-arrow-right"></i></span>
              </div> -->
            </div>
          </div>

          <!-- Diagnostic Tests -->
          <div class="col-md-6">
            <div class="service-card-3d rounded-4 h-100" onclick="window.location.href='hms/book_test.php'">
              <div class="service-icon">
                <i class="fas fa-vial"></i>
              </div>
              <h4>Diagnostic Tests</h4>
              <!-- <div class="service-hover-content">
                <p>Schedule diagnostic tests and lab procedures</p>
                <span class="service-cta">Book Now <i class="fas fa-arrow-right"></i></span>
              </div> -->
            </div>
          </div>

          <!-- Ambulance Service -->
          <div class="col-md-6">
            <div class="service-card-3d rounded-4 h-100" onclick="window.location.href='hms/book_ambulance.php'">
              <div class="service-icon">
                <i class="fas fa-ambulance"></i>
              </div>
              <h4>Ambulance</h4>
              <!-- <div class="service-hover-content">
                <p>Emergency medical transport services</p>
                <span class="service-cta">Book Now <i class="fas fa-arrow-right"></i></span>
              </div> -->
            </div>
          </div>

          <!-- Bed Admission -->
          <div class="col-md-6">
            <div class="service-card-3d rounded-4 h-100" onclick="window.location.href='hms/book_bed.php'">
              <div class="service-icon">
                <i class="fas fa-procedures"></i>
              </div>
              <h4>Beds</h4>
              <!-- <div class="service-hover-content">
                <p>Request admission to our facilities</p>
                <span class="service-cta">Book Now <i class="fas fa-arrow-right"></i></span>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Animated Gradient Background */
  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* 3D Service Card Design */
  .service-card-3d {
    position: relative;
    background: white;
    border: 1px solid rgba(0,0,0,0.1);
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    perspective: 1000px;
    transform-style: preserve-3d;
    overflow: hidden;
  }

  .service-card-3d:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 30px rgba(40, 167, 69, 0.2);
    border-color: rgba(40, 167, 69, 0.3);
  }

  .service-card-3d .service-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #f0f9ff, #e6f7ed);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease;
  }

  .service-card-3d .service-icon i {
    font-size: 32px;
    color: #00b894;
    transition: all 0.4s ease;
  }

  .service-card-3d:hover .service-icon {
    background: linear-gradient(90deg, #00b894, #00cec9);
    transform: rotateY(180deg);
  }

  .service-card-3d:hover .service-icon i {
    color: white;
    transform: rotateY(-180deg);
  }

  .service-card-3d h4 {
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    position: relative;
    padding-bottom: 10px;
  }

  .service-card-3d h4:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 2px;
    background: #28a745;
    transition: all 0.4s ease;
  }

  .service-card-3d:hover h4:after {
    width: 80px;
    background: #218838;
  }

  .service-card-3d .service-hover-content {
    height: 0;
    overflow: hidden;
    transition: all 0.4s ease;
    opacity: 0;
  }

  .service-card-3d:hover .service-hover-content {
    height: auto;
    opacity: 1;
    margin-top: 15px;
  }

  .service-card-3d .service-hover-content p {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .service-card-3d .service-cta {
    display: inline-block;
    color: #28a745;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
  }

  .service-card-3d:hover .service-cta {
    color: #218838;
    transform: translateX(5px);
  }
</style>

    <!-- Our Department Starts Here -->
    <?php include_once 'hms/pages/service.php'; ?>

    <!-- Our Services Starts Here -->
    <?php include_once 'hms/pages/department.php'; ?>

    <!-- About Us Starts Here -->
    <?php include_once 'hms/pages/about_us.php'; ?>    
    
    <!-- Gallery Starts Here -->
    <?php include_once 'hms/pages/gallery.php'; ?>      
    
    <!-- Contact Us Starts Here -->
    <?php include_once 'hms/pages/contact_us.php'; ?>
    
    <!-- Footer Starts Here -->
    <?php include_once 'hms/include/footer.php'; ?>

    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/scroll-nav/js/jquery.easing.min.js"></script>
    <script src="assets/plugins/scroll-nav/js/scrolling-nav.js"></script>
    <script src="assets/plugins/scroll-fixed/jquery-scrolltofixed-min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Make service cards clickable
        $(document).ready(function() {
            $('.service-card').click(function() {
                window.location.href = $(this).find('a').attr('href');
            });
        });
    </script>
</body>
</html>