<?php
session_start();
include('../includes/db.php');

// Only Staff Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// Handle Add Patient
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_patient'])) {
    $patient_name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    mysqli_query($conn, "INSERT INTO patients (patient_name, patient_id, date_of_birth, gender, contact_number, email, address) 
    VALUES ('$patient_name', '$patient_id', '$dob', '$gender', '$contact', '$email', '$address')");
    header("Location: patients.php");
    exit;
}

// Handle Edit Patient
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_patient'])) {
    $edit_id = $_POST['edit_id'];
    $patient_name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    mysqli_query($conn, "UPDATE patients SET 
        patient_name='$patient_name', 
        patient_id='$patient_id', 
        date_of_birth='$dob', 
        gender='$gender', 
        contact_number='$contact', 
        email='$email', 
        address='$address' 
    WHERE id='$edit_id'");

    header("Location: patients.php");
    exit;
}

// Handle Delete Patient
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM patients WHERE id='$delete_id'");
    header("Location: patients.php");
    exit;
}

// Fetch All Patients
$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY patient_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients - Staff</title>
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

        /* Form Styling */
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: #0047ab;
            border: none;
        }

        .btn-primary:hover {
            background-color: #003a8c;
        }

        .table thead {
            background-color: #0047ab;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f9ff;
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
        <h2>Patients</h2>
        <p>Manage patient records.</p>
    </div>

    <!-- Add Patient Form -->
    <div class="form-container">
        <h5>Add New Patient</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient Name</label>
                <input type="text" name="patient_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Patient ID</label>
                <input type="number" name="patient_id" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control">
            </div>
            <div class="col-12">
                <button type="submit" name="add_patient" class="btn btn-primary w-100">Add Patient</button>
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Patient ID</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($patients) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($patients)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['patient_id']; ?></td>
                        <td><?php echo $row['date_of_birth']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td>
                            <a href="edit_patient.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="patients.php?delete_id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this patient?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No patients found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
