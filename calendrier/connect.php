<?php
$servername = "localhost";  // Replace with your database server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "rdv";            // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
