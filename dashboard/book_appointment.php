<?php
session_start();
include('../includes/db.php');

// Only Patients Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../index.php");
    exit;
}

// Get patient_id from session
$patient_id = $_SESSION['user_id'];
$message = "";

// Handle Appointment Booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    // Insert Appointment into `appointments` table
    $query = "INSERT INTO appointments (patient_id, patient_name, doctor_name, department, appointment_date, appointment_time, status) 
              VALUES ('$patient_id', '$patient_name', '$doctor_name', '$department', '$appointment_date', '$appointment_time', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success'>Appointment booked successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment - Care Compass Hospitals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
            padding: 30px;
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


        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #0047ab;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #003399;
            transform: translateY(-2px);
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

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4 text-center text-primary"><i class="fas fa-calendar-plus"></i> Book an Appointment</h2>
    <?php echo $message; ?>

    <div class="form-container">
        <form method="POST">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name</label>
                <input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="doctor_name" class="form-label">Doctor Name</label>
                <input type="text" class="form-control" id="doctor_name" name="doctor_name" placeholder="Enter doctor's name" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select class="form-select" id="department" name="department" required>
                    <option value="">Select Department</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Orthopedics">Orthopedics</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="appointment_date" class="form-label">Appointment Date</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="appointment_time" class="form-label">Appointment Time</label>
                <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
