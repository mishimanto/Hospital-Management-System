<?php
session_start();
include('include/config.php');

$status = isset($_GET['status']) ? $_GET['status'] : 'Pending';

$query = "SELECT `to`.order_number, `to`.created_at, u.fullName, 
          ot.id as test_id, ot.test_id as test_type_id, dt.name as test_name,
          ot.status, ot.result, ot.completed_at
          FROM test_orders `to`
          JOIN ordered_tests ot ON `to`.id = ot.order_id
          JOIN diagnostic_tests dt ON ot.test_id = dt.id
          JOIN users u ON `to`.user_id = u.id";

if ($status != 'All') {
    $query .= " WHERE ot.status = '$status'";
}

$query .= " ORDER BY to.created_at DESC";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

if (mysqli_num_rows($result) > 0) {
    $current_order = '';
    
    while ($row = mysqli_fetch_assoc($result)) {
        if ($current_order != $row['order_number']) {
            if ($current_order != '') {
                echo '</div>'; // Close previous order card
            }
            
            echo '<div class="test-card">';
            echo '<div class="test-header">';
            echo '<h4>Order #: ' . $row['order_number'] . '</h4>';
            echo '<p>Patient: ' . $row['fullName'] . '</p>';
            echo '<p>Order Date: ' . date('M d, Y h:i A', strtotime($row['created_at'])) . '</p>';
            echo '</div>';
            
            $current_order = $row['order_number'];
        }
        
        echo '<div class="test-item">';
        echo '<h5>' . $row['test_name'] . '</h5>';
        echo '<p>Status: <span class="label label-' . ($row['status'] == 'Completed' ? 'success' : 'warning') . '">' . $row['status'] . '</span></p>';
        
        if ($row['status'] == 'Completed') {
            echo '<div class="well">';
            echo '<strong>Result:</strong><br>';
            echo nl2br($row['result']);
            echo '<br><small>Completed on: ' . date('M d, Y h:i A', strtotime($row['completed_at'])) . '</small>';
            echo '</div>';
        } else {
            echo '<button class="btn btn-primary btn-xs enter-result-btn" 
                  data-test-id="' . $row['test_id'] . '"
                  data-test-name="' . $row['test_name'] . '">
                  Enter Result
                  </button>';
        }
        
        echo '</div>';
        echo '<hr>';
    }
    
    echo '</div>'; // Close last order card
} else {
    echo '<div class="alert alert-info">No test orders found with status: ' . $status . '</div>';
}
?>