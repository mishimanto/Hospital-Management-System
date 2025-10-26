<style type="text/css">
/* Footer base */
.footer {
  background-color: #002f2f;
  color: #e0f2f1;
  padding: 40px 0 30px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border-top: 4px solid #00ab9f;
}

.footer h2 {
  font-size: 24px;
  margin-bottom: 25px;
  font-weight: 700;
  color: #00ab9f;
  position: relative;
}

.footer h2::after {
  content: "";
  display: block;
  width: 60px;
  height: 3px;
  background: #00ab9f;
  margin-top: 8px;
  border-radius: 2px;
}

.link-listi {
  padding-left: 0;
  list-style: none;
}

.link-listi li {
  margin-bottom: 15px;
  position: relative;
  padding-left: 25px;
}

.link-listi li i.fa-angle-right {
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  color: #00ab9f;
  font-size: 16px;
  transition: color 0.3s ease;
}

.link-listi li a {
  color: #e0f2f1;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.link-listi li a:hover {
  color: #00ab9f;
}

/* Contact address */
.footer address {
  font-style: normal;
  line-height: 1.6;
  color: #b2dfdb;
  font-weight: 400;
  font-size: 16px;
}

.footer address a {
  color: #00ab9f;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer address a:hover {
  color: #e0f2f1;
  text-decoration: underline;
}

/* Map Card */
.map-card {
  background-color: #003838;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 20px rgba(0,0,0,0.4);
  border: 1px solid #005e5e;
}

.map-card iframe {
  display: block;
  width: 100%;
  height: 270px;
  border: 0;
}

/* Social icons */
.social-icons {
  margin-top: 40px;
  text-align: center;
}

.social-icons a {
  display: inline-block;
  margin: 0 8px;
  color: #e0f2f1;
  font-size: 20px;
  transition: color 0.3s ease, transform 0.3s ease;
}

.social-icons a:hover {
  color: #00ab9f;
  transform: scale(1.2);
}

/* Scroll to top button */
#toTop {
  display: none;
  position: fixed;
  bottom: 50px;
  right: 30px;
  z-index: 99;
  background-color: #00ab9f;
  color: white;
  padding: 12px 16px;
  border-radius: 50%;
  font-size: 22px;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  opacity: 0;
  transition: opacity 0.4s ease, transform 0.4s ease;
}

#toTop.show {
  display: block;
  opacity: 1;
  transform: scale(1);
}

#toTop.hide {
  opacity: 0;
  transform: scale(0.8);
  transition: opacity 0.4s ease, transform 0.4s ease;
}

#toTop:hover {
  background: #00cfc0;
  transform: scale(1.15);
}

/* Responsive layout */
@media (max-width: 767px) {
  .footer .col-md-4 {
    margin-bottom: 30px;
  }
}

/* Copyright bar */
.copy {
  height: auto;
  background-color: #001f1f;
  color: #a7d9d7;
  text-align: center;
  padding: 15px 0;
  font-size: 18px;
  letter-spacing: 0.02em;
  border-top: 1px solid #004d4d;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  user-select: none;
}
</style>

<footer class="footer">
  <div class="container-fluid px-5">
    <div class="row  px-5">

      <!-- Useful Links -->
      <div class="col-md-4 col-sm-12">
        <h2>Useful Links</h2>
        <ul class="list-unstyled link-listi">
          <li><a href="#about_us">About us</a><i class="fa fa-angle-right"></i></li>
          <li><a href="#services">Services</a><i class="fa fa-angle-right"></i></li>
          <li><a href="admin_login.php">Admin Login</a><i class="fa fa-angle-right"></i></li>
          <li><a href="#gallery">Gallery</a><i class="fa fa-angle-right"></i></li>
          <li><a href="#contact_us">Contact us</a><i class="fa fa-angle-right"></i></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-md-4 col-sm-12">
        <h2>Contact Us</h2>
        <address class="md-margin-bottom-40">
          <?php
          $ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");
          while ($row=mysqli_fetch_array($ret)) {
          ?>
          <?php echo $row['PageDescription'];?> <br>
          Phone: <?php echo $row['MobileNumber'];?> <br>
          Email: <a href="mailto:<?php echo $row['Email'];?>"><?php echo $row['Email'];?></a><br>
          Open: <?php echo $row['OpenningTime'];?>
          <?php } ?>
        </address>
      </div>

      <!-- Map + Social Icons -->
      <div class="col-md-4 col-sm-12">
        <h2>Find Us</h2>
        <div class="map-card">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3655.448575332718!2d90.49608087589432!3d23.62410129361235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b10941d09f3f%3A0x9e53c62023606a5!2sChashara%2C%20Narayanganj!5e0!3m2!1sen!2sbd!4v1748525476323!5m2!1sen!2sbd" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
      <div class="social-icons">
          <a href="#"><i class="fa fa-facebook"></i></a>
          <a href="#"><i class="fa fa-instagram"></i></a>
          <a href="#"><i class="fa fa-twitter"></i></a>
          <a href="#"><i class="fa fa-google"></i></a>
      </div>

    </div>
  </div>
</footer>

<!-- Scroll to Top Button -->
<div id="toTop"><i class="fa fa-chevron-up"></i></div>

<div class="copy">
  <div>All right reserved &copy; MSEDIZEN</div>
</div>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
// Scroll to Top Script
window.onscroll = function() {
  var toTopBtn = document.getElementById("toTop");
  if (document.body.scrollTop > 1200 || document.documentElement.scrollTop > 1200) {
    toTopBtn.classList.add("show");
    toTopBtn.classList.remove("hide");
  } else {
    toTopBtn.classList.remove("show");
    toTopBtn.classList.add("hide");
  }
};

document.getElementById('toTop').onclick = function() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};


</script>
