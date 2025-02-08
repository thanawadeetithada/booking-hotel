<?php
session_start();
require 'db.php';

// ตรวจสอบว่ามีการส่งค่า POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $id_card = $_POST["id_card"];
    $birthdate = $_POST["birthdate"];
    $userrole = $_POST["userrole"];

    // อัปเดตข้อมูล
    $sql = "UPDATE users SET first_name=?, last_name=?, phone=?, email=?, id_card=?, birthdate=?, userrole=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $userrole, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลเรียบร้อยแล้ว'); window.location.href='dashboard_user.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด'); window.history.back();</script>";
    }
}
?>
