<?php
include 'db.php';

if (isset($_POST['room_number']) && !empty($_POST['room_number'])) {
    $room_number = $_POST['room_number'];
    error_log("Room Number received: " . $room_number);

    $sql = "SELECT price FROM rooms WHERE room_number = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $conn->error);
        echo "0";
        exit;
    }

    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo $row['price'];
    } else {
        echo "0";
    }

    $stmt->close();
} else {
    error_log("No room_number received in POST request.");
    echo "0";
}

$conn->close();

?>
