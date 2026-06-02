<?php
$host = "localhost";   // XAMPP default
$user = "root";        // phpMyAdmin default user
$pass = "";            // phpMyAdmin default password (XAMPP e empty thake)
$db   = "final"; // database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
