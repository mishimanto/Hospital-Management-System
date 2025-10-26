<?php
include('include/config.php');
session_start();

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header("Location: ../user-login.php");
    exit();
}

// Check if order_id is provided
if(!isset($_GET['order_id'])) {
    header("Location: my_tests.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['id'];

// Fetch order details
$order_query = "SELECT * FROM test_orders WHERE id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($con, $order_query);
$order = mysqli_fetch_assoc($order_result);

if(!$order) {
    header("Location: my_tests.php");
    exit();
}

// Fetch ordered tests
$tests_query = "SELECT ot.*, dt.name as test_name, dt.description, dt.normal_range
                FROM ordered_tests ot
                JOIN diagnostic_tests dt ON ot.test_id = dt.id
                WHERE ot.order_id = $order_id";
$tests_result = mysqli_query($con, $tests_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - <?php echo htmlspecialchars($order['order_number']); ?></title>
    
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
        .order-header {
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .test-card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .test-header {
            background-color: #e9f7fe;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }
        .status-badge {
            font-size: 0.9rem;
        }
        .result-text {
            white-space: pre-line;
        }
    </style>
</head>
<body>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Order #<?php echo htmlspecialchars($order['order_number']); ?></h3>
                        <span class="badge 
                            <?php echo $order['status'] == 'Completed' ? 'bg-success' : 
                                  ($order['status'] == 'Cancelled' ? 'bg-danger' : 'bg-warning'); ?> status-badge">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></p>
                            <p><strong>Total Amount:</strong> ৳<?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="<?php echo $order['payment_status'] == 'Paid' ? 'text-success' : 'text-warning'; ?>">
                                    <?php echo htmlspecialchars($order['payment_status']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <?php if($order['status'] == 'Completed'): ?>
                                <p><strong>Completed At:</strong> <?php echo date('M d, Y h:i A', strtotime($order['updated_at'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <h4 class="mb-3">Ordered Tests</h4>
                
                <?php while($test = mysqli_fetch_assoc($tests_result)): ?>
                    <div class="card test-card mb-3">
                        <div class="test-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($test['test_name']); ?></h5>
                                <span class="badge 
                                    <?php echo $test['status'] == 'Completed' ? 'bg-success' : 
                                          ($test['status'] == 'Cancelled' ? 'bg-danger' : 'bg-warning'); ?> status-badge">
                                    <?php echo htmlspecialchars($test['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Description:</strong> <?php echo htmlspecialchars($test['description']); ?></p>
                                    <p><strong>Normal Range:</strong> <?php echo htmlspecialchars($test['normal_range']); ?></p>
                                    <p><strong>Price:</strong> ৳<?php echo number_format($test['price'], 2); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <?php if($test['status'] == 'Completed' && !empty($test['result'])): ?>
                                        <div class="mb-3">
                                            <h6>Test Result</h6>
                                            <div class="bg-light p-3 rounded result-text">
                                                <?php echo htmlspecialchars($test['result']); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                
                <div class="text-center mt-4">
                    <a href="my_tests.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to My Tests
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>