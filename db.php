<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "pha_chom_dao_resort";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
