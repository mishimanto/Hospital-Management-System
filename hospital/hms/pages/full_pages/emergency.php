<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['id'])==0) {
    header('location:logout.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Department | MediZen Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --emergency-red: #d90429;
            --emergency-dark: #2b2d42;
            --emergency-light: #edf2f4;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .emergency-header {
            background: linear-gradient(135deg, var(--emergency-red), #ef233c);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .emergency-card {
            border-left: 4px solid var(--emergency-red);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .emergency-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .priority-critical {
            border-left: 4px solid #d90429;
        }
        .priority-high {
            border-left: 4px solid #f77f00;
        }
        .priority-medium {
            border-left: 4px solid #fcbf49;
        }
        .priority-low {
            border-left: 4px solid #2ec4b6;
        }
        .status-admitted {
            color: #2ec4b6;
            font-weight: bold;
        }
        .status-pending {
            color: #f77f00;
            font-weight: bold;
        }
        .status-discharged {
            color: #6c757d;
            font-weight: bold;
        }
        .vital-sign {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .normal-vital {
            color: #2ec4b6;
        }
        .abnormal-vital {
            color: var(--emergency-red);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        .triage-badge {
            font-size: 0.8rem;
            padding: 0.35rem 0.5rem;
            border-radius: 0.25rem;
        }
        .bed-available {
            background-color: #2ec4b6;
            color: white;
        }
        .bed-occupied {
            background-color: var(--emergency-red);
            color: white;
        }
    </style>
</head>
<body>
    <?php include('include/header1.php'); ?>

    <div class="emergency-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-ambulance me-2"></i> Emergency Department</h1>
                    <p class="mb-0">Real-time monitoring and management of emergency cases</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#newCaseModal">
                        <i class="fas fa-plus-circle me-1"></i> New Case
                    </button>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#bedStatusModal">
                        <i class="fas fa-procedures me-1"></i> Bed Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Emergency Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h2 class="text-primary"><?php 
                                        $query = "SELECT COUNT(*) FROM emergency_cases WHERE status='Pending'";
                                        $result = mysqli_query($con, $query);
                                        echo mysqli_fetch_array($result)[0];
                                    ?></h2>
                                    <small class="text-muted">Pending Cases</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h2 class="text-success"><?php 
                                        $query = "SELECT COUNT(*) FROM emergency_cases WHERE status='Admitted'";
                                        $result = mysqli_query($con, $query);
                                        echo mysqli_fetch_array($result)[0];
                                    ?></h2>
                                    <small class="text-muted">Admitted</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <h2 class="text-danger"><?php 
                                        $query = "SELECT COUNT(*) FROM emergency_cases WHERE priority='Critical'";
                                        $result = mysqli_query($con, $query);
                                        echo mysqli_fetch_array($result)[0];
                                    ?></h2>
                                    <small class="text-muted">Critical</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <h2 class="text-warning"><?php 
                                        $query = "SELECT COUNT(*) FROM emergency_beds WHERE status='Available'";
                                        $result = mysqli_query($con, $query);
                                        echo mysqli_fetch_array($result)[0];
                                    ?></h2>
                                    <small class="text-muted">Beds Available</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Alerts</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php
                            $query = "SELECT * FROM emergency_alerts ORDER BY alert_time DESC LIMIT 5";
                            $result = mysqli_query($con, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">'.$row['alert_type'].'</h6>
                                        <small>'.date('h:i A', strtotime($row['alert_time'])).'</small>
                                    </div>
                                    <p class="mb-1">'.$row['alert_message'].'</p>
                                    <small>'.$row['patient_name'].'</small>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Active Cases</h5>
                        <div>
                            <select class="form-select form-select-sm" id="filterPriority" style="width: 150px; display: inline-block;">
                                <option value="all">All Priorities</option>
                                <option value="Critical">Critical</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                            <select class="form-select form-select-sm ms-2" id="filterStatus" style="width: 150px; display: inline-block;">
                                <option value="all">All Statuses</option>
                                <option value="Pending">Pending</option>
                                <option value="Admitted">Admitted</option>
                                <option value="Discharged">Discharged</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Case ID</th>
                                        <th>Patient</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Arrival Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="emergencyCasesTable">
                                    <?php
                                    $query = "SELECT ec.*, p.PatientName, p.PatientContno 
                                              FROM emergency_cases ec
                                              LEFT JOIN tblpatient p ON ec.patient_id = p.ID
                                              ORDER BY ec.priority DESC, ec.arrival_time DESC";
                                    $result = mysqli_query($con, $query);
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>
                                            <td>'.$row['case_id'].'</td>
                                            <td>
                                                <strong>'.$row['PatientName'].'</strong><br>
                                                <small class="text-muted">'.$row['PatientContno'].'</small>
                                            </td>
                                            <td><span class="badge bg-'.strtolower($row['priority']).'">'.$row['priority'].'</span></td>
                                            <td><span class="status-'.strtolower($row['status']).'">'.$row['status'].'</span></td>
                                            <td>'.date('h:i A', strtotime($row['arrival_time'])).'</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-case" data-id="'.$row['id'].'">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success ms-1 admit-case" data-id="'.$row['id'].'" '.($row['status']=='Admitted'?'disabled':'').'>
                                                    <i class="fas fa-procedures"></i>
                                                </button>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Emergency Department Analytics</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="emergencyStatsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Case Modal -->
    <div class="modal fade" id="newCaseModal" tabindex="-1" aria-labelledby="newCaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newCaseModalLabel"><i class="fas fa-plus-circle me-2"></i>New Emergency Case</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="newCaseForm" action="save-emergency-case.php" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patientSearch" class="form-label">Search Patient</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="patientSearch" placeholder="Search by name or ID">
                                        <button class="btn btn-outline-secondary" type="button" id="searchPatientBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div id="patientResults" class="mt-2 d-none">
                                        <select class="form-select" id="patientSelect" name="patient_id">
                                            <option value="">Select Patient</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="newPatientName" class="form-label">Or Register New Patient</label>
                                    <input type="text" class="form-control" id="newPatientName" name="new_patient_name" placeholder="Full Name">
                                </div>
                                <div class="mb-3">
                                    <label for="newPatientContact" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="newPatientContact" name="new_patient_contact" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority Level</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="Critical">Critical (Immediate attention)</option>
                                        <option value="High">High (Urgent)</option>
                                        <option value="Medium">Medium (Semi-urgent)</option>
                                        <option value="Low">Low (Non-urgent)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="complaint" class="form-label">Chief Complaint</label>
                                    <textarea class="form-control" id="complaint" name="complaint" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="triageNotes" class="form-label">Triage Notes</label>
                                    <textarea class="form-control" id="triageNotes" name="triage_notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mt-3 mb-2">Vital Signs</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vitalBP" class="form-label">BP (mmHg)</label>
                                            <input type="text" class="form-control" id="vitalBP" name="vital_bp" placeholder="120/80">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vitalHR" class="form-label">HR (bpm)</label>
                                            <input type="number" class="form-control" id="vitalHR" name="vital_hr" placeholder="72">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vitalTemp" class="form-label">Temp (°C)</label>
                                            <input type="number" step="0.1" class="form-control" id="vitalTemp" name="vital_temp" placeholder="36.6">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vitalO2" class="form-label">SpO2 (%)</label>
                                            <input type="number" class="form-control" id="vitalO2" name="vital_o2" placeholder="98">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Case</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bed Status Modal -->
    <div class="modal fade" id="bedStatusModal" tabindex="-1" aria-labelledby="bedStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="bedStatusModalLabel"><i class="fas fa-procedures me-2"></i>Emergency Bed Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php
                        $query = "SELECT * FROM emergency_beds ORDER BY bed_number";
                        $result = mysqli_query($con, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center '.($row['status']=='Available'?'bg-success':'bg-danger').' text-white">
                                        <strong>Bed '.$row['bed_number'].'</strong>
                                        <span class="badge bg-light text-dark">'.$row['bed_type'].'</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Status:</strong> <span class="text-'.($row['status']=='Available'?'success':'danger').'">'.$row['status'].'</span></p>';
                            if($row['status'] == 'Occupied') {
                                $patientQuery = "SELECT p.PatientName FROM emergency_cases ec 
                                               JOIN tblpatient p ON ec.patient_id = p.ID 
                                               WHERE ec.bed_number = '".$row['bed_number']."' AND ec.status='Admitted'";
                                $patientResult = mysqli_query($con, $patientQuery);
                                $patient = mysqli_fetch_assoc($patientResult);
                                echo '<p class="mb-1"><strong>Patient:</strong> '.$patient['PatientName'].'</p>
                                      <p class="mb-0"><strong>Since:</strong> '.date('M j, h:i A', strtotime($row['occupied_since'])).'</p>';
                            }
                            echo '</div>
                                    <div class="card-footer bg-light">
                                        <button class="btn btn-sm btn-outline-primary bed-details" data-id="'.$row['id'].'">
                                            <i class="fas fa-info-circle me-1"></i> Details
                                        </button>';
                            if($row['status'] == 'Occupied') {
                                echo '<button class="btn btn-sm btn-outline-success float-end discharge-patient" data-id="'.$row['id'].'">
                                        <i class="fas fa-sign-out-alt me-1"></i> Discharge
                                      </button>';
                            }
                            echo '</div>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Details Modal -->
    <div class="modal fade" id="caseDetailsModal" tabindex="-1" aria-labelledby="caseDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="caseDetailsModalLabel"><i class="fas fa-file-medical me-2"></i>Case Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="caseDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary print-case"><i class="fas fa-print me-1"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Chart
            const ctx = document.getElementById('emergencyStatsChart').getContext('2d');
            const emergencyStatsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Critical', 'High', 'Medium', 'Low'],
                    datasets: [{
                        label: 'Cases by Priority (Last 24 hrs)',
                        data: [12, 19, 8, 5],
                        backgroundColor: [
                            'rgba(217, 4, 41, 0.7)',
                            'rgba(247, 127, 0, 0.7)',
                            'rgba(252, 191, 73, 0.7)',
                            'rgba(46, 196, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgba(217, 4, 41, 1)',
                            'rgba(247, 127, 0, 1)',
                            'rgba(252, 191, 73, 1)',
                            'rgba(46, 196, 182, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Patient Search
            $('#searchPatientBtn').click(function() {
                const searchTerm = $('#patientSearch').val();
                if(searchTerm.length > 2) {
                    $.ajax({
                        url: 'search-patients.php',
                        method: 'POST',
                        data: { search: searchTerm },
                        success: function(response) {
                            $('#patientResults').removeClass('d-none');
                            $('#patientSelect').html(response);
                        }
                    });
                }
            });

            // View Case Details
            $('.view-case').click(function() {
                const caseId = $(this).data('id');
                $.ajax({
                    url: 'get-case-details.php',
                    method: 'POST',
                    data: { case_id: caseId },
                    success: function(response) {
                        $('#caseDetailsContent').html(response);
                        $('#caseDetailsModal').modal('show');
                    }
                });
            });

            // Admit Patient
            $('.admit-case').click(function() {
                const caseId = $(this).data('id');
                if(confirm('Admit this patient to emergency department?')) {
                    $.ajax({
                        url: 'admit-patient.php',
                        method: 'POST',
                        data: { case_id: caseId },
                        success: function(response) {
                            if(response.success) {
                                alert('Patient admitted successfully');
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        }
                    });
                }
            });

            // Discharge Patient
            $('.discharge-patient').click(function() {
                const bedId = $(this).data('id');
                if(confirm('Discharge patient from this bed?')) {
                    $.ajax({
                        url: 'discharge-patient.php',
                        method: 'POST',
                        data: { bed_id: bedId },
                        success: function(response) {
                            if(response.success) {
                                alert('Patient discharged successfully');
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        }
                    });
                }
            });

            // Filter Cases
            $('#filterPriority, #filterStatus').change(function() {
                const priority = $('#filterPriority').val();
                const status = $('#filterStatus').val();
                
                $.ajax({
                    url: 'filter-emergency-cases.php',
                    method: 'POST',
                    data: { priority: priority, status: status },
                    success: function(response) {
                        $('#emergencyCasesTable').html(response);
                    }
                });
            });

            // Print Case
            $(document).on('click', '.print-case', function() {
                window.print();
            });
        });
    </script>
</body>
</html>