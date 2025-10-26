<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Fetch wards with their available beds
$wards = $con->query("
    SELECT w.ward_id, w.ward_name, w.ward_type, w.description,
           COUNT(b.bed_id) as available_beds,
           GROUP_CONCAT(b.bed_id) as bed_ids
    FROM wards w
    LEFT JOIN beds b ON w.ward_id = b.ward_id AND b.status = 'Available'
    GROUP BY w.ward_id
    ORDER BY w.ward_name
");

// Prepare ward data with beds
$ward_data = [];
while ($ward = $wards->fetch_assoc()) {
    $ward['beds'] = [];
    if ($ward['available_beds'] > 0) {
        $beds = $con->query("
            SELECT * FROM beds 
            WHERE bed_id IN ({$ward['bed_ids']})
            ORDER BY bed_number
        ");
        while ($bed = $beds->fetch_assoc()) {
            $ward['beds'][] = $bed;
        }
    }
    $ward_data[] = $ward;
}

// Handle bed booking
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_bed'])){
    $bed_id = intval($_POST['bed_id']);
    $patient_name = $con->real_escape_string($_POST['patient_name']);
    $contact_no = $con->real_escape_string($_POST['contact_no']);
    $gender = $con->real_escape_string($_POST['gender']);
    $address = $con->real_escape_string($_POST['address']);
    $admission_date = date('Y-m-d H:i:s');
    
    // Get bed price
    $bed_query = $con->query("SELECT price_per_day FROM beds WHERE bed_id = $bed_id");
    $bed_data = $bed_query->fetch_assoc();
    $price_per_day = $bed_data['price_per_day'];
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Insert patient record
        $stmt = $con->prepare("INSERT INTO patient (user_id, PatientName, PatientContno, PatientGender, PatientAdd) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $_SESSION['id'], $patient_name, $contact_no, $gender, $address);
        $stmt->execute();
        $patient_id = $con->insert_id;
        
        // Update bed status
        $stmt = $con->prepare("UPDATE beds SET status = 'Occupied' WHERE bed_id = ?");
        $stmt->bind_param("i", $bed_id);
        $stmt->execute();
        
        // Create bed assignment with price
        $stmt = $con->prepare("INSERT INTO bed_assignments (bed_id, patient_id, user_id, admission_date, price_per_day) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisd", $bed_id, $patient_id, $_SESSION['id'], $admission_date, $price_per_day);
        $stmt->execute();
        
        $con->commit();
        $_SESSION['success'] = "Bed booked successfully!";
        header("Location: my_bed_bookings.php");
        exit();
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = "Error booking bed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Hospital Bed | MEDIZEN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 18px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .ward-card {
            margin-bottom: 24px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .ward-header {
            background-color: #f8f9fa;
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .ward-header:hover {
            background-color: #f1f3f5;
        }
        
        .ward-header.collapsed .fa-chevron-down {
            transform: rotate(-90deg);
        }
        
        .ward-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 4px;
        }
        
        .ward-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .ward-badge {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .bed-card {
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: white;
            margin-bottom: 12px;
        }
        
        .bed-card:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.15);
        }
        
        .bed-card.selected {
            border-color: var(--secondary-color);
            background-color: rgba(52, 152, 219, 0.05);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .price-tag {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--secondary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1;
        }
        
        .bed-type {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .general-bed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .icu-bed {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .private-bed {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .semi-private-bed {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background-color: #219653;
            border-color: #219653;
            transform: translateY(-1px);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.2);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }
        
        .section-title {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 24px;
            font-weight: 600;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background-color: var(--secondary-color);
            border-radius: 3px;
        }
        
        .status-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .available {
            background-color: #d4edda;
            color: #155724;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: #6c757d;
            padding: 0 16px;
        }
        
        .input-group-text i {
            width: 16px;
            text-align: center;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .form-section {
            margin-bottom: 24px;
        }
        
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }
        
        .form-section-title i {
            margin-right: 10px;
            color: var(--secondary-color);
        }
        
        hr {
            border-top: 1px solid #e0e0e0;
            opacity: 0.5;
            margin: 24px 0;
        }
        
        .badge-count {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 500;
        }
        
        .no-beds {
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 12px;
            text-align: center;
        }
        
        .no-beds i {
            font-size: 2.5rem;
            color: #adb5bd;
            margin-bottom: 15px;
        }
        
        .no-beds h5 {
            color: #6c757d;
            font-weight: 500;
        }
        
        .no-beds p {
            color: #adb5bd;
            font-size: 0.95rem;
        }
        
        .ward-body {
            padding: 16px;
            background-color: #f8f9fa;
        }
        
        .ward-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .fa-chevron-down {
            transition: transform 0.2s ease;
        }
        
        @media (max-width: 991.98px) {
            .card {
                margin-bottom: 24px;
            }
            
            .form-control, .form-select {
                padding: 10px 14px;
            }
        }
        h4
        {
            padding: 5px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include('include/header_logins_page.php'); ?>
    
    <div class="container py-5">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-lg">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-6 px-4 mb-4">
                <div class="card">
                    <div class="card-header p-2 bg-secondary">
                        <h4 class="mb-0 text-light"><i class="fas fa-bed me-2"></i>Ward</h4>
                        <?php 
                            $total_beds = 0;
                            foreach ($ward_data as $ward) {
                                $total_beds += $ward['available_beds'];
                            }
                        ?>
                        <!-- <span class="badge badge-count"><?= $total_beds ?> Beds Available</span> -->
                    </div>
                    <div class="card-body p-4">
                    <?php if(count($ward_data) > 0): ?>
                        <div class="accordion" id="wardsAccordion">
                            <?php foreach ($ward_data as $ward): ?>
                            <div class="card ward-card my-3">
                                <div class="ward-header collapsed"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#ward-<?= $ward['ward_id'] ?>"
                                     aria-expanded="false">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="ward-title mb-1">
                                                <?= htmlspecialchars($ward['ward_name']) ?>
                                                <span class="ward-subtitle">(<?= $ward['ward_type'] ?>)</span>
                                            </h5>
                                            <p class="ward-subtitle mb-0"><?= $ward['description'] ?></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="ward-badge me-3">
                                                <?= $ward['available_beds'] ?> Available
                                            </span>
                                            <i class="fas fa-chevron-down text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div id="ward-<?= $ward['ward_id'] ?>"
                                     class="collapse"
                                     data-bs-parent="#wardsAccordion">
                                    <div class="ward-body">
                                        <?php if($ward['available_beds'] > 0): ?>
                                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                                <?php foreach ($ward['beds'] as $bed):
                                                    $bed_type_class = strtolower(str_replace(' ', '-', $bed['bed_type'])).'-bed';
                                                ?>
                                                <div class="col">
                                                    <div class="card bed-card h-100"
                                                         onclick="selectBed(
                                                            <?= $bed['bed_id'] ?>,
                                                            '<?= htmlspecialchars($ward['ward_name']) ?>',
                                                            '<?= htmlspecialchars($bed['bed_number']) ?>',
                                                            '<?= $bed['bed_type'] ?>',
                                                            '<?= number_format($bed['price_per_day'], 2) ?>'
                                                         )"
                                                         id="bed-<?= $bed['bed_id'] ?>">
                                                        <div class="price-tag">
                                                            <i class="fas fa-tag me-1"></i><?= number_format($bed['price_per_day'], 2) ?>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <span class="bed-type <?= $bed_type_class ?>"><?= $bed['bed_type'] ?></span>
                                                            <h5 class="card-title mb-2 fw-semibold"><?= htmlspecialchars($bed['bed_number']) ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="no-beds">
                                                <i class="fas fa-bed"></i>
                                                <h5>No available beds in this ward</h5>
                                                <p>All beds are currently occupied. Please check back later.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-beds">
                            <i class="fas fa-bed"></i>
                            <h5>No wards available</h5>
                            <p>There are currently no wards with available beds.</p>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header p-2 bg-secondary">
                        <h4 class="mb-0 text-light"><i class="fas fa-calendar-check me-2"></i>Book New Bed</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" id="bookingForm">
                            <input type="hidden" name="bed_id" id="bed_id">
                            
                            <div class="form-section py-3">
                                <div class="form-section-title">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Bed Information</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hospital"></i></span>
                                            <input type="text" id="ward_name" class="form-control" placeholder="Ward" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-bed"></i></span>
                                            <input type="text" id="bed_number" class="form-control" placeholder="Bed" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="text" id="bed_type" class="form-control" placeholder="Bed type" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-bangladeshi-taka-sign"></i></span>
                                            <input type="text" id="bed_price" class="form-control" placeholder="Price per day" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-section py-3">
                                <div class="form-section-title">
                                    <i class="fas fa-user-injured"></i>
                                    <span>Patient Information</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="patient_name" class="form-control" placeholder="Full name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="contact_no" class="form-control" placeholder="Contact number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            <select name="gender" class="form-select" required>
                                                <option value="" selected disabled>Select gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <textarea name="address" class="form-control" rows="2" placeholder="Patient's address" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" name="book_bed" class="btn btn-success btn-lg" disabled id="bookBtn">
                                    Confirm Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>

    <script>
    // Bed select function (already there)
    function selectBed(bedId, wardName, bedNumber, bedType, bedPrice) {
        document.getElementById('bed_id').value = bedId;
        document.getElementById('ward_name').value = wardName;
        document.getElementById('bed_number').value = bedNumber;
        document.getElementById('bed_type').value = bedType;
        document.getElementById('bed_price').value = bedPrice;
        document.getElementById('bookBtn').disabled = false;
        document.querySelectorAll('.bed-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.getElementById('bed-' + bedId).classList.add('selected');
        if (window.innerWidth < 992) {
            document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Accordion chevron toggle
    var wardAccordion = document.getElementById('wardsAccordion');
    wardAccordion.addEventListener('show.bs.collapse', function (e) {
        let header = document.querySelector('[data-bs-target="#' + e.target.id + '"]');
        header.classList.remove('collapsed');
        header.querySelector('.fa-chevron-down').style.transform = 'rotate(0deg)';
    });
    wardAccordion.addEventListener('hide.bs.collapse', function (e) {
        let header = document.querySelector('[data-bs-target="#' + e.target.id + '"]');
        header.classList.add('collapsed');
        header.querySelector('.fa-chevron-down').style.transform = 'rotate(-90deg)';
    });
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>