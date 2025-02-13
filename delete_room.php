<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // ตรวจสอบว่ามีห้องนี้จริงก่อนลบ
    $check_sql = "SELECT * FROM rooms WHERE room_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        echo "error: ห้องที่ต้องการลบไม่มีอยู่ในระบบ";
        exit();
    }
    $check_stmt->close();

    // ดำเนินการลบ
    $sql = "DELETE FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $conn->error; // แสดงรายละเอียด error
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error: ไม่มี ID ที่ถูกส่งมา";
}
?>
