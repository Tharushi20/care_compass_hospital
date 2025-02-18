<?php
session_start();
include('../includes/db.php');

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$delete_id'");
    header("Location: manage_users.php");
    exit;
}

// Handle Role Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    mysqli_query($conn, "UPDATE users SET role='$new_role' WHERE id='$user_id'");
    header("Location: manage_users.php");
    exit;
}

// Fetch Users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
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
            background-color: #002366;
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

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            padding: 15px;
            background-color: #0056d2;
            color: white;
            text-align: center;
            border-radius: 5px;
        }

        .table thead {
            background-color: #0047ab;
            color: white;
        }

        .btn-success, .btn-danger {
            padding: 5px 12px;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #d9534f;
        }

        .form-select {
            padding: 5px;
            font-size: 14px;
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
    <h4>Admin Panel</h4>
    <a href="admin.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a>
    <a href="manage_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
    <a href="view_feedback.php"><i class="fas fa-comments"></i> View Feedback</a>
    <a href="../logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Manage Users</h2>
        <p>View, edit, and manage user roles.</p>
    </div>

    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Role</th>
                <th>Update Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo ucfirst($row['role']); ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <select name="role" class="form-select">
                                <option value="admin" <?php if ($row['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="staff" <?php if ($row['role'] === 'staff') echo 'selected'; ?>>Staff</option>
                                <option value="patient" <?php if ($row['role'] === 'patient') echo 'selected'; ?>>Patient</option>
                            </select>
                            <button type="submit" name="update_role" class="btn btn-success ms-2">Update</button>
                        </form>
                    </td>
                    <td>
                        <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">
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
