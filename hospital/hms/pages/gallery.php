<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hospital Medical Gallery</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Lightbox CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

  <!-- AOS Animation CSS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .gallery-section {
      background-color: #f9fbfd;
    }

    .inner-title h2 {
      font-weight: 700;
      color: #0d6efd;
    }

    .title-divider {
      width: 80px;
      height: 3px;
      background: #0d6efd;
      margin: 15px auto 0;
      border-radius: 3px;
    }

    .gallery-card {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      transition: all 0.4s ease;
      background: #fff;
    }

    .gallery-card img {
      transition: transform 0.6s ease;
      width: 100%;
      height: 300px;
      object-fit: cover;
    }

    .gallery-card:hover img {
      transform: scale(1.07);
    }

    .gallery-card:hover {
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .gallery-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 16px;
      background: linear-gradient(90deg, #00b894, #00cec9);
      color: white;
      transition: all 0.4s ease;
      opacity: 0;
      border-top: 2px solid rgba(255,255,255,0.3);
    }

    .gallery-card:hover .gallery-overlay {
      opacity: 1;
    }

    .gallery-overlay h5 {
      font-size: 1rem;
      margin-bottom: 0;
    }

    .view-btn {
      color: white;
      background: linear-gradient(90deg, #00b894, #00cec9);
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.35s ease;
    }

    .view-btn:hover {
      background: #fff;
      color: #00b894;
    }

    .filter-button {
      border-radius: 30px;
      padding: 8px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .filter-button:hover {
      background-color:  #00b894;
      color: #fff;
    }

    .filter-button.active {
      background-color: #0d6efd !important;
      color: white !important;
    }

    @media (max-width: 767px) {
      .gallery-filter {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }
    }
  </style>
</head>

<body>

<div id="gallery" class="gallery-section">
  <div class="container-fluid px-5">
    <div class="inner-title text-center mb-5">
      <h2 class="display-5 fw-bold">Our Gallery</h2>
      <p class="lead text-muted">Explore Our Healthcare Facilities and Services</p>
      <div class="title-divider"></div>
    </div>

    <div class="gallery-filter text-center mb-4">
      <button class="btn btn-outline-success mx-2 mb-2 filter-button active" data-filter="all">All</button>
      <button class="btn btn-outline-success mx-2 mb-2 filter-button" data-filter="dental"><i class="fas fa-tooth me-2"></i>Dental</button>
      <button class="btn btn-outline-success mx-2 mb-2 filter-button" data-filter="cardiology"><i class="fas fa-heartbeat me-2"></i>Cardiology</button>
      <button class="btn btn-outline-success mx-2 mb-2 filter-button" data-filter="neurology"><i class="fas fa-brain me-2"></i>Neurology</button>
      <button class="btn btn-outline-success mx-2 mb-2 filter-button" data-filter="laboratory"><i class="fas fa-flask me-2"></i>Laboratory</button>
    </div>

    <div class="row gallery-container">
      <!-- Item Example -->
      <div class="col-lg-4 col-md-6 mb-4 gallery-item dental" data-aos="fade-up">
        <div class="gallery-card">
          <img src="assets/images/gallery/gallery_01.jpg" alt="Dental Care">
          <div class="gallery-overlay d-flex align-items-center justify-content-between">
            <h5><i class="fas fa-tooth me-2"></i>Dental</h5>
            <a href="assets/images/gallery/gallery_01.jpg" data-lightbox="gallery" data-title="Dental Care" class="view-btn">
              <i class="fas fa-expand"></i>
            </a>
          </div>
        </div>
      </div>
       <!-- Dental -->
            <div class="col-lg-4 col-md-6 mb-4 gallery-item dental">
                <div class="gallery-card">
                    <img src="assets/images/gallery/gallery_03.jpg" class="img-fluid" alt="Dental Care">
                    <div class="gallery-overlay d-flex align-items-center justify-content-between px-3">
                        <h5 class="mb-0"><i class="fas fa-tooth me-2"></i>Dental</h5>
                        <a href="assets/images/gallery/gallery_03.jpg" data-lightbox="gallery" class="view-btn">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>

      <!-- Repeat for other categories -->
      <!-- Cardiology -->
      <div class="col-lg-4 col-md-6 mb-4 gallery-item cardiology" data-aos="fade-up">
        <div class="gallery-card">
          <img src="assets/images/gallery/gallery_02.jpg" alt="Cardiology">
          <div class="gallery-overlay d-flex align-items-center justify-content-between">
            <h5><i class="fas fa-heartbeat me-2"></i>Cardiology</h5>
            <a href="assets/images/gallery/gallery_02.jpg" data-lightbox="gallery" data-title="Cardiology Unit" class="view-btn">
              <i class="fas fa-expand"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Repeat other items similarly -->
      <!-- Neurology, Laboratory, etc -->
      <!-- Laboratry -->
            <div class="col-lg-4 col-md-6 mb-4 gallery-item laboratory">
                <div class="gallery-card">
                    <img src="assets/images/gallery/gallery_04.jpg" class="img-fluid" alt="Laboratory">
                    <div class="gallery-overlay d-flex align-items-center justify-content-between px-3">
                        <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Laboratory</h5>
                        <a href="assets/images/gallery/gallery_04.jpg" data-lightbox="gallery" class="view-btn">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Neurology -->
            <div class="col-lg-4 col-md-6 mb-4 gallery-item neurology">
                <div class="gallery-card">
                    <img src="assets/images/gallery/gallery_05.jpg" class="img-fluid" alt="Neurology">
                    <div class="gallery-overlay d-flex align-items-center justify-content-between px-3">
                        <h5 class="mb-0"><i class="fas fa-brain me-2"></i>Neurology</h5>
                        <a href="assets/images/gallery/gallery_05.jpg" data-lightbox="gallery" class="view-btn">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Neurology -->
            <div class="col-lg-4 col-md-6 mb-4 gallery-item neurology">
                <div class="gallery-card">
                    <img src="assets/images/gallery/gallery_06.jpg" class="img-fluid" alt="Neurology">
                    <div class="gallery-overlay d-flex align-items-center justify-content-between px-3">
                        <h5 class="mb-0"><i class="fas fa-brain me-2"></i>Neurology</h5>
                        <a href="assets/images/gallery/gallery_06.jpg" data-lightbox="gallery" class="view-btn">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>
        

    </div>
  </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({duration: 800, once: true});</script>

<script>
  $(document).ready(function(){
    $(".filter-button").click(function(){
      var value = $(this).attr('data-filter');
      $(".filter-button").removeClass("active");
      $(this).addClass("active");
      if(value == "all"){
        $('.gallery-item').show('600');
      } else {
        $(".gallery-item").not('.'+value).hide('600');
        $('.gallery-item').filter('.'+value).show('600');
      }
    });
  });
</script>

</body>
</html>
