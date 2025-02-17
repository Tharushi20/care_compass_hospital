<?php
include('includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if user already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['register_error'] = "Email already exists.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')");
        if ($insert) {
            $_SESSION['register_success'] = "Registration successful. You can now log in.";
        } else {
            $_SESSION['register_error'] = "Registration failed. Please try again.";
        }
    }
    header("Location: index.php");
    exit;
}
?>
