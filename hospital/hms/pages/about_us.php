<?php
$establishedYear = 2008;
$currentYear = date("Y");
$experience = $currentYear - $establishedYear;
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
.about-us {
    font-family: 'Poppins', sans-serif;
    position: relative;
}

.about-img {
    position: relative;
    border-radius: 10px 0 0 10px ;
    box-shadow: 15px 15px 30px rgba(0, 0, 0, 0.1);
}

.about-content {
    background: white;
    border-radius: 10px 0 0 10px;
    box-shadow: -15px 15px 30px rgba(0, 0, 0, 0.1);
}

.icon-box {
    transition: all 0.3s ease;
    background: linear-gradient(90deg, #00b894, #00cec9);
    border-radius: 6px;
}

.icon-box:hover {
    transform: rotate(15deg);
    transform: translateY(3px);
    background: #00b894;
}

.btn-primary {
    background: linear-gradient(90deg, #00b894, #00cec9);
    border: none;
    padding: 10px 25px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #00b894;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

@media (max-width: 991.98px) {
    .about-img, .about-content {
        border-radius: 10px;
    }
    .about-content {
        margin-top: -50px;
        z-index: 1;
        position: relative;
    }
}
</style>

<section id="about_us" class="about-us bg-light">
    <div class="container">
        <div class="row align-items-center g-0">
            <!-- Image Column -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="about-img h-100" style="min-height: 500px; background: url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80') center/cover;">
                    <div class="position-absolute top-0 end-0 p-3 m-4 rounded" style="background: linear-gradient(90deg, #00b894, #00cec9);">
                        <h4 class="text-white mb-0">
                          <i class="fas fa-award me-2"></i><?php echo $experience; ?>+ Years Experience
                        </h4>
                    </div>
                </div>
            </div>
            
            <!-- Content Column -->
            <div class="col-lg-6">
                <div class="about-content p-4 p-lg-5">
                    <span class="text-primary fw-bold mb-2 d-block">About Us</span>
                    <h2 class="mb-4">Compassionate Care <span style="color: #00b894;">Since <?php echo $establishedYear; ?></span></h2>
                    
                    <?php
                        $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='aboutus'");

                        if (!$ret) {
                            die("Query Failed: " . mysqli_error($con));
                        }

                        $i = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                            $fullText = $row['PageDescription'];
                            $firstPart = substr($fullText, 0, 125);
                            $secondPart = substr($fullText, 0, 900);
                        ?>
                            <div class="about-text mb-4">
                                <p class="lead d-inline">
                                    <?php echo htmlspecialchars($firstPart); ?>...
                                </p>
                                <a href="#" class="btn-link text-decoration-none fw-semibold ms-2" data-bs-toggle="modal" data-bs-target="#aboutModal<?php echo $i; ?>">
                                    readmore
                                </a><br>
                                <br>

                               <p class="mt-3 mb-3 d-inline">
                            <?php echo htmlspecialchars($secondPart); ?>...
                        </p>
                        <a href="#" class="btn-link text-decoration-none fw-semibold ms-1" data-bs-toggle="modal" data-bs-target="#aboutModal<?php echo $i; ?>">
                            readmore
                        </a>

                            </div>

                            <div class="modal fade" id="aboutModal<?php echo $i; ?>" tabindex="-1" aria-labelledby="aboutModalLabel<?php echo $i; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="aboutModalLabel<?php echo $i; ?>">About Us - Full Description</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><?php echo nl2br(htmlspecialchars($fullText)); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                            $i++;
                        }
                    ?>



                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box p-3 me-3">
                                    <i class="fas fa-user-md text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Expert Doctors</h5>
                                    <small class="text-muted">Specialized Physicians</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box p-3 me-3">
                                    <i class="fas fa-procedures text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Modern Equipment</h5>
                                    <small class="text-muted">Advanced Technology</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

