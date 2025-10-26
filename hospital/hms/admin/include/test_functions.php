<?php
include('../include/config.php');

class TestFunctions {
    private $con;
    
    public function __construct($con) {
        $this->con = $con;
    }
    
    // Get all test categories
    public function getTestCategories() {
        $query = "SELECT * FROM test_categories ORDER BY name";
        $result = mysqli_query($this->con, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get tests by category
    public function getTestsByCategory($category_id) {
        $category_id = intval($category_id);
        $query = "SELECT * FROM diagnostic_tests WHERE category_id = $category_id ORDER BY name";
        $result = mysqli_query($this->con, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get test details by ID
    public function getTestDetails($test_id) {
        $test_id = intval($test_id);
        $query = "SELECT dt.*, tc.name as category_name 
                  FROM diagnostic_tests dt
                  JOIN test_categories tc ON dt.category_id = tc.id
                  WHERE dt.id = $test_id";
        $result = mysqli_query($this->con, $query);
        return mysqli_fetch_assoc($result);
    }
    
    // Create a new test order
    public function createTestOrder($user_id, $test_ids, $payment_method = 'Wallet') {
        mysqli_begin_transaction($this->con);
        
        try {
            // Calculate total amount
            $total_amount = 0;
            $test_ids = array_map('intval', $test_ids);
            $test_list = implode(',', $test_ids);
            
            $query = "SELECT SUM(price) as total FROM diagnostic_tests WHERE id IN ($test_list)";
            $result = mysqli_query($this->con, $query);
            $total = mysqli_fetch_assoc($result);
            $total_amount = $total['total'];
            
            // Generate order number
            $order_number = 'TST-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            // Create order
            $query = "INSERT INTO test_orders (order_number, user_id, total_amount, payment_method, payment_status, status) 
                      VALUES ('$order_number', $user_id, $total_amount, '$payment_method', 'Paid', 'Pending')";
            mysqli_query($this->con, $query);
            $order_id = mysqli_insert_id($this->con);
            
            // Add ordered tests
            foreach ($test_ids as $test_id) {
                $test = $this->getTestDetails($test_id);
                $query = "INSERT INTO ordered_tests (order_id, test_id, price, status) 
                          VALUES ($order_id, $test_id, {$test['price']}, 'Pending')";
                mysqli_query($this->con, $query);
            }
            
            // Deduct from wallet
            $query = "UPDATE users SET wallet_balance = wallet_balance - $total_amount WHERE id = $user_id";
            mysqli_query($this->con, $query);
            
            mysqli_commit($this->con);
            return $order_id;
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }
    
    // Cancel a test order
    public function cancelTestOrder($order_id, $user_id) {
        mysqli_begin_transaction($this->con);
        
        try {
            // Get order details
            $query = "SELECT * FROM test_orders WHERE id = $order_id AND user_id = $user_id";
            $result = mysqli_query($this->con, $query);
            $order = mysqli_fetch_assoc($result);
            
            if (!$order || $order['status'] != 'Pending') {
                return false;
            }
            
            // Update order status
            $query = "UPDATE test_orders SET status = 'Cancelled', updated_at = NOW() WHERE id = $order_id";
            mysqli_query($this->con, $query);
            
            // Update ordered tests status
            $query = "UPDATE ordered_tests SET status = 'Cancelled' WHERE order_id = $order_id";
            mysqli_query($this->con, $query);
            
            // Refund to wallet if payment was made
            if ($order['payment_status'] == 'Paid') {
                $refund_amount = $order['total_amount'];
                $query = "UPDATE users SET wallet_balance = wallet_balance + $refund_amount WHERE id = $user_id";
                mysqli_query($this->con, $query);
            }
            
            mysqli_commit($this->con);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }
    
    // Get user's test orders
    public function getUserTestOrders($user_id) {
        $user_id = intval($user_id);
        $query = "SELECT to.*, 
                         GROUP_CONCAT(dt.name SEPARATOR ', ') as test_names,
                         COUNT(ot.id) as test_count
                  FROM test_orders to
                  JOIN ordered_tests ot ON to.id = ot.order_id
                  JOIN diagnostic_tests dt ON ot.test_id = dt.id
                  WHERE to.user_id = $user_id
                  GROUP BY to.id
                  ORDER BY to.created_at DESC";
        $result = mysqli_query($this->con, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get order details
    public function getOrderDetails($order_id, $user_id) {
        $order_id = intval($order_id);
        $user_id = intval($user_id);
        
        // Get order info
        $query = "SELECT * FROM test_orders WHERE id = $order_id AND user_id = $user_id";
        $result = mysqli_query($this->con, $query);
        $order = mysqli_fetch_assoc($result);
        
        if (!$order) {
            return false;
        }
        
        // Get ordered tests
        $query = "SELECT ot.*, dt.name as test_name, dt.description, dt.normal_range
                  FROM ordered_tests ot
                  JOIN diagnostic_tests dt ON ot.test_id = dt.id
                  WHERE ot.order_id = $order_id";
        $result = mysqli_query($this->con, $query);
        $tests = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        return [
            'order' => $order,
            'tests' => $tests
        ];
    }
}
?>