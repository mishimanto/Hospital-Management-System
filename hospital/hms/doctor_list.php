<?php
include('include/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Doctors</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    
    <style>
        :root {
            --medical-blue: #0d6efd;
            --medical-dark-blue: #0a58ca;
            --light-blue: #e9f2ff;
            --dark-text: #212529;
            --gray-text: #6c757d;
            --light-bg: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        .page-header {
            background: linear-gradient(90deg, #00b894, #00cec9);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .doctor-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15);
        }

        .doctor-header {
            background: var(--medical-blue);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .doctor-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .doctor-specialty {
            font-size: 1rem;
            opacity: 0.9;
        }

        .doctor-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            margin: -60px auto 20px;
            background: var(--light-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--medical-blue);
            font-size: 2.5rem;
            font-weight: 600;
        }

        .doctor-body {
            padding: 1.5rem;
        }

        .doctor-detail {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }

        .doctor-detail i {
            color: var(--medical-blue);
            margin-right: 10px;
            margin-top: 3px;
            min-width: 20px;
        }

        .doctor-fees {
            background: var(--light-blue);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: 500;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .btn-book {
            background: var(--medical-blue);
            color: white;
            flex: 1;
        }

        .btn-profile {
            border: 1px solid var(--medical-blue);
            color: var(--medical-blue);
            flex: 1;
        }

        .btn-book:hover {
            background: var(--medical-dark-blue);
            color: white;
        }

        .btn-profile:hover {
            background: var(--light-blue);
        }

        .specialty-badge {
            background: rgba(13, 110, 253, 0.1);
            color: var(--medical-blue);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 2rem 0;
        }

        /* Back button styles */
        .back-btn-container {
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .back-btn {
            float: right;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--medical-blue);
            color: var(--medical-blue);
            transition: all 0.3s ease;
        }
        
        /*.back-btn:hover {
            background: var(--light-blue);
            color: var(--medical-dark-blue);
        }*/

        /* Search bar styles */
        .search-container {
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .search-input {
            border-radius: 50px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .search-input:focus {
            border-color: var(--medical-blue);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .search-btn {
            border-radius: 50px;
            padding: 12px 25px;
            background: var(--medical-blue);
            border: none;
        }
        .search-btn:hover {
            background: var(--medical-dark-blue);
        }

        .close-btn {
            border-radius: 50px;
            background: gray;
            padding: 12px 25px;
            border: none;
        }

        .close-btn:hover {
            background: var(--gray-text);
        }
        
        /* No results message */
        .no-results {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: var(--gray-text);
        }
    </style>
</head>
<body>

    <?php //include('include/header_logins_page.php'); ?>
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Our Specilizatialized Doctors</h1>
            <p class="page-subtitle text-muted">Meet our team of highly qualified medical professionals</p>
        </div>
    </header>

    <!-- Doctors Listing -->
    <div class="container">
        <!-- Search Bar -->
        <div class="search-container">
            <form id="searchForm" class="d-flex" action="doctor_list.php" method="GET">
                <input type="text" name="search" class="form-control search-input me-2" 
                       placeholder="Search doctors..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary search-btn me-2">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($_GET['search']) || !empty($_GET['spec'])): ?>
                    <a href="doctor_list.php" class="btn btn-info close-btn d-flex align-items-center justify-content-center" >
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="row">

            <?php
             // Database connection
            $servername = "127.0.0.1";
            $username = "root"; // replace with your database username
            $password = ""; // replace with your database password
            $dbname = "hms";
            
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Check if search query exists
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $specialization = isset($_GET['spec']) ? $_GET['spec'] : '';
                
                // Build the base query
                $query = "SELECT * FROM doctors";
                $conditions = [];
                $params = [];
                
                // Add search condition if search term exists
                if (!empty($search)) {
                    $conditions[] = "(doctorName LIKE :search OR specilization LIKE :search OR address LIKE :search)";
                    $params[':search'] = '%' . $search . '%';
                }
                
                // Add specialization filter if exists
                if (!empty($specialization)) {
                    $conditions[] = "specilization = :spec";
                    $params[':spec'] = $specialization;
                }
                
                // Combine conditions if any exist
                if (!empty($conditions)) {
                    $query .= " WHERE " . implode(' AND ', $conditions);
                }
                
                $query .= " ORDER BY doctorName";
                
                $stmt = $conn->prepare($query);
                
                // Bind parameters
                foreach ($params as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                
                // Set the result array to associative
                $stmt->execute(); // এই লাইনটা অবশ্যই লাগবে
                $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                
                if (count($doctors) > 0) {
                    foreach ($doctors as $doctor) {
                        // Format visiting hours
                        $visitingHours = date("g:i A", strtotime($doctor['visiting_start_time'])) . 
                                        " - " . 
                                        date("g:i A", strtotime($doctor['visiting_end_time']));
                        
                        // Get initials for avatar
                        $names = explode(' ', $doctor['doctorName']);
                        $initials = '';
                        foreach ($names as $name) {
                            $initials .= strtoupper(substr($name, 0, 1));
                            if (strlen($initials) >= 2) break;
                        }
                        
                        // Output the doctor card
                        echo '<div class="col-lg-4 col-md-6 mb-4">
                                <div class="doctor-card">
                                    <div class="doctor-header">
                                        <div class="doctor-avatar">Dr.</div>
                                        <h3 class="doctor-name">' . htmlspecialchars($doctor['doctorName']) . '</h3>
                                        <p class="doctor-specialty">' . htmlspecialchars($doctor['specilization']) . '</p>
                                    </div>
                                    <div class="doctor-body">
                                        <div class="doctor-detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <strong>From</strong>
                                                <p>' . htmlspecialchars($doctor['address']) . '</p>
                                            </div>
                                        </div>
                                        
                                        <div class="doctor-detail">
                                            <i class="fas fa-clock"></i>
                                            <div>
                                                <strong>Visiting Hours</strong>
                                                <p>' . $visitingHours . '</p>
                                            </div>
                                        </div>
                                        
                                        <div class="doctor-detail">
                                            <i class="fas fa-phone-alt"></i>
                                            <div>
                                                <strong>Contact</strong>
                                                <p>' . htmlspecialchars($doctor['contactno']) . '</p>
                                            </div>
                                        </div>
                                        
                                        <div class="doctor-detail">
                                            <i class="fas fa-envelope"></i>
                                            <div>
                                                <strong>Email</strong>
                                                <p>' . htmlspecialchars($doctor['docEmail']) . '</p>
                                            </div>
                                        </div>
                                        
                                        <div class="doctor-fees">
                                            Consultation Fee: <i class="fa-solid fa-bangladeshi-taka-sign"></i> <span style="color: #CD5C5C; font-size: 18px;">' . htmlspecialchars($doctor['docFees']) . '</span>
                                        </div>
                                        
                                        <div class="action-buttons">
                                            <a href="book-appointment.php" class="btn btn-book btn-sm p-2">
                                                <i class="fas fa-calendar-check"></i> Book Appointment
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '<div class="col-12 text-center py-5">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No doctors found in our database.
                            </div>
                        </div>';
                }
            } catch(PDOException $e) {
                echo '<div class="col-12 text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i> Error loading doctors: ' . htmlspecialchars($e->getMessage()) . '
                        </div>
                    </div>';
            }
            ?>
        </div>
        <div class="back-btn-container">
            <a href="../index.php" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 back-btn" style="padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none;">
              <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>

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

    <?php //include('include/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript for live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.querySelector('input[name="search"]');
            const doctorsContainer = document.getElementById('doctorsContainer');
            
            // Handle form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                
                // Update URL with search parameter
                const url = new URL(window.location.href);
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            });
        });
    </script>
</body>
</html>