<?php
include 'db.php';

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$adults = $_POST['adults'];
$rooms = $_POST['rooms'];

$sql = "SELECT * FROM rooms WHERE isshow = 1 AND NOT EXISTS (
            SELECT 1 FROM booking 
            WHERE booking.room_number = rooms.room_number 
            AND (
                (booking.checkin_date BETWEEN '$startDate' AND '$endDate') OR 
                (booking.checkout_date BETWEEN '$startDate' AND '$endDate') OR 
                ('$startDate' BETWEEN booking.checkin_date AND booking.checkout_date)
            )
        )";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='hotel-card'>";
        echo "<img src='".htmlspecialchars($row['image_path'])."' alt='ห้องพัก' class='hotel-image'>";
        echo "<div class='hotel-info'>";
        echo "<h3>ข้อมูลห้องพัก</h3>";
        echo "<p>".htmlspecialchars($row['description'])."</p>";
        echo "<p><strong>ราคา ".number_format($row['price'], 2)." บาท/คืน</strong></p>";
        echo "<button class='btn btn-custom mt-2'>จองห้องพัก</button>";
        echo "</div></div>";
    }
} else {
    echo "<p class='text-center'>ไม่มีห้องพักหรือเต็นท์ที่พร้อมให้จองในขณะนี้</p>";
}

$conn->close();
?>
