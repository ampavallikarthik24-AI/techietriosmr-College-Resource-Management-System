<?php
$servername = "localhost";
$username   = "root";
$password   = "rootkarthik";   // put your real MySQL root password here
$dbname     = "techietriosmr";
$port       = 3306;   // or 3307, whichever port works for your MySQL

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

