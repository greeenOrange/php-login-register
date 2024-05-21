<?php
$servername = "localhost";
$username  = "root";
$dbPassword = "";
$dbName = "registration_db";
$conn = mysqli_connect($servername, $username , $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>