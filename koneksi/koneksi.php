<?php
$conn = new mysqli("localhost", "root", "", "dbbangunan");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

