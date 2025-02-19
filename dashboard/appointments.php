<?php
session_start();
include('../includes/db.php');

// Only Patients Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../index.php");
    exit;
}

// Get patient ID from session
$patient_id = $_SESSION['user_id'];

// Handle Appointment Cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    mysqli_query($conn, "UPDATE appointments SET status='Cancelled' WHERE id='$appointment_id' AND patient_id='$patient_id'");
    header("Location: appointments.php");
    exit;
}

// Fetch Patient's Appointments
$appointments = mysqli_query($conn, 
    "SELECT * FROM appointments 
     WHERE patient_id='$patient_id' 
     ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments - Care Compass Hospitals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f8ff; }
        .header { padding: 15px; background-color: #0056d2; color: white; text-align: center; border-radius: 5px; }
        .table thead { background-color: #0047ab; color: white; }
        .status-badge { font-size: 0.9rem; padding: 5px 10px; border-radius: 15px; font-weight: bold; }
        .status-pending { background-color: #ffcc00; color: #333; }
        .status-completed { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
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
        <h2><i class="fas fa-calendar-check"></i> My Appointments</h2>
        <p>View your upcoming and past appointments</p>
    </div>

    <!-- Appointments Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Appointment Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($appointments) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                    <tr>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo $row['appointment_time']; ?></td>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['department']; ?></td>
                        <td>
                            <?php 
                                $status = strtolower($row['status']);
                                if ($status === 'pending') {
                                    echo "<span class='status-badge status-pending'>Pending</span>";
                                } elseif ($status === 'completed') {
                                    echo "<span class='status-badge status-completed'>Completed</span>";
                                } else {
                                    echo "<span class='status-badge status-cancelled'>Cancelled</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($status === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="cancel_appointment" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>No Action</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No appointments found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
