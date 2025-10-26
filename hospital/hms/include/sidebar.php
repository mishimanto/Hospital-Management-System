<div class="slider-detail">
  <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="4"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="5"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="6"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="7"></button>
      <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="8"></button>
    </div>

    <!-- Carousel Items -->
    <div class="carousel-inner">
      <?php 
      for($i=1; $i<=9; $i++) { 
        $active = ($i==1) ? 'active' : '';
        echo '
        <div class="carousel-item '.$active.'">
          <div class="carousel-img-wrapper">
            <img src="assets/images/slider/slider_'.$i.'.jpg" class="d-block w-100" alt="Slide '.$i.'">
            <div class="carousel-cover"></div>
          </div>
          <div class="carousel-caption d-none d-md-block">
            <h5>'.($i==1 ? 'Peace of Mind Power of Precision' : 'Expert Doctors at Your Service').'</h5>
          </div>
        </div>';
      }
      ?>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>

  </div>
</div>

<style>
  .carousel-item {
    transition: transform 0.6s ease-in-out;
  }

  .carousel-img-wrapper {
    position: relative;
  }

  .carousel-cover {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 35%;
    background: rgba(0, 173, 162, 0.53);
    z-index: 1;
  }

  .carousel-caption {
    text-align: left;
    z-index: 2;
  }

  .carousel-caption h5 {
    font-size: 2rem;
    font-weight: 600;
    color: #fff;
    text-shadow: 0 0 8px rgba(0,0,0,0.5);
  }

  @media (max-width: 768px) {
    .carousel-cover {
      width: 100%;
      background: rgba(0, 173, 162, 0.4);
    }
    .carousel-caption {
      text-align: center;
      bottom: 20px;
    }
    .carousel-caption h5 {
      font-size: 1.3rem;
    }
  }

  .carousel-fade .carousel-item {
    opacity: 0;
    transition: opacity 1s ease;
  }

  .carousel-fade .carousel-item.active {
    opacity: 1;
  }
</style>
