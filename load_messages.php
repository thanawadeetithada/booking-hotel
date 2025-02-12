<?php
session_start();
include "db.php";

// if (!isset($_SESSION["userrole"])) {
//     die("ไม่ได้รับอนุญาต");
// }

$user_id = isset($_GET["user_id"]) ? intval($_GET["user_id"]) : NULL;

// ถ้าเป็น Admin ให้ดึงแชทของ User ที่เลือก
if ($_SESSION["userrole"] === "admin" && $user_id) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $user_id);
} 
// ถ้าเป็น User ให้ดึงแชทของตัวเอง
elseif ($_SESSION["userrole"] === "user") {
    $user_id = intval($_SESSION["user_id"]);
    $stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $user_id);
} 
// ถ้าไม่มี User ID ให้ดึงแชททั้งหมด (กรณีเป็น Admin)
else {
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at ASC");
}

// รันคำสั่ง SQL
$stmt->execute();
$result = $stmt->get_result();

// แสดงผลข้อความ
while ($row = $result->fetch_assoc()) {
    if ($row['sender'] == "user") {
        echo "<div class='message-wrapper user-message'>
                <div class='message user'>" . htmlspecialchars($row['message']) . "</div>
              </div>";
    } else {
        echo "<div class='message-wrapper admin-message'>
                <div class='message admin'>" . htmlspecialchars($row['message']) . "</div>
              </div>";
    }
}

$stmt->close();
?>
