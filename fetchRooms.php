<?php
include 'db.php';
if (isset($_POST['room_type'])) {
    $room_type = $_POST['room_type'];
    $query = "SELECT room_number FROM rooms WHERE type = ? AND isshow = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $room_type);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['room_number'] . "'>" . $row['room_number'] . "</option>";
    }
    $stmt->close();
}
$conn->close();
?>
