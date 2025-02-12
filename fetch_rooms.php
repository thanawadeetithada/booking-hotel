<?php
include 'db.php';

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $query = "SELECT room_number, price FROM rooms WHERE type = ? AND isshow = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $rooms = [];

    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    echo json_encode($rooms);
    $stmt->close();
    $conn->close();
}
?>
