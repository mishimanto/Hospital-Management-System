<?php
include('../../include/config.php');
?>

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
            --dark: #212529;
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
            cursor: pointer;
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
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .page-header {
            background: linear-gradient(90deg, #00b894, #00cec9);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            text-align: center;
        }
        .footer {
            background: var(--dark);
            color: white;
            padding: 2rem 0;
        }
        .button-container {
            text-align: right;
            margin-bottom: 50px;
        }
        
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
            margin-left: 8px;
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
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Our Departments</h1>
            <p class="page-subtitle text-muted">Explore Our Healthcare Departments</p>
        </div>
    </header>
    
    <div class="container">
        <!-- Search Bar -->
        <div class="search-container">
            <form id="searchForm" class="d-flex">
                <input type="text" id="searchInput" class="form-control search-input me-2" placeholder="Search departments..." aria-label="Search">
                <button class="btn btn-primary search-btn" type="submit">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($_GET['search']) || !empty($_GET['spec'])): ?>
                    <a href="all_departments.php" class="btn btn-info close-btn d-flex align-items-center justify-content-center" >
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Departments will be loaded here -->
        <div id="departmentsContainer" class="row">
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
                
                // Query to fetch all specializations with optional search filter
                if (!empty($search)) {
                    $stmt = $conn->prepare("SELECT id, specilization FROM doctorspecilization 
                                          WHERE specilization LIKE :search 
                                          ORDER BY specilization");
                    $stmt->bindValue(':search', '%' . $search . '%');
                } else {
                    $stmt = $conn->prepare("SELECT id, specilization FROM doctorspecilization 
                                          ORDER BY specilization");
                }
                
                $stmt->execute();
                
                // Set the result array to associative
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $spec = htmlspecialchars($row['specilization']);
                        
                        // Map specializations to appropriate icons
                        $icon = match (strtolower($spec)) {
                            'orthopedics' => 'fa-bone',
                            'internal medicine' => 'fa-stethoscope',
                            'obstetrics and gynecology' => 'fa-person-pregnant',
                            'dermatology', 'dermatologists' => 'fa-user-md',
                            'pediatrics' => 'fa-baby',
                            'radiology' => 'fa-x-ray',
                            'general surgery' => 'fa-procedures',
                            'ophthalmology' => 'fa-eye',
                            'anesthesia' => 'fa-syringe',
                            'pathology' => 'fa-microscope',
                            'ent' => 'fa-ear-listen',
                            'dental care' => 'fa-tooth',
                            'endocrinologists' => 'fa-flask',
                            'neurologists' => 'fa-brain',
                            'encologist' => 'fa-dna',
                            'medicine' => 'fa-heartbeat',
                            default => 'fa-hospital' // default icon
                        };
                        
                        echo '<div class="col-lg-3 col-md-4 col-sm-6 department-card">
                                <div class="service-card" onclick="window.location.href=\'../../doctor_list.php?spec=' . urlencode($spec) . '\'">
                                    <div class="service-icon"><i class="fas ' . $icon . '"></i></div>
                                    <h5 class="service-title">' . $spec . '</h5>
                                </div>
                              </div>';
                    }
                } else {
                    echo '<div class="no-results">
                            <i class="fas fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                            <p>No departments found matching your search.</p>
                          </div>';
                }
            } catch(PDOException $e) {
                echo "<p>Connection failed: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="button-container">
          <a href="../../../index.php" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 back-btn" style="padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none;">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JavaScript for live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const departmentsContainer = document.getElementById('departmentsContainer');
            
            // Handle form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                
                // Update URL with search parameter without reloading
                const url = new URL(window.location.href);
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                window.history.pushState({}, '', url);
                
                // Filter departments
                filterDepartments(searchTerm);
            });
            
            // Filter departments based on search term
            function filterDepartments(searchTerm) {
                const departmentCards = document.querySelectorAll('.department-card');
                let hasResults = false;

                if (searchTerm === '') {
                    // If search term is empty, show all departments
                    departmentCards.forEach(card => {
                        card.style.display = 'block';
                    });

                    // Remove no-results div if present
                    const noResultsDiv = document.querySelector('.no-results');
                    if (noResultsDiv) {
                        noResultsDiv.remove();
                    }
                    return;
                }

                // Otherwise, filter normally
                departmentCards.forEach(card => {
                    const title = card.querySelector('.service-title').textContent.toLowerCase();
                    if (title.includes(searchTerm.toLowerCase())) {
                        card.style.display = 'block';
                        hasResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                const noResultsDiv = document.querySelector('.no-results');
                if (!hasResults) {
                    if (!noResultsDiv) {
                        departmentsContainer.innerHTML += `
                            <div class="no-results">
                                <i class="fas fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                                <p>No departments found matching "${searchTerm}".</p>
                            </div>
                        `;
                    }
                } else if (noResultsDiv) {
                    noResultsDiv.remove();
                }
            }

            
            // Initialize with any existing search term from URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                searchInput.value = searchParam;
                filterDepartments(searchParam);
            }
        });
    </script>
</body>
</html>