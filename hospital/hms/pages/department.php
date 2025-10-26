<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style type="text/css">
        :root {
            --medical-blue: #0d6efd;
            --medical-dark-blue: #0a58ca;
            --light-blue: #e9f2ff;
            --dark-text: #212529;
            --gray-text: #6c757d;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-text);
            line-height: 1.6;
        }

        .key-features {
            background-color: var(--light-bg);
            position: relative;
            overflow: hidden;
        }

        .service-card {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: white;
            cursor: pointer;
            border: none;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            padding: 20px;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(13, 110, 253, 0.15);
        }

        .service-icon {
            transition: all 0.3s ease;
            color: #00b894;
            background: var(--light-blue);
            width: 80px;
            height: 80px;
            line-height: 80px !important;
            border-radius: 50%;
            display: inline-block;
            margin: 25px 0;
            font-size: 32px;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(90deg, #00b894, #00cec9);
            color: white;
        }

        .section-title {
            position: relative;
            color: var(--dark-text);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--medical-blue), var(--medical-dark-blue));
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 4px;
        }

        .section-subtitle {
            color: var(--gray-text);
            font-weight: 400;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        .service-title {
            color: var(--dark-text);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.4rem;
            margin-bottom: 30px;
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .service-icon {
                width: 70px;
                height: 70px;
                line-height: 70px !important;
                font-size: 28px;
            }
        }
        .view-more-link {
            font-weight: 500;
            color: var(--medical-blue);
            text-decoration: none;
            font-size: 1.05rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .view-more-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px;
            width: 0%;
            height: 2px;
            background: var(--medical-blue);
            transition: all 0.3s ease;
        }

        .view-more-link:hover {
            font-size: 1.10rem;
            color: var(--medical-dark-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .view-more-link:hover::after {
            width: 100%;
        }
    </style>
</head>

<body>

    <section id="department" class="key-features">
        <div class="container-fluid px-5">
            <div class="section-header text-center">
                <h2 class="fw-bold text-primary">Departments</h2>
                <p class="lead text-muted">Comprehensive healthcare solutions</p>
                <div class="divider mx-auto bg-primary mb-4"></div>
            </div>

            <div class="row g-4 mt-2">
                <!-- Cardiology -->
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h4 class="service-title">Cardiology</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <!-- Orthopedics -->
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-bone"></i>
                            </div>
                            <h4 class="service-title">Orthopedics</h4>
                            <!-- <a href="hms/doctor_list.php" ></a> -->
                        </div>
                    </div>
                </div>

                <!-- Neurology -->
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4 class="service-title">Neurology</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <!-- Dental Care -->
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-tooth"></i>
                            </div>
                            <h4 class="service-title">Dental Care</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <!-- ENT -->
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-ear-listen"></i>
                            </div>
                            <h4 class="service-title">ENT</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-dna"></i>
                            </div>
                            <h4 class="service-title">Encologist</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <h4 class="service-title">Pathology</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" onclick="window.location.href='hms/doctor_list.php'">
                    <div class="service-card h-100">
                        <div class="card-body">
                            <div class="service-icon">
                                <i class="fas fa-baby"></i>
                            </div>
                            <h4 class="service-title">Pediatrics</h4>
                            <!-- <a href="hms/doctor_list.php" class="btn btn-outline-primary">View Doctor</a> -->
                        </div>
                    </div>
                </div>

                <!-- View More Button -->
                
                <div class="text-end mt-4">
                    <a href="hms/pages/full_pages/all_departments.php" class="view-more-link">
                        Explore All Departments
                    </a>
                </div>  

            </div>
        </div>
    </section>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
