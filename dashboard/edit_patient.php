<?php
session_start();
include('../includes/db.php');

// Only Staff Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// Get Patient Details for Editing
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM patients WHERE id='$patient_id'");
    $patient = mysqli_fetch_assoc($result);

    if (!$patient) {
        echo "<script>alert('Patient not found!'); window.location.href='patients.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='patients.php';</script>";
    exit;
}

// Handle Patient Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
    $patient_name = $_POST['patient_name'];
    $patient_id_num = $_POST['patient_id'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    mysqli_query($conn, "UPDATE patients SET 
        patient_name='$patient_name',
        patient_id='$patient_id_num',
        date_of_birth='$dob',
        gender='$gender',
        contact_number='$contact',
        email='$email',
        address='$address'
    WHERE id='$patient_id'");

    echo "<script>alert('Patient record updated successfully!'); window.location.href='patients.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient</title>
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

<!-- Edit Patient Form -->
<div class="form-container">
    <h4 class="mb-4 text-center"><i class="fas fa-edit"></i> Edit Patient Record</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" value="<?php echo $patient['patient_name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Patient ID</label>
            <input type="number" name="patient_id" class="form-control" value="<?php echo $patient['patient_id']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control" value="<?php echo $patient['date_of_birth']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="<?php echo $patient['contact_number']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $patient['email']; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?php echo $patient['address']; ?>" required>
        </div>

        <div class="d-grid">
            <button type="submit" name="update_patient" class="btn btn-primary">Update Patient</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="patients.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Patients</a>
    </div>
</div>

</body>
</html>
