
  <title>Enhanced Card Counter Slider | SwiperJS</title>

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
    .section-title {
      text-align: center;
      margin-bottom: 50px;
    }
    .section-title h2 {
      font-size: 2.5rem;
      color: var(--dark-color);
      margin-bottom: 15px;
    }
    .section-title p {
      color: #666;
      font-size: 1.1rem;
      max-width: 700px;
      margin: 0 auto;
    }
    .swiper {
      padding: 40px 0 60px;
    }
    .swiper-slide {
      display: flex;
      justify-content: center;
      transition: transform 0.3s ease;
      width: auto !important; /* Added to fix slide width */
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
      width: 320px; /* Changed from min-width to fixed width */
      height: 350px; /* Changed from min-height to fixed height */
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
      background: linear-gradient(90deg, aquamarine, green);
    }
    .single_counter i {
      font-size: 60px;
      margin-bottom: 25px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
    .swiper-pagination-bullet {
      width: 12px;
      height: 12px;
      background: #ddd;
      opacity: 1;
    }
    .swiper-pagination-bullet-active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }
    
    @media (max-width: 1200px) {
      .single_counter {
        width: 300px;
        height: 300px;
      }
    }
    @media (max-width: 992px) {
      .single_counter {
        width: 280px;
        height: 280px;
      }
    }
    @media (max-width: 768px) {
      .section-title h2 {
        font-size: 2rem;
      }
      .single_counter {
        width: 250px;
        height: 250px;
        padding: 30px 20px;
      }
      .single_counter i {
        font-size: 50px;
      }
      .single_counter h2 {
        font-size: 42px;
      }
    }
    @media (max-width: 576px) {
      .container {
        padding: 60px 15px;
      }
      .swiper-button-next, .swiper-button-prev {
        display: none;
      }
      .single_counter {
        width: 100%;
        max-width: 300px;
        height: 250px;
      }
    }
  </style>


<body>

<div class="container">
  <div class="section-header text-center mt-5">
      <h2 class="fw-bold text-primary">Our Services</h2>
      <p class="lead text-muted">Providing exceptional healthcare services with state-of-the-art facilities and dedicated professionals</p>
      <div class="divider mx-auto bg-primary"></div>
    </div>
  
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">

      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-ambulance"></i>
          <h2 class="statistic-counter">24</h2>
          <p>24/7 Ambulance Service</p>
        </div>
      </div>

      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-procedures"></i>
          <h2 class="statistic-counter">1250</h2>
          <p>Beds Available</p>
        </div>
      </div>

      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-baby"></i>
          <h2 class="statistic-counter">36</h2>
          <p>NICU Units</p>
        </div>
      </div>

      <!-- Single Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-heartbeat"></i>
          <h2 class="statistic-counter">48</h2>
          <p>ICU Beds</p>
        </div>
      </div>
      
      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-user-md"></i>
          <h2 class="statistic-counter">150</h2>
          <p>Specialist Doctors</p>
        </div>
      </div>
      
      <!-- Additional Card -->
      <div class="swiper-slide">
        <div class="single_counter">
          <i class="fas fa-flask"></i>
          <h2 class="statistic-counter">18</h2>
          <p>Research Labs</p>
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
      dynamicBullets: true
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    breakpoints: {
      0: { 
        slidesPerView: 1,
        spaceBetween: 20
      },
      576: { 
        slidesPerView: 2,
        spaceBetween: 25
      },
      768: { 
        slidesPerView: 3,
        spaceBetween: 30
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 30
      }
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
          // Format numbers with commas
          if($(this).data('format') === 'comma') {
            $(this).text(Math.ceil(now).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          } else {
            $(this).text(Math.ceil(now));
          }
        }
      });
    });
    
    // Re-init counters when slide changes
    swiper.on('slideChange', function() {
      $('.statistic-counter').each(function() {
        if($(this).is(':visible')) {
          $(this).prop('Counter', 0).animate({
            Counter: $(this).text().replace(/,/g, '')
          }, {
            duration: 2000,
            easing: 'swing',
            step: function(now) {
              if($(this).data('format') === 'comma') {
                $(this).text(Math.ceil(now).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
              } else {
                $(this).text(Math.ceil(now));
              }
            }
          });
        }
      });
    });
  });
</script>

</body>
