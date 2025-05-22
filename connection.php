<?php
$host = "localhost";
$dbname = ""; // database name
$user = ""; // username
$pass = ""; // password

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
