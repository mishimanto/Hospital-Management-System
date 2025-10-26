<?php
session_start();
include('include/config.php');

// Fetch ambulance count
$ambulanceQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM ambulances WHERE status = 'available'");
$ambulance = mysqli_fetch_assoc($ambulanceQuery);
$availableAmbulance = $ambulance['total'];

// Fetch doctors
$doctorQuery = mysqli_query($con, "SELECT * FROM em_doctors ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Services | MediZen Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --danger: #dc3545;
            --success: #198754;
            --warning: #ffc107;
            --info: #0dcaf0;
            --light: #f8f9fa;
            --dark: #212529;
            --emergency-red: #d90429;
            --emergency-dark: #2b2d42;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --border-radius: 0.375rem;
            --border-radius-lg: 0.5rem;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: #495057;
            background-color: #f8f9fa;
        }
        
        /* Header Styles */
        .emergency-header {
            background: linear-gradient(135deg, var(--emergency-red), #ef233c);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .emergency-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
            opacity: 0.3;
        }
        
        .emergency-header h1 {
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .emergency-header .lead {
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        /* Card Styles */
        .card {
            border: none;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-5px);
        }
        
        .ambulance-card {
            background: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            position: relative;
            border-top: 5px solid var(--danger);
        }
        
        .doctor-card {
            border-radius: var(--border-radius-lg);
            border-top: 6px solid var(--primary);
        }
        
        .availability-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--success);
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: var(--transition);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--emergency-red), #ef233c);
            border: none;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #ef233c, var(--emergency-red));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(217, 4, 41, 0.3);
        }
        
        /* Utility Classes */
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .z-index-1 {
            position: relative;
            z-index: 1;
        }
        
        /* Section Spacing */
        .section {
            padding: 4rem 0;
        }
        
        /* Stats Boxes */
        .stat-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .stat-box h2, .stat-box h4 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-box p {
            color: var(--secondary);
            margin-bottom: 0;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }
        
        /* Doctor Card Specifics */
        .doctor-name {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .doctor-specialty {
            color: var(--primary);
            font-weight: 500;
        }
        
        .doctor-qualification {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 2rem 0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .emergency-header {
                padding: 2rem 0;
            }
            
            .emergency-header h1 {
                font-size: 2rem;
            }
            
            .stat-box {
                margin-bottom: 1rem;
            }
            
            .section {
                padding: 2rem 0;
            }
            
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991.98px) {
            .emergency-header {
                padding: 2.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Emergency Header -->
    <header class="emergency-header text-center">
        <div class="container z-index-1">
            <h1 class="display-4 fw-bold mb-3"><!-- <i class="fas fa-ambulance me-2"></i> --> Emergency Services</h1>
            <p class="lead mb-0">Immediate medical care when you need it most</p>
        </div>
    </header>

    <main class="container">
        <!-- Ambulance Service Section -->
        <section class="mb-5 py-4 py-lg-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg ambulance-card">
                        <div class="card-body text-center py-4 py-lg-5">
                            <div class="mb-4">
                                <i class="fas fa-ambulance fa-3x fa-lg-4x mb-3 text-danger"></i>
                                <h2 class="fw-bold text-uppercase mb-3">24/7 Ambulance Service</h2>
                                <!-- <p class="lead text-muted">Emergency ambulance available round the clock</p> -->
                            </div>

                            <div class="row mt-4 g-3 g-lg-4">
                                <div class="col-md-4">
                                    <div class="stat-box">
                                        <h2 class="text-danger fw-bold"><?= $availableAmbulance ?></h2>
                                        <p>Available Now</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-box">
                                        <h4 class="text-dark fw-bold"><i class="fas fa-clock me-2 text-danger"></i>5-30 mins</h4>
                                        <p>Response Time</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-box">
                                        <a href="book_ambulance.php" class="btn btn-danger btn-lg px-4">
                                            <i class="fas fa-calendar-check me-2"></i>Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-4 text-danger small fw-semibold">
                                <i class="fas fa-exclamation-circle me-1"></i> For emergency ambulance, please login to your account first.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Emergency Doctors Section -->
        <section class="mb-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold"><i class="fas fa-user-md me-2"></i> Emergency Doctors On Duty</h2>
                <!-- <p class="lead text-muted">Our team of experienced emergency specialists</p> -->
            </div>

            <div class="row g-4">
                <?php while($doctor = mysqli_fetch_assoc($doctorQuery)) { ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card doctor-card h-100">
                        <div class="card-body text-center py-4 position-relative">
                            <?php if ($doctor['available']) { ?>
                                <span class="availability-indicator"></span>
                            <?php } ?>
                            
                            <div class="mb-4">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user-md fa-2x text-primary"></i>
                                </div>
                            </div>
                            
                            <h4 class="doctor-name"><?= htmlspecialchars($doctor['name']) ?></h4>
                            <p class="doctor-specialty mb-2"><?= htmlspecialchars($doctor['specialization']) ?></p>
                            <p class="doctor-qualification mb-3"><?= htmlspecialchars($doctor['qualification']) ?></p>
                            
                            <hr class="my-3">
                            
                            <p class="mb-4"><i class="fas fa-clock me-2 text-danger"></i> <?= htmlspecialchars($doctor['shift']) ?></p>
                            
                            <div class="d-grid">
                                <button class="btn btn-sm <?= $doctor['available'] ? 'btn-success' : 'btn-outline-secondary' ?>" <?= $doctor['available'] ? '' : 'disabled' ?>>
                                    <?= $doctor['available'] ? 'Available Now' : 'Off Duty' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <h3 class="mb-3">MEDIZEN</h3>

            <?php
                $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
                while ($row = mysqli_fetch_array($ret)) {
            ?>
            <p class="mb-2"><i class="fas fa-phone-alt me-2"></i> Emergency: <?php echo $row['MobileNumber']; ?></p>
            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Chasara, Narayanganj, Bangladesh</p>

            <?php } ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>