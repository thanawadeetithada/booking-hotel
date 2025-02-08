<?php
session_start();
require 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $user_id = $_POST['id'];

    // ตรวจสอบว่า user_id มีอยู่จริงหรือไม่
    $check_sql = "SELECT * FROM users WHERE user_id = ?";
    if ($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // ถ้าพบข้อมูล ดำเนินการลบ
            $delete_sql = "DELETE FROM users WHERE user_id = ?";
            if ($delete_stmt = $conn->prepare($delete_sql)) {
                $delete_stmt->bind_param("i", $user_id);
                if ($delete_stmt->execute()) {
                    echo "success"; // ส่งค่ากลับไปให้ JavaScript จัดการ
                } else {
                    echo "error";
                }
                $delete_stmt->close();
            } else {
                echo "error";
            }
        } else {
            echo "not_found";
        }
        $check_stmt->close();
    } else {
        echo "error";
    }
} else {
    echo "invalid_request";
}

$conn->close();
?>
