<?php
include 'db.php';

if (isset($_POST['room_number'])) {
    $room_number = $_POST['room_number'];

    $sql = "SELECT price FROM rooms WHERE room_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo $row['price'];
    } else {
        echo "0";
    }

    $stmt->close();
    $conn->close();
}
?>
