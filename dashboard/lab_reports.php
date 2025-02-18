<?php
session_start();
include('../includes/db.php');

// Only Staff Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// Handle Report Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM lab_reports WHERE id='$delete_id'");
    header("Location: lab_reports.php");
    exit;
}

// Handle Add Report
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_report'])) {
    $patient_name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];
    $test_type = $_POST['test_type'];
    $result = $_POST['result'];
    $doctor_name = $_POST['doctor_name'];
    $report_date = $_POST['report_date'];

    mysqli_query($conn, "INSERT INTO lab_reports (patient_name, patient_id, test_type, result, doctor_name, report_date) 
    VALUES ('$patient_name', '$patient_id', '$test_type', '$result', '$doctor_name', '$report_date')");
    header("Location: lab_reports.php");
    exit;
}

// Fetch All Lab Reports
$reports = mysqli_query($conn, "SELECT * FROM lab_reports ORDER BY report_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Reports - Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
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
            text-align: center;
            font-weight: bold;
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

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .header {
            padding: 15px;
            background-color: #0056d2;
            color: white;
            text-align: center;
            border-radius: 5px;
        }

        /* Table Styling */
        .table thead {
            background-color: #0047ab;
            color: white;
        }

        .btn-action {
            padding: 5px 10px;
            font-size: 13px;
            border-radius: 20px;
        }

        /* Form Styling */
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

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
    <h4>Staff Panel</h4>
    <a href="staff.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_appointments.php"><i class="fas fa-calendar-check"></i> Manage Appointments</a>
    <a href="patients.php"><i class="fas fa-procedures"></i> View Patients</a>
    <a href="lab_reports.php"><i class="fas fa-flask"></i> Manage Lab Reports</a>
    <a href="../logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Lab Reports</h2>
        <p>Manage patient lab reports efficiently.</p>
    </div>

    <!-- Add Report Form -->
    <div class="form-container">
        <h5>Add New Lab Report</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient Name</label>
                <input type="text" name="patient_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Patient ID</label>
                <input type="number" name="patient_id" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Test Type</label>
                <input type="text" name="test_type" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor Name</label>
                <input type="text" name="doctor_name" class="form-control" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Result</label>
                <textarea name="result" class="form-control" rows="3" required></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Report Date</label>
                <input type="date" name="report_date" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" name="add_report" class="btn btn-primary w-100">Add Report</button>
            </div>
        </form>
    </div>

    <!-- Lab Reports Table -->
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Patient ID</th>
                <th>Test Type</th>
                <th>Doctor</th>
                <th>Report Date</th>
                <th>Result</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($reports)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td><?php echo $row['test_type']; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo $row['report_date']; ?></td>
                    <td><?php echo $row['result']; ?></td>
                    <td>
                        <a href="edit_lab_report.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="lab_reports.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn btn-danger btn-action"
                           onclick="return confirm('Are you sure you want to delete this report?');">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
