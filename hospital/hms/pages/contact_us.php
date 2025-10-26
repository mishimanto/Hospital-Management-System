<style>
  .contact-section {
    font-family: 'Poppins', sans-serif;
  }

  .section-header .divider {
    width: 60px;
    height: 3px;
    margin: 10px auto;
  }

  .contact-form .input-group-text {
    width: 45px;
    justify-content: center;
  }

  .contact-item .icon-box {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-primary {
    background: linear-gradient(45deg, #0d6efd, #0b5ed7);
    border: none;
    transition: all 0.3s;
  }

  .btn-primary:hover {
    background: linear-gradient(45deg, #0b5ed7, #0d6efd);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
  }

  .social-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.3s;
  }

  .social-icon:hover {
    background: #0d6efd;
    color: white !important;
    transform: translateY(-3px);
  }

  /* Mobile-specific styles */
  @media (max-width: 767.98px) {
    .contact-section {
      padding-top: 2rem;
      padding-bottom: 2rem;
    }

    .contact-info {
      margin-bottom: 1.5rem;
    }

    .contact-form, .contact-info {
      padding: 1.5rem !important;
    }

    .contact-item {
      margin-bottom: 1.25rem !important;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .icon-box {
      margin-bottom: 0.75rem;
      margin-right: 0;
    }

    .btn-lg {
      padding: 0.5rem 1rem;
      font-size: 1rem;
    }
  }


  /* Tablet styles */
  @media (min-width: 768px) and (max-width: 991.98px) {
    .contact-item {
      flex-direction: row !important;
      align-items: center;
      text-align: left;
    }
    
    .icon-box {
      margin-right: 1rem !important;
      margin-bottom: 0 !important;
    }
  }

  /* Desktop styles */
  @media (min-width: 992px) {
    .contact-item {
      flex-direction: row;
      align-items: center;
      text-align: left;
    }
    
    .icon-box {
      margin-right: 1rem;
      margin-bottom: 0;
    }
  }
</style>


<!-- Contact Us Starts Here -->
<section id="contact_us" class="contact-section bg-light">
  <div class="container">
    <div class="section-header text-center mb-4 mb-md-5">
      <h2 class="fw-bold text-primary mb-2">Get In Touch</h2>
      <p class="text-muted mb-3">We're here to help and answer any questions you might have</p>
      <div class="divider mx-auto bg-primary"></div>
    </div>

    <div class="row g-3 g-md-4">
      <!-- Contact Form Column -->
      <div class="col-lg-6 order-lg-1 order-2">
        <div class="contact-form bg-white p-3 p-md-4 p-lg-5 rounded-3 shadow-sm">
          <h4 class="mb-3 mb-md-4 text-dark fw-bold">Send us a message</h4>
          
          <form method="post" novalidate>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                  <i class="fas fa-user"></i>
                </span>
                <input 
                  type="text" 
                  name="fullname" 
                  class="form-control shadow-none" 
                  placeholder="Your full name" 
                  required
                >
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                  <i class="fas fa-envelope"></i>
                </span>
                <input 
                  type="email" 
                  name="emailid" 
                  class="form-control shadow-none" 
                  placeholder="Your email address" 
                  required
                >
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                  <i class="fas fa-phone"></i>
                </span>
                <input 
                  type="tel" 
                  name="mobileno" 
                  class="form-control shadow-none" 
                  placeholder="Your mobile number" 
                  pattern="[0-9]{11,14}"
                  title="Enter a valid phone number" 
                  required
                >
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text bg-primary text-white align-items-start">
                  <i class="fas fa-comment mt-1"></i>
                </span>
                <textarea 
                  name="description" 
                  rows="4" 
                  class="form-control shadow-none" 
                  placeholder="Your message" 
                  required
                ></textarea>
              </div>
            </div>

            <div class="d-grid">
              <button 
                type="submit" 
                name="submit" 
                class="btn btn-primary btn-lg fw-bold py-2 py-md-3"
              >
                <i class="fas fa-paper-plane me-2"></i> Send Message
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Contact Info Column -->
      <div class="col-lg-6 order-lg-2 order-1 mb-3 mb-lg-0">
        <div class="contact-info h-100 p-3 p-md-4 p-lg-5 rounded-3 bg-white shadow-sm">
          <h4 class="mb-3 mb-md-4 text-dark fw-bold">Contact Information</h4>
          
          <div class="contact-item d-flex flex-column flex-sm-row mb-3 mb-md-4">
            <div class="icon-box bg-primary text-white rounded-circle p-2 p-md-3 me-0 me-sm-3 mb-2 mb-sm-0">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="text-center text-sm-start">
              <h5 class="mb-1 fs-6">Our Location</h5>
              <p class="mb-0 text-muted small">Chasara, Narayanganj, Bangladesh</p>
            </div>
          </div>

          <div class="contact-item d-flex flex-column flex-sm-row mb-3 mb-md-4">
            <div class="icon-box bg-primary text-white rounded-circle p-2 p-md-3 me-0 me-sm-3 mb-2 mb-sm-0">
              <i class="fas fa-phone-alt"></i>
            </div>

            <?php
                $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
                while ($row = mysqli_fetch_array($ret)) {
            ?>
            <div class="text-center text-sm-start">
              <h5 class="mb-1 fs-6">Phone Numbers</h5>
              <p class="mb-0 text-muted small"><?php echo $row['MobileNumber']; ?> (Emergency)</p>
              <p class="mb-0 text-muted small"><?php echo $row['MobileNumber']; ?> (Appointment)</p>

            </div>
          </div>

          <div class="contact-item d-flex flex-column flex-sm-row mb-3 mb-md-4">
            <div class="icon-box bg-primary text-white rounded-circle p-2 p-md-3 me-0 me-sm-3 mb-2 mb-sm-0">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="text-center text-sm-start">
              <h5 class="mb-1 fs-6">Email Address</h5>
              <p class="mb-0 text-muted small"><?php echo $row['Email'];?></p>
              <!-- <p class="mb-0 text-muted small"><?php echo $row['Email'];?></p> -->
            </div>
          </div>

          <div class="contact-item d-flex flex-column flex-sm-row mb-3 mb-md-4">
            <div class="icon-box bg-primary text-white rounded-circle p-2 p-md-3 me-0 me-sm-3 mb-2 mb-sm-0">
              <i class="fas fa-clock"></i>
            </div>
            <div class="text-center text-sm-start">
              <h5 class="mb-1 fs-6">Working Hours</h5>
              <p class="mb-0 text-muted small">Saturday - Thursday: 9:00 AM - 5:00 PM</p>
              <!-- <p class="mb-0 text-muted small">Friday: 8:00 AM - 8:00 PM</p> -->
            </div>
          </div>
          <?php } ?>

          <div class="social-links mt-4 mt-md-5 text-center">
            <h5 class="mb-2 mb-md-3 fs-6">Connect With Us</h5>
            <div class="d-flex justify-content-center">
              <a href="#" class="btn btn-outline-primary rounded-circle me-2 social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="btn btn-outline-primary rounded-circle me-2 social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="btn btn-outline-primary rounded-circle me-2 social-icon">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="btn btn-outline-primary rounded-circle social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

