<?php
// Database Connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'care_compass';
$port = 3307; 

// Create Connection
$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
