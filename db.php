<?php
$host = "localhost";     // Your database host
$user = "root";          // Your database username (default is root in XAMPP)
$password = "";          // Your database password (empty by default in XAMPP)
$dbname = "todo_app_v3"; // Your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
