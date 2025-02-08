<?php
$servername = "localhost"; // หรือ IP ของเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้ MySQL
$password = ""; // รหัสผ่าน MySQL
$database = "pha_chom_dao_resort"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
