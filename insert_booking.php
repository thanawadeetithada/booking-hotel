<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST" || 
    !isset($_POST["first_name"], $_POST["last_name"], $_POST["checkin_date"], $_POST["checkout_date"], 
    $_POST["room_number"], $_POST["room_type"], $_POST["guest_count"], $_POST["room_count"], 
    $_POST["price"], $_POST["description"], $_POST["payment_method"])) {
    echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit();
}

$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$checkin_date = date("Y-m-d", strtotime($_POST["checkin_date"]));
$checkout_date = date("Y-m-d", strtotime($_POST["checkout_date"]));
$room_number = $_POST["room_number"];
$room_type = $_POST["room_type"];
$guest_count = intval($_POST["guest_count"]);
$room_count = intval($_POST["room_count"]);
$price = floatval($_POST["price"]);
$description = $_POST["description"];
$payment_method = $_POST["payment_method"];
$status_payment = "pending";
$paid_amount = 0.00;
$invoice_number = "INV-" . time();

$checkin = new DateTime($checkin_date);
$checkout = new DateTime($checkout_date);
$interval = $checkin->diff($checkout);
$nights = $interval->days;

if ($nights <= 0) {
    die("วันที่เช็คเอาท์ต้องมากกว่าวันที่เช็คอิน");
}
if ($room_type == "เต็นท์") {
    $total_amount = $price * $guest_count * $room_count * $nights;
} else {
    $total_amount = $price * $room_count * $nights;
}

$sql = "INSERT INTO booking (invoice_number, first_name, last_name, checkin_date, checkout_date, room_number, room_type, 
        guest_count, room_count, price, description, payment_method, status_payment, total_amount, paid_amount) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssddssssd", $invoice_number, $first_name, $last_name, 
                  $checkin_date, $checkout_date, $room_number, $room_type, 
                  $guest_count, $room_count, $price, $description, $payment_method, 
                  $status_payment, $total_amount, $paid_amount);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "booking_id" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
