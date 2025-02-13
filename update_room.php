<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_code = $_POST['room_code'];
    $room_number = $_POST['room_number'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $isshow = isset($_POST['isshow']) ? 1 : 0;
    $image_path = $_POST['existing_image'];

    $check_sql = "SELECT room_code FROM rooms WHERE room_number = ? AND room_code != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $room_number, $room_code);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('มีเลขห้องนี้อยู่แล้ว กรุณาใช้หมายเลขอื่น'); window.history.back();</script>";
        exit();
    }

    if (!empty($_FILES["image"]["name"])) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_types)) {
            $new_filename = "uploads/" . uniqid("room_", true) . "." . $file_ext;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $new_filename)) {
                $image_path = $new_filename;
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('รองรับเฉพาะไฟล์ JPG, JPEG, PNG, และ GIF เท่านั้น'); window.history.back();</script>";
            exit();
        }

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
        echo "<script>alert('เกิดข้อผิดพลาด: " . $conn->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>