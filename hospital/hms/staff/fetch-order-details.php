<?php
include('include/config.php');

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']); // Ensuring integer type

    $user_html = '';
    $test_html = '';

    // 1. Fetch User Info
    $user_sql = "
        SELECT u.fullName, u.email,  u.gender,  u.address
        FROM test_orders t
        JOIN users u ON u.id = t.user_id
        WHERE t.id = '$order_id'
    ";
    $user_q = mysqli_query($con, $user_sql);

    if (!$user_q) {
        echo "<div class='alert alert-danger'>Error fetching user info: " . mysqli_error($con) . "</div>";
        exit;
    }

    if ($user = mysqli_fetch_assoc($user_q)) {
        $user_html .= "<div class='card'>";
        $user_html .= "<h5 class='text-primary'><i class='fa fa-user'></i> Patient Info</h5>";
        $user_html .= "<p><strong>Name:</strong> {$user['fullName']}<br>";
        $user_html .= "<strong>Email:</strong> {$user['email']}<br>";
        
        $user_html .= "<strong>Gender:</strong> {$user['gender']}<br>";
        
        $user_html .= "<strong>Address:</strong> {$user['address']}</p>";
        $user_html .= "</div>";

    } else {
        $user_html .= "<div class='alert alert-warning'>No user info found for this order.</div>";
    }

    // 2. Fetch Tests for This Order
    $test_sql = "
        SELECT ot.id AS ordered_test_id, dt.name AS test_name
        FROM ordered_tests ot
        JOIN diagnostic_tests dt ON ot.test_id = dt.id
        WHERE ot.order_id = '$order_id' AND ot.status != 'Completed'
    ";
    $test_q = mysqli_query($con, $test_sql);

    if (!$test_q) {
        echo "<div class='alert alert-danger'>Error fetching test list: " . mysqli_error($con) . "</div>";
        exit;
    }

    if (mysqli_num_rows($test_q) > 0) {
        $test_html .= '<div class="form-group">';
        $test_html .= '<label>Select Test</label>';
        $test_html .= '<select name="ordered_test_id" class="form-control" required>';
        $test_html .= '<option value="">-- Select Test --</option>';
        while ($test = mysqli_fetch_assoc($test_q)) {
            $test_html .= "<option value='{$test['ordered_test_id']}'>{$test['test_name']}</option>";
        }
        $test_html .= '</select></div>';
    } else {
        $test_html .= '<div class="alert alert-warning">No pending tests for this order.</div>';
    }

    // Combine and return via JS
    echo $user_html . '---SPLIT---' . $test_html;
}
?>
