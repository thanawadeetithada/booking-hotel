<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit();
}

// ตรวจสอบข้อมูลที่รับมา
if (!isset($_POST['booking_id']) || !isset($_FILES['payment_proof'])) {
    echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit();
}

$booking_id = intval($_POST['booking_id']);

// 1️⃣ **ดึงข้อมูลจาก `booking`**
$sql = "SELECT * FROM booking WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลการจอง"]);
    exit();
}

$booking = $result->fetch_assoc();

// ตรวจสอบว่า invoice_number มีอยู่หรือไม่
$invoice_number = $booking['invoice_number'];
if (empty($invoice_number)) {
    $invoice_number = "INV-" . time();
}

// **โฟลเดอร์อัปโหลด**
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// 2️⃣ **อัปโหลดหลักฐานการชำระเงิน**
$payment_slip_name = time() . "_" . basename($_FILES["payment_proof"]["name"]);
$target_file = $target_dir . $payment_slip_name;

if (!move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
    echo json_encode(["status" => "error", "message" => "อัปโหลดไฟล์ล้มเหลว"]);
    exit();
}

// 3️⃣ **อัปเดตสถานะ `booking` เป็น "paid"**
$update_sql = "UPDATE booking SET status_payment = 'paid', payment_slip = ?, invoice_number = ? WHERE booking_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssi", $payment_slip_name, $invoice_number, $booking_id);

if (!$update_stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการอัปเดต Booking: " . $update_stmt->error]);
    exit();
}

// 4️⃣ **ย้ายข้อมูลจาก `booking` ไปยัง `invoice`**
$insert_invoice_sql = "INSERT INTO invoice 
        (invoice_number, first_name, last_name, checkin_date, checkout_date, room_number, 
         room_type, guest_count, room_count, price, description, payment_method, 
         status_payment, payment_slip, total_amount, paid_amount, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$insert_stmt = $conn->prepare($insert_invoice_sql);
$status_payment = "paid";
$paid_amount = $booking['total_amount'];

$insert_stmt->bind_param("ssssssssddssssdd", 
    $invoice_number, $booking['first_name'], $booking['last_name'], 
    $booking['checkin_date'], $booking['checkout_date'], $booking['room_number'], 
    $booking['room_type'], $booking['guest_count'], $booking['room_count'], 
    $booking['price'], $booking['description'], $booking['payment_method'], 
    $status_payment, $payment_slip_name, $booking['total_amount'], $paid_amount
);

if (!$insert_stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการสร้างใบแจ้งหนี้: " . $insert_stmt->error]);
    exit();
}

$invoice_id = $conn->insert_id;

// 5️⃣ **ลบข้อมูลใน `booking` หลังจากย้ายไป `invoice` แล้ว (ถ้าต้องการ)**
$delete_booking_sql = "DELETE FROM booking WHERE booking_id = ?";
$delete_stmt = $conn->prepare($delete_booking_sql);
$delete_stmt->bind_param("i", $booking_id);

if (!$delete_stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูล Booking: " . $delete_stmt->error]);
    exit();
}

// **ส่งค่าไปยัง JS**
echo json_encode([
    "status" => "success",
    "message" => "ชำระเงินสำเร็จ",
    "invoice_id" => $invoice_id // ให้ JS ใช้ redirect ไป success_payment.php
]);

// **ปิด Statement**
$stmt->close();
$update_stmt->close();
$insert_stmt->close();
$delete_stmt->close();
$conn->close();
exit();
?>
