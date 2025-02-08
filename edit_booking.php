<?php
session_start();
require 'db.php';

// ตรวจสอบว่ามีการส่ง booking_id มาหรือไม่
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // ดึงข้อมูลการจองร่วมกับข้อมูลผู้ใช้และห้องพัก
    $sql = "SELECT 
                b.*, 
                u.first_name, 
                u.last_name, 
                r.room_number, 
                r.type, 
                r.price, 
                r.description 
            FROM bookings b
            JOIN users u ON b.user_id = u.user_id
            JOIN rooms r ON b.room_id = r.room_id
            WHERE b.booking_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        echo "<script>alert('ไม่พบข้อมูลการจอง'); window.location.href = 'room_dashboard.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีรหัสการจองที่ต้องการแก้ไข'); window.location.href = 'room_dashboard.php';</script>";
    exit();
}

// ตรวจสอบค่าจากฐานข้อมูลก่อนนำไปใช้ในฟอร์ม
$first_name = $booking['first_name'];
$last_name = $booking['last_name'];
$checkin_date = $booking['checkin_date'];
$checkout_date = $booking['checkout_date'];
$room_number = $booking['room_number'];
$type = $booking['type'];
$price = $booking['price'];
$description = $booking['description'];
$payment_method = $booking['payment_method'];
$payment_slip = $booking['payment_slip'];

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการจองห้องพัก</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: url('bg/sky.png') no-repeat center center/cover;
        margin: 0;
    }

    .container {
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    h2 {
        margin-bottom: 20px;
        color: black;
        text-align: center;
    }

    form {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    label {
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
    }

    input, select {
        width: calc(100% - 30px);
        padding: 12px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    button {
        width: 48%;
        padding: 12px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .submit-btn {
        background: #8c99bc;
        color: white;
    }

    .cancel-btn {
        background: #ccc;
        color: black;
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>แก้ไขข้อมูลการจองห้องพัก</h2>
        <form action="update_booking.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="booking_id" value="<?= $booking_id ?>">

            <label for="first_name">ชื่อผู้จอง</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>

            <label for="last_name">นามสกุลผู้จอง</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>

            <label for="checkin_date">วันที่เช็คอิน</label>
            <input type="date" id="checkin_date" name="checkin_date" value="<?= $checkin_date ?>" required>

            <label for="checkout_date">วันที่เช็คเอาท์</label>
            <input type="date" id="checkout_date" name="checkout_date" value="<?= $checkout_date ?>" required>

            <label for="room_number">เลขห้อง</label>
            <input type="text" id="room_number" name="room_number" value="<?= htmlspecialchars($room_number) ?>" required>

            <label for="type">ประเภท</label>
            <select id="type" name="type" required>
                <option value="ห้องพัก" <?= ($type == 'ห้องพัก') ? 'selected' : '' ?>>ห้องพัก</option>
                <option value="เต็นท์" <?= ($type == 'เต็นท์') ? 'selected' : '' ?>>เต็นท์</option>
            </select>

            <label for="price">ราคา</label>
            <input type="number" id="price" name="price" value="<?= $price ?>" step="0.01" required>

            <label for="description">รายละเอียด</label>
            <input type="text" id="description" name="description" value="<?= htmlspecialchars($description) ?>">

            <label for="payment_method">รูปแบบการชำระเงิน</label>
            <select id="payment_method" name="payment_method" required>
                <option value="โอนเงิน" <?= ($payment_method == 'โอนเงิน') ? 'selected' : '' ?>>โอนเงิน</option>
                <option value="เงินสด" <?= ($payment_method == 'เงินสด') ? 'selected' : '' ?>>เงินสด</option>
            </select>

            <label for="imgpayment">แนบสลิปการโอนเงิน</label>
            <input type="file" id="imgpayment" name="imgpayment">
            <?php if (!empty($payment_slip)): ?>
                <p>สลิปปัจจุบัน: <a href="<?= htmlspecialchars($payment_slip) ?>" target="_blank">ดูสลิป</a></p>
            <?php endif; ?>

            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">อัปเดตข้อมูล</button>
                <button type="button" class="cancel-btn" onclick="window.history.back();">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>

</html>
