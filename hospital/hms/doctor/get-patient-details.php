<?php
session_start();
include('include/config.php');

if(isset($_POST['user_id']) && isset($_POST['appointment_number'])) {
    $userId = $_POST['user_id'];
    $appointmentNumber = $_POST['appointment_number'];
    
    // Verify the appointment exists and belongs to this user
    $verifyQuery = mysqli_query($con, "SELECT * FROM appointment WHERE 
                                     appointment_number = '$appointmentNumber' AND 
                                     userId = '$userId'");
    
    if(mysqli_num_rows($verifyQuery) > 0) {
        // Get user details from users table
        $userQuery = mysqli_query($con, "SELECT * FROM users WHERE id = '$userId'");
        if(mysqli_num_rows($userQuery) > 0) {
            $user = mysqli_fetch_assoc($userQuery);
            
            // Calculate age if date of birth is available
            $age = '';
            if(isset($user['dob']) && !empty($user['dob'])) {
                $dob = new DateTime($user['dob']);
                $now = new DateTime();
                $age = $now->diff($dob)->y;
            }
            
            $response = [
                'status' => 'success',
                'data' => [
                    'id' => $user['id'],
                    'fullName' => $user['fullName'],
                    'email' => $user['email'],
                    'gender' => $user['gender'],
                    'address' => $user['address'],
                    'city' => $user['city'],
                    'age' => $age,
                    'contactno' => $user['contactno'] ?? ''
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'User not found'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Appointment not found or does not belong to this user'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>