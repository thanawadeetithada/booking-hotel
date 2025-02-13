<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM messages WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "error: ไม่พบข้อมูลที่ต้องการลบ";
        }
    } else {
        echo "error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "error: ไม่มีค่า user_id ที่ส่งมา";
}
?>
