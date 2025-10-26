<?php
// search_results.php
include('include/config.php');

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the query
$query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');

// You would typically search multiple tables (doctors, services, etc.)
$doctorResults = [];
$serviceResults = [];
$departmentResults = [];

if (!empty($query)) {
    // Search doctors
    $sql = "SELECT id, fullName, specialization FROM doctors WHERE fullName LIKE ? OR specialization LIKE ? LIMIT 5";
    $stmt = $con->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctorResults = $result->fetch_all(MYSQLI_ASSOC);
    
    // Search services (similar queries for other tables)
    // ...
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results for "<?php echo $query; ?>"</title>
    <!-- Include your CSS -->
</head>
<body>
    <div class="container">
        <h2>Search Results for "<?php echo $query; ?>"</h2>
        
        <?php if (!empty($doctorResults)): ?>
        <div class="search-section">
            <h3>Doctors</h3>
            <div class="row">
                <?php foreach ($doctorResults as $doctor): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5><?php echo $doctor['fullName']; ?></h5>
                            <p><?php echo $doctor['specialization']; ?></p>
                            <a href="hms/doctor-profile.php?id=<?php echo $doctor['id']; ?>" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Repeat for other result types -->
        
        <?php if (empty($doctorResults) && empty($serviceResults) && empty($departmentResults)): ?>
        <div class="alert alert-info">
            No results found for "<?php echo $query; ?>". Please try different keywords.
        </div>
        <?php endif; ?>
    </div>
</body>
</html>