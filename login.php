<?php
include('includes/db.php');
session_start();

// Handle Login Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT id, role, password FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Store session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header("Location: dashboard/admin.php");
        } elseif ($user['role'] == 'staff') {
            header("Location: dashboard/staff.php");
        } else {
            header("Location: dashboard/patient.php");
        }
        exit;
    } else {
        // Return error back to index.php
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: index.php");
        exit;
    }
}
?>
