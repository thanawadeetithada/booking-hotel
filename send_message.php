<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["sender"], $_POST["message"], $_POST["user_id"])) {
        die("ข้อมูลไม่ครบ");
    }

    $sender = $conn->real_escape_string($_POST["sender"]);
    $message = $conn->real_escape_string($_POST["message"]);
    $user_id = intval($_POST["user_id"]);
    $admin_id = isset($_SESSION["admin_id"]) ? intval($_SESSION["admin_id"]) : NULL;
    $notification = 1;

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, admin_id, sender, message, notification) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $user_id, $admin_id, $sender, $message, $notification);
        $stmt->execute();
        $stmt->close();
    }
}
?>
