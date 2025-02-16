<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST" || 
    !isset($_POST["first_name"], $_POST["last_name"], $_POST["checkin_date"], $_POST["checkout_date"], 
    $_POST["room_number"], $_POST["room_type"], $_POST["guest_count"],
    $_POST["price"], $_POST["description"], $_POST["payment_method"])) {
    echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit();
}
if (!isset($_SESSION["email"])) {
    echo json_encode(["status" => "error", "message" => "ไม่ได้เข้าสู่ระบบ"]);
    exit();
}

$email = $_SESSION["email"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$checkin_date = date("Y-m-d", strtotime($_POST["checkin_date"]));
$checkout_date = date("Y-m-d", strtotime($_POST["checkout_date"]));
$room_number = $_POST["room_number"];
$room_type = $_POST["room_type"];
$guest_count = intval($_POST["guest_count"]);
$price = floatval($_POST["price"]);
$description = $_POST["description"];
$payment_method = $_POST["payment_method"];
$status_payment = "pending";
$invoice_number = "INV-" . time();

$checkin = new DateTime($checkin_date);
$checkout = new DateTime($checkout_date);
$interval = $checkin->diff($checkout);
$nights = $interval->days;

if ($nights <= 0) {
    die("กรุณาจองอย่างน้อย 1 คืน");
}
if ($room_type == "เต็นท์") {
    $total_amount = $price * $guest_count * $nights;
} else {
    $total_amount = $price  * $nights;
}

$paid_amount = $total_amount * (1 + (7/100));
$sql = "INSERT INTO booking (invoice_number, first_name, last_name, checkin_date, checkout_date, email, room_number, room_type, 
        guest_count, price, description, payment_method, status_payment, total_amount, paid_amount, nights) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssdssssdi", $invoice_number, $first_name, $last_name, 
                  $checkin_date, $checkout_date, $email, $room_number, $room_type, 
                  $guest_count, $price, $description, $payment_method, 
                  $status_payment, $total_amount, $paid_amount, $nights);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "booking_id" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
