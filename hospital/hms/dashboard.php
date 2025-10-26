<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

include('include/config.php');
include('include/checklogin.php');
check_login();

$user_id = $_SESSION['id'];
$query = "SELECT fullName FROM users WHERE id='$user_id'";
$result = mysqli_query($con, $query);
$user = $result ? mysqli_fetch_assoc($result) : null;
$username = $user ? htmlspecialchars($user['fullName']) : 'User';

$ambulanceQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM ambulances WHERE status = 'available'");
$availableAmbulance = $ambulanceQuery ? mysqli_fetch_assoc($ambulanceQuery)['total'] : 0;

$wallet_q = mysqli_query($con, "SELECT wallet_balance FROM users WHERE id = '$user_id'");
$balance = $wallet_q ? (mysqli_fetch_assoc($wallet_q))['wallet_balance'] : 0;

// Today's total
$todayResult = mysqli_query($con, "
    SELECT 
        (IFNULL(SUM(payment_amount),0) + 
         IFNULL((SELECT SUM(cost) FROM ambulance_bookings WHERE status='Paid' AND DATE(booking_time)=CURDATE()),0) +
         IFNULL((SELECT SUM(total_charge) FROM bed_assignments WHERE payment_status='Paid' AND DATE(created_at)=CURDATE()),0)
        ) AS today_total
    FROM appointment 
    WHERE payment_status='Paid' AND DATE(postingDate)=CURDATE()
");
$todayData = mysqli_fetch_assoc($todayResult);
$todayAmount = number_format($todayData['today_total'], 2);

// This Month's total
$monthResult = mysqli_query($con, "
    SELECT 
        (IFNULL(SUM(payment_amount),0) + 
         IFNULL((SELECT SUM(cost) FROM ambulance_bookings WHERE status='Paid' AND MONTH(booking_time)=MONTH(CURDATE()) AND YEAR(booking_time)=YEAR(CURDATE())),0) +
         IFNULL((SELECT SUM(total_charge) FROM bed_assignments WHERE payment_status='Paid' AND MONTH(created_at)=MONTH(CURDATE()) AND YEAR(created_at)=YEAR(CURDATE())),0)
        ) AS month_total
    FROM appointment 
    WHERE payment_status='Paid' AND MONTH(postingDate)=MONTH(CURDATE()) AND YEAR(postingDate)=YEAR(CURDATE())
");
$monthData = mysqli_fetch_assoc($monthResult);
$monthAmount = number_format($monthData['month_total'], 2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | <?php echo $username; ?> - Healthcare System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --light-gray: #f1f5f9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Enhanced Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 L0,100 Z" /></svg>');
            background-size: cover;
            opacity: 0.1;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-message {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.3;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .greeting-text {
            background: linear-gradient(90deg, #fff, #e0f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textShine 3s ease infinite;
        }

        .greeting-emoji {
            display: inline-block;
            animation: bounce 2s ease infinite;
        }

        .medical-icons {
            position: absolute;
            top: -20px;
            left: -20px;
            opacity: 0.1;
        }

        .medical-icons i {
            font-size: 8rem;
            position: absolute;
        }

        .medical-icons i:nth-child(1) { top: 20%; left: 10%; }
        .medical-icons i:nth-child(2) { top: 40%; left: 30%; }
        .medical-icons i:nth-child(3) { top: 60%; left: 15%; }
        .medical-icons i:nth-child(4) { top: 30%; left: 50%; }

        /* Glassmorphism Wallet Card */
        .wallet-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .wallet-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: -1;
        }

        .wallet-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            color: white;
            gap: 15px;
        }

        .wallet-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .wallet-label {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0;
            display: block;
        }

        .wallet-sub {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .wallet-body {
            margin-bottom: 1.5rem;
        }

        .wallet-amount {
            margin-bottom: 2rem;
            text-align: center;
        }

        .balance-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
            display: block;
        }

        .amount-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .currency-symbol {
            font-size: 1.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
        }

        .amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
        }

        .wallet-stats {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }

        .stat-item {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .stat-header i {
            font-size: 1rem;
        }

        .stat-value {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 5px 0;
        }

        .progress-bar-container {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            height: 100%;
            background: white;
            border-radius: 2px;
            animation: progressAnimation 2s ease-in-out infinite alternate;
        }

        .wallet-actions {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.8rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .action-btn.add-funds {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .action-btn.add-funds:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .action-btn.history {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .action-btn.history:hover {
            background: rgba(0, 0, 0, 0.3);
        }

       .wallet-decoration {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            overflow: hidden;
            pointer-events: none;
        }

        .wallet-decoration .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            background: var(--primary);
        }

        .wallet-decoration .circle-1 {
            width: 120px;
            height: 120px;
            bottom: -60px;
            left: -30px;
        }

        .wallet-decoration .circle-2 {
            width: 80px;
            height: 80px;
            bottom: -40px;
            right: -20px;
            background: var(--success);
        }

        .wallet-decoration .dotted-line {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-image: linear-gradient(to right, var(--primary) 33%, rgba(255,255,255,0) 0%);
            background-size: 12px 2px;
            background-repeat: repeat-x;
            opacity: 0.1;
        }

        .amount-display {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-top: 0.5rem;
        }

        .amount-display .currency-symbol {
            font-size: 1.8rem;
            color: var(--primary);
            margin-right: 6px;
        }

        /* Book Services Section */
        .book-services {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 3rem;
        }

        .section-title {
            font-weight: 600;
            font-size: 1.6rem;
            margin-bottom: 2rem;
            color: var(--dark);
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .service-card {
            border: none;
            border-radius: 14px;
            padding: 2rem 1.5rem;
            transition: all 0.4s ease;
            height: 100%;
            text-align: center;
            background: var(--light);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
        }

        .service-card.appointment .service-icon {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .service-card.bed .service-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .service-card.test .service-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .service-card.ambulance .service-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .service-card h3 {
            font-weight: 600;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .service-card a {
            text-decoration: none;
        }

        .service-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
            margin-top: auto;
            align-self: center;
            width: fit-content;
        }

        .service-card.appointment .service-btn {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .service-card.bed .service-btn {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .service-card.test .service-btn {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .service-card.ambulance .service-btn {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .service-card.appointment .service-btn:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .service-card.bed .service-btn:hover {
            background: var(--success);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .service-card.test .service-btn:hover {
            background: var(--warning);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .service-card.ambulance .service-btn:hover {
            background: var(--danger);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* Quick Actions - Ticket Style */
        .quick-actions {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        .ticket-card {
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.03) 0%, rgba(37, 99, 235, 0.01) 100%);
            z-index: 0;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .ticket-card.appointments {
            border-left-color: var(--primary);
        }

        .ticket-card.tests {
            border-left-color: var(--success);
        }

        .ticket-card.beds {
            border-left-color: var(--info);
        }

        .ticket-card.settings {
            border-left-color: var(--gray);
        }

        .ticket-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        .ticket-card.appointments .ticket-icon {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .ticket-card.tests .ticket-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .ticket-card.beds .ticket-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .ticket-card.settings .ticket-icon {
            background: rgba(100, 116, 139, 0.1);
            color: var(--gray);
        }

        .ticket-content {
            position: relative;
            z-index: 1;
            flex-grow: 1;
        }

        .ticket-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .ticket-desc {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        .ticket-btn {
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none !important;
            display: inline-block;
        }

        .ticket-card.appointments .ticket-btn {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .ticket-card.tests .ticket-btn {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .ticket-card.beds .ticket-btn {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .ticket-card.settings .ticket-btn {
            background: rgba(100, 116, 139, 0.1);
            color: var(--gray);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .ticket-card.appointments .ticket-btn:hover {
            background: var(--primary);
            color: white;
        }

        .ticket-card.tests .ticket-btn:hover {
            background: var(--success);
            color: white;
        }

        .ticket-card.beds .ticket-btn:hover {
            background: var(--info);
            color: white;
        }

        .ticket-card.settings .ticket-btn:hover {
            background: var(--gray);
            color: white;
        }

        /* Animations */
        @keyframes textShine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes progressAnimation {
            0% { width: 30%; }
            100% { width: 70%; }
        }

        @keyframes animated-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .animated-pulse {
            animation: animated-pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .welcome-message {
                font-size: 2rem;
            }
            
            .amount {
                font-size: 2rem;
            }
            
            .book-services, .quick-actions {
                padding: 2rem;
            }
        }

        @media (max-width: 768px) {
            .welcome-section {
                padding: 3rem 0;
                border-radius: 0 0 20px 20px;
            }
            
            .welcome-message {
                font-size: 1.8rem;
            }
            
            .wallet-stats {
                flex-direction: column;
            }
            
            .service-card {
                margin-bottom: 1.5rem;
            }
            
            .book-services, .quick-actions {
                padding: 1.75rem;
            }

            .ticket-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .ticket-icon {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-message {
                font-size: 1.6rem;
            }
            
            .greeting-emoji {
                display: none;
            }
            
            .amount {
                font-size: 1.8rem;
            }
            
            .book-services, .quick-actions {
                padding: 1.5rem;
            }
            
            .section-title {
                font-size: 1.4rem;
            }

            .wallet-actions {
                flex-direction: column;
            }
        }

        .wallet-card i.fa-bangladeshi-taka-sign {
            color: #fff;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once('include/header_logins_page.php'); ?>

    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="welcome-content">
                        <h1 class="welcome-message">
                            <span class="greeting-text">Hello, <?php echo $username; ?> !</span>
                            <span class="greeting-emoji"> 👋🏻</span>
                        </h1>
                        <div class="medical-icons">
                            <i class="fas fa-heartbeat"></i>
                            <i class="fas fa-stethoscope"></i>
                            <i class="fas fa-pills"></i>
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="wallet-card">
                        <div class="wallet-body">
                            <div class="wallet-amount">
                                <h6>
                                    <i class="fas fa-wallet text-primary me-2"></i> Wallet Balance
                                </h6>
                                <div class="amount-display">
                                    <span class="currency-symbol">
                                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                    </span>
                                    <span class="amount"><?php echo number_format($balance, 2); ?></span>
                                </div>
                            </div>

                            <div class="wallet-stats">
                                <div class="stat-item">
                                    <div class="stat-header">
                                        <i class="fas fa-arrow-up text-primary"></i>
                                        <span>Today</span>
                                    </div>
                                    <div class="stat-value">
                                        <i class="fa-solid fa-bangladeshi-taka-sign me-1"></i>
                                        <?php echo $todayAmount; ?>
                                    </div>
                                </div>

                                <div class="stat-item">
                                    <div class="stat-header">
                                        <i class="fas fa-history text-primary"></i>
                                        <span>This Month</span>
                                    </div>
                                    <div class="stat-value">
                                        <i class="fa-solid fa-bangladeshi-taka-sign me-1"></i>
                                        <?php echo $monthAmount; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wallet-actions">
                            <a href="add-money.php" class="action-btn add-funds">
                                <i class="fas fa-plus-circle"></i><b style="font-size: 17px;">Top Up</b>
                            </a>
                        </div>

                        <div class="wallet-decoration">
                            <div class="circle circle-1"></div>
                            <div class="circle circle-2"></div>
                            <div class="dotted-line"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <!-- Book Services Section -->
        <div class="book-services">
            <h2 class="section-title">Book Services</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="service-card appointment">
                        <div class="service-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <h3>Doctor Appointment</h3>
                        <p>Book specialist doctors</p>
                        <a href="book-appointment.php" class="service-btn">
                            <i class="fas fa-plus me-2"></i> Book Now
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card bed">
                        <div class="service-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <h3>Beds</h3>
                        <p>Reserve beds for treatment</p>
                        <a href="book_bed.php" class="service-btn">
                            <i class="fas fa-plus me-2"></i> Book Now
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card test">
                        <div class="service-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>Diagnostic Test</h3>
                        <p>Schedule diagnostic tests</p>
                        <a href="book_test.php" class="service-btn">
                            <i class="fas fa-plus me-2"></i> Book Now
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="service-card ambulance">
                        <div class="service-icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h3>Emergency Ambulance</h3>
                        <p><b><?php echo $availableAmbulance; ?></b> available</p>
                        <a href="book_ambulance.php" class="service-btn">
                            <i class="fas fa-plus me-2"></i> Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - Ticket Style -->
        <div class="quick-actions mb-5">
            <h2 class="section-title">Quick Access</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="ticket-card appointments">
                        <div class="ticket-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="ticket-content">
                            <h4 class="ticket-title">My Appointments</h4>
                            <a href="appointment-history.php" class="ticket-btn">
                                View Appointments <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="ticket-card tests">
                        <div class="ticket-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="ticket-content">
                            <h4 class="ticket-title">My Test Results</h4>
                            <a href="order_details.php" class="ticket-btn">
                                View Results <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="ticket-card beds">
                        <div class="ticket-icon">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <div class="ticket-content">
                            <h4 class="ticket-title">My Bed Bookings</h4>
                            <a href="my_bed_bookings.php" class="ticket-btn">
                                View Bookings <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="col-lg-6">
                    <div class="ticket-card settings">
                        <div class="ticket-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="ticket-content">
                            <h4 class="ticket-title">Account Settings</h4>
                            <a href="settings.php" class="ticket-btn">
                                Go to Settings <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('include/footer.php'); ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display current time
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('current-time').textContent = now.toLocaleDateString('en-US', options);
        }
        
        updateTime();
        setInterval(updateTime, 60000);
        
        // Animation for cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.service-card, .ticket-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>