<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Departments</title>

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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
            background: var(--light-bg);
        }

        .service-card {
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            margin-bottom: 20px;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(13, 110, 253, 0.1);
        }

        .service-icon {
            color: #00b894;
            background: var(--light-blue);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            display: inline-block;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .service-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .page-title {
            text-align: center;
            font-size: 2.5rem;
            margin-top: 30px;
            margin-bottom: 40px;
            color: var(--medical-blue);
            position: relative;
        }

        .page-title::after {
            content: "";
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--medical-blue), var(--medical-dark-blue));
            display: block;
            margin: 12px auto 0;
            border-radius: 4px;
        }

        .back-btn {
            display: inline-block;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2 class="page-title">Our Departments</h2>
        <div class="row">
            <!-- Cardiology -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-heartbeat"></i></div>
                    <h5 class="service-title">Cardiology</h5>
                </div>
            </div>

            <!-- Neurology -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-brain"></i></div>
                    <h5 class="service-title">Neurology</h5>
                </div>
            </div>

            <!-- Orthopedics -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-bone"></i></div>
                    <h5 class="service-title">Orthopedics</h5>
                </div>
            </div>

            <!-- Dental Care -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-tooth"></i></div>
                    <h5 class="service-title">Dental Care</h5>
                </div>
            </div>

            <!-- Surgery -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-procedures"></i></div>
                    <h5 class="service-title">Surgery</h5>
                </div>
            </div>

            <!-- Pharmacy -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-pills"></i></div>
                    <h5 class="service-title">Pharmacy</h5>
                </div>
            </div>

            <!-- ENT -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-ear-listen"></i></div>
                    <h5 class="service-title">ENT</h5>
                </div>
            </div>

            <!-- Dermatology -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-user-md"></i></div>
                    <h5 class="service-title">Dermatology</h5>
                </div>
            </div>

            <!-- Pediatrics -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-baby"></i></div>
                    <h5 class="service-title">Pediatrics</h5>
                </div>
            </div>

            <!-- Psychiatry -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-brain"></i></div>
                    <h5 class="service-title">Psychiatry</h5>
                </div>
            </div>

            <!-- Ophthalmology -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-eye"></i></div>
                    <h5 class="service-title">Ophthalmology</h5>
                </div>
            </div>

            <!-- Urology -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-venus-mars"></i></div>
                    <h5 class="service-title">Urology</h5>
                </div>
            </div>

        </div>

        <!-- Back to Home Button -->
        <a href="index.php" class="btn btn-primary back-btn">← Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




