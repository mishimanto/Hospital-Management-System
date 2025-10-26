<?php
include('include/config.php');
/*session_start();

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header("Location: ../user-login.php");
    exit();
}*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Tests</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="../logo_no_bg.png?v=1.1" type="image/jpeg">
    
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

        .test-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15);
        }

        .test-header {
            background: var(--medical-blue);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .test-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .test-category {
            font-size: 1rem;
            opacity: 0.9;
        }

        .test-body {
            padding: 1.5rem;
        }

        .test-detail {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }

        .test-detail i {
            color: var(--medical-blue);
            margin-right: 10px;
            margin-top: 3px;
            min-width: 20px;
        }

        .test-price {
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

        .btn-details {
            border: 1px solid var(--medical-blue);
            color: var(--medical-blue);
            flex: 1;
        }

        .btn-book:hover {
            background: var(--medical-dark-blue);
            color: white;
        }

        .btn-details:hover {
            background: var(--light-blue);
        }

        .category-badge {
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

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Available Diagnostic Tests</h1>
            <p class="page-subtitle">Book your medical tests with ease</p>
        </div>
    </header>

    <!-- Tests Listing -->
    <div class="container">
        <!-- Search Bar -->
        <div class="search-container">
            <form id="searchForm" class="d-flex">
                <input type="text" id="searchInput" class="form-control search-input me-2" placeholder="Search tests..." aria-label="Search">
                <button class="btn btn-primary search-btn" type="submit">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($_GET['search']) || !empty($_GET['spec'])): ?>
                    <a href="test_list.php" class="btn btn-info close-btn d-flex align-items-center justify-content-center" >
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="row" id="testsContainer">
            <?php
            // Check if search query exists
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            
            // Query to fetch all tests with optional search filter
            if (!empty($search)) {
                $query = "SELECT dt.*, tc.name as category_name 
                          FROM diagnostic_tests dt
                          JOIN test_categories tc ON dt.category_id = tc.id
                          WHERE dt.name LIKE '%$search%' OR tc.name LIKE '%$search%' OR dt.description LIKE '%$search%'
                          ORDER BY dt.name";
            } else {
                $query = "SELECT dt.*, tc.name as category_name 
                          FROM diagnostic_tests dt
                          JOIN test_categories tc ON dt.category_id = tc.id
                          ORDER BY dt.name";
            }
            
            $result = mysqli_query($con, $query);
            
            if (mysqli_num_rows($result) > 0) {
                while ($test = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-lg-4 col-md-6 mb-4 test-card-item">
                            <div class="test-card">
                                <div class="test-header">
                                    <h3 class="test-name">' . htmlspecialchars($test['name']) . '</h3>
                                    <p class="test-category">' . htmlspecialchars($test['category_name']) . '</p>
                                </div>
                                <div class="test-body">
                                    <div class="test-detail">
                                        <i class="fas fa-info-circle"></i>
                                        <div>
                                            <strong>Description</strong>
                                            <p>' . htmlspecialchars(substr($test['description'], 0, 100)) . '...</p>
                                        </div>
                                    </div>
                                    
                                    <div class="test-detail">
                                        <i class="fas fa-flask"></i>
                                        <div>
                                            <strong>Preparation</strong>
                                            <p>' . htmlspecialchars(substr($test['preparation'], 0, 100)) . '...</p>
                                        </div>
                                    </div>
                                    
                                    <div class="test-detail">
                                        <i class="fas fa-chart-line"></i>
                                        <div>
                                            <strong>Normal Range</strong>
                                            <p>' . htmlspecialchars($test['normal_range']) . '</p>
                                        </div>
                                    </div>
                                    
                                    <div class="test-price">
                                        Price: <i class="fa-solid fa-bangladeshi-taka-sign"></i> 
                                        <span style="color: #CD5C5C; font-size: 18px;">' . htmlspecialchars($test['price']) . '</span>
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <a href="book_test.php?test_id=' . $test['id'] . '" class="btn btn-book btn-sm p-2">
                                            <i class="fas fa-calendar-check"></i> Book Test
                                        </a>
                                        <a href="test_details.php?test_id=' . $test['id'] . '" class="btn btn-details btn-sm p-2">
                                            <i class="fas fa-info-circle"></i> Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo '<div class="col-12 no-results">
                        <i class="fas fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                        <p>No tests found matching your search.</p>
                    </div>';
            }
            ?>
        </div>
        <div class="back-btn-container">
            <a href="../index.php" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 back-btn">
              <i class="fas fa-arrow-left"></i> Back to Home
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JavaScript for live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const testsContainer = document.getElementById('testsContainer');
            
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
                
                // Filter tests
                filterTests(searchTerm);
            });
            
            // Filter tests based on search term
            function filterTests(searchTerm) {
                const testCards = document.querySelectorAll('.test-card-item');
                let hasResults = false;

                if (searchTerm === '') {
                    // If search term is empty, show all tests
                    testCards.forEach(card => {
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
                testCards.forEach(card => {
                    const title = card.querySelector('.test-name').textContent.toLowerCase();
                    const category = card.querySelector('.test-category').textContent.toLowerCase();
                    const description = card.querySelector('.test-detail p').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm.toLowerCase()) || 
                        category.includes(searchTerm.toLowerCase()) || 
                        description.includes(searchTerm.toLowerCase())) {
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
                        testsContainer.innerHTML += `
                            <div class="col-12 no-results">
                                <i class="fas fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                                <p>No tests found matching "${searchTerm}".</p>
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
                filterTests(searchParam);
            }
        });
    </script>
</body>
</html>