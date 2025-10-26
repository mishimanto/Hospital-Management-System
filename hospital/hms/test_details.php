<?php
include('include/config.php');
session_start();

if(!isset($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

$test_id = intval($_GET['test_id']);

// Fetch test details with category
$query = "SELECT dt.*, tc.name as category_name 
          FROM diagnostic_tests dt
          JOIN test_categories tc ON dt.category_id = tc.id
          WHERE dt.id = $test_id";
$result = mysqli_query($con, $query);
$test = mysqli_fetch_assoc($result);

if(!$test) {
    header("Location: test_list.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Details - <?php echo htmlspecialchars($test['name']); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .test-card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .test-header {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .price-badge {
            font-size: 1.2rem;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card test-card">
                    <div class="card-header test-header">
                        <h3 class="mb-0 text-center"><?php echo htmlspecialchars($test['name']); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Category:</span>
                                    <p><?php echo htmlspecialchars($test['category_name']); ?></p>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Description:</span>
                                    <p><?php echo htmlspecialchars($test['description']); ?></p>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Preparation:</span>
                                    <p><?php echo htmlspecialchars($test['preparation']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Normal Range:</span>
                                    <p><?php echo htmlspecialchars($test['normal_range']); ?></p>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Price:</span>
                                    <div class="price-badge bg-light text-danger rounded d-inline-block">
                                        <i class="fas fa-bangladeshi-taka-sign"></i> <?php echo number_format($test['price'], 2); ?>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Last Updated:</span>
                                    <p><?php echo date('M d, Y', strtotime($test['updated_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="book_test.php?test_id=<?php echo $test['id']; ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-check"></i> Book This Test
                            </a>
                            <a href="test_list.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Back to Tests
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>