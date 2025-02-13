<?php
session_start();
require 'db.php';
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    echo "unauthorized";
    exit();
}

if (isset($_POST['room_id']) && isset($_POST['isshow'])) {
    $room_id = intval($_POST['room_id']);
    $isshow = intval($_POST['isshow']);

    $sql = "UPDATE rooms SET isshow = ? WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isshow, $room_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "invalid";
}
$conn->close();
?>
