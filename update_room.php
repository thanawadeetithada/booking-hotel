<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_code = $_POST['room_code'];
    $room_number = $_POST['room_number'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $isshow = isset($_POST['isshow']) ? 1 : 0; // รับค่า isshow (เปิด = 1, ปิด = 0)

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = "uploads/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);

        $sql = "UPDATE rooms SET room_number = ?, price = ?, type = ?, description = ?, image_path = ?, isshow = ? WHERE room_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssis", $room_number, $price, $type, $description, $image_path, $isshow, $room_code);
    } else {
        $sql = "UPDATE rooms SET room_number = ?, price = ?, type = ?, description = ?, isshow = ? WHERE room_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssis", $room_number, $price, $type, $description, $isshow, $room_code);
    }

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location.href = 'dashboard_room.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $conn->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
