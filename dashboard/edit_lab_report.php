<?php
session_start();
include('../includes/db.php');

// Only Staff Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// Get Report Details for Editing
if (isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM lab_reports WHERE id='$report_id'");
    $report = mysqli_fetch_assoc($result);

    if (!$report) {
        echo "<script>alert('Report not found!'); window.location.href='lab_reports.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='lab_reports.php';</script>";
    exit;
}

// Handle Report Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_report'])) {
    $patient_name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];
    $test_type = $_POST['test_type'];
    $result_text = $_POST['result'];
    $doctor_name = $_POST['doctor_name'];
    $report_date = $_POST['report_date'];

    mysqli_query($conn, "UPDATE lab_reports SET 
        patient_name='$patient_name',
        patient_id='$patient_id',
        test_type='$test_type',
        result='$result_text',
        doctor_name='$doctor_name',
        report_date='$report_date'
    WHERE id='$report_id'");

    echo "<script>alert('Report updated successfully!'); window.location.href='lab_reports.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lab Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin: 50px auto;
            max-width: 600px;
        }

        .btn-primary {
            background-color: #0047ab;
            border: none;
        }

        .btn-primary:hover {
            background-color: #003a8c;
        }

        .back-btn {
            color: #0047ab;
            text-decoration: none;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Edit Lab Report Form -->
<div class="form-container">
    <h4 class="mb-4 text-center"><i class="fas fa-edit"></i> Edit Lab Report</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" value="<?php echo $report['patient_name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Patient ID</label>
            <input type="number" name="patient_id" class="form-control" value="<?php echo $report['patient_id']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Test Type</label>
            <input type="text" name="test_type" class="form-control" value="<?php echo $report['test_type']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Result</label>
            <textarea name="result" class="form-control" rows="4" required><?php echo $report['result']; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Doctor Name</label>
            <input type="text" name="doctor_name" class="form-control" value="<?php echo $report['doctor_name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Report Date</label>
            <input type="date" name="report_date" class="form-control" value="<?php echo $report['report_date']; ?>" required>
        </div>

        <div class="d-grid">
            <button type="submit" name="update_report" class="btn btn-primary">Update Report</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="lab_reports.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Reports</a>
    </div>
</div>

</body>
</html>
