<?php
session_start();
include('../includes/db.php');

// Only Patients Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../index.php");
    exit;
}

// Get logged-in patient's ID
$patient_id = $_SESSION['user_id'];

// Fetch lab reports **only for this patient**
$lab_reports = mysqli_query($conn, 
    "SELECT report_date, test_type, result, doctor_name  
     FROM lab_reports 
     WHERE patient_id='$patient_id' 
     ORDER BY report_date DESC");
// $lab_reports = mysqli_query($conn, "SELECT * FROM lab_reports WHERE patient_id='$patient_id' ORDER BY report_date DESC");
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Reports - Care Compass Hospitals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f8ff; }
        .header { padding: 15px; background-color: #0056d2; color: white; text-align: center; border-radius: 5px; }
        .table thead { background-color: #0047ab; color: white; }
        .container {
    margin-left: 260px; 
    padding-right: 20px; 
    width: calc(100% - 260px); 
    overflow-x: auto; 
}

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #0047ab;
            color: white;
            padding-top: 20px;
        }

        .sidebar h4 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #0056d2;
        }
        /* Logout Button */
        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 20px;
            width: 80%;
        }

    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h4>Patient Panel</h4>
    <a href="patient.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="appointments.php"><i class="fas fa-calendar-check"></i> My Appointments</a>
    <a href="book_appointment.php"><i class="fas fa-calendar-check"></i> Book Appointments</a>
    <a href="medical_records_patient.php"><i class="fas fa-notes-medical"></i> Medical Records</a>
    <a href="lab_reports_patient.php"><i class="fas fa-flask"></i> Lab Reports</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal"><i class="fas fa-comment-dots"></i> Provide Feedback</a>
    <a href="../logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<div class="container mt-4">
    <div class="header">
        <h2><i class="fas fa-flask"></i> My Lab Reports</h2>
        <p>View your laboratory test results.</p>
    </div>

    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>Report Date</th>
                <th>Test Type</th>
                <th>Results</th>
                <th>Doctor</th>
                <th>View Report</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = mysqli_fetch_assoc($lab_reports)): ?>
        <tr>
            <td><?php echo $row['report_date']; ?></td>
            <td><?php echo isset($row['test_type']) ? $row['test_type'] : 'N/A'; ?></td>
            <td><?php echo isset($row['result']) ? $row['result'] : 'N/A'; ?></td>
            <td><?php echo isset($row['doctor_name']) ? $row['doctor_name'] : 'N/A'; ?></td>
            <td>
                <span class="text-danger">No File</span>  <!-- Since no report file column exists -->
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

    </table>
</div>

</body>
</html>
