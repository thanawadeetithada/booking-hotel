<?php
require 'db.php';

if (isset($_POST['room_id']) && isset($_POST['isshow'])) {
    $room_id = intval($_POST['room_id']); // แปลงให้เป็นตัวเลข
    $isshow = intval($_POST['isshow']);

    $sql = "UPDATE rooms SET isshow = $isshow WHERE room_id = $room_id";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
$conn->close();
?>
