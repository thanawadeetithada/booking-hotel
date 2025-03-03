<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $payment_method = $_POST['payment_method'];

    $room_query = "SELECT room_id FROM rooms WHERE room_number = ?";
    $stmt_room = $conn->prepare($room_query);
    $stmt_room->bind_param("s", $room_number);
    $stmt_room->execute();
    $room_result = $stmt_room->get_result();
    
    if ($room_result->num_rows > 0) {
        $room_data = $room_result->fetch_assoc();
        $room_id = $room_data['room_id'];
    } else {
        echo "<script>alert('ไม่พบหมายเลขห้อง'); window.history.back();</script>";
        exit();
    }

    $payment_slip = "";
    if (!empty($_FILES['imgpayment']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imgpayment"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["imgpayment"]["tmp_name"], $target_file)) {
                $payment_slip = $target_file;
            } else {
                echo "<script>alert('อัปโหลดไฟล์ล้มเหลว'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('ประเภทไฟล์ไม่ถูกต้อง'); window.history.back();</script>";
            exit();
        }
    }

    if ($payment_method === "เงินสด") {
        $payment_slip = "";
    }
    $status_payment = ($payment_method === "เงินสด") ? "pending" : "paid";

    if (!empty($payment_slip)) {
        $sql = "UPDATE bookings 
                SET checkin_date = ?, checkout_date = ?, room_id = ?, payment_method = ?, status_payment = ?, payment_slip = ?
                WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssi", $checkin_date, $checkout_date, $room_id, $payment_method, $status_payment, $payment_slip, $booking_id);
    } else {
        $sql = "UPDATE bookings 
                SET checkin_date = ?, checkout_date = ?, room_id = ?, payment_method = ?, status_payment = ?, payment_slip = NULL
                WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $checkin_date, $checkout_date, $room_id, $payment_method, $status_payment, $booking_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.location.href = 'dashboard_booking.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>