<?php
session_start();
include('../includes/db.php');

// Only Staff Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// Handle Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE appointments SET status='$new_status' WHERE id='$appointment_id'");
    header("Location: manage_appointments.php");
    exit;
}

// Fetch All Appointments
$appointments = mysqli_query($conn, "SELECT * FROM appointments ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments - Staff</title>
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

        .header {
            padding: 15px;
            background-color: #0056d2;
            color: white;
            text-align: center;
            border-radius: 5px;
        }

        /* Dashboard Cards (Same as Staff Dashboard) */
        .dashboard-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .dashboard-card i {
            font-size: 40px;
            color: #0056d2;
        }

        .dashboard-card h5 {
            font-weight: bold;
            margin: 10px 0;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        /* Table Styles */
        .table thead {
            background-color: #0047ab;
            color: white;
        }

        .status-btn {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 20px;
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
    <h4>Staff Panel</h4>
    <a href="staff.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_appointments.php"><i class="fas fa-calendar"></i> Manage Appointments</a>
    <a href="patients.php"><i class="fas fa-procedures"></i> View Patients</a>
    <a href="lab_reports.php"><i class="fas fa-flask"></i> Manage Lab Reports</a>
    <a href="../logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Manage Appointments</h2>
        <p>View and update patient appointments.</p>
    </div>

    <!-- Dashboard Cards (Same Style as Staff Dashboard) -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="dashboard-card">
                <i class="fas fa-calendar-check"></i>
                <h5>Today's Appointments</h5>
                <p>
                    <?php
                    $today = date('Y-m-d');
                    $today_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE appointment_date='$today'"))['count'];
                    echo $today_count;
                    ?>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card">
                <i class="fas fa-clock"></i>
                <h5>Pending Appointments</h5>
                <p>
                    <?php
                    $pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='pending'"))['count'];
                    echo $pending_count;
                    ?>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card">
                <i class="fas fa-check-circle"></i>
                <h5>Completed Appointments</h5>
                <p>
                    <?php
                    $completed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='completed'"))['count'];
                    echo $completed_count;
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <table class="table table-bordered mt-4">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo $row['status'] == 'completed' ? 'success' : ($row['status'] == 'canceled' ? 'danger' : 'warning'); ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                                <option value="pending" <?php if ($row['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="completed" <?php if ($row['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                <option value="canceled" <?php if ($row['status'] === 'canceled') echo 'selected'; ?>>Canceled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
