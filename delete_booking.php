<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['id'];

    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>
