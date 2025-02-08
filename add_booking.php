<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'] ?? '';  // ถ้ามีหมายเลขโทรศัพท์
    $email = $_POST['email'] ?? '';  // ถ้ามีอีเมล
    $id_card = $_POST['id_card'] ?? '';  // ถ้ามีบัตรประชาชน
    $birthdate = $_POST['birthdate'] ?? '0000-00-00';  // ถ้าไม่มี ให้ใส่ค่าเริ่มต้น
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $room_number = $_POST['room_number'];
    $payment_method = $_POST['payment_method'];

    // อัปโหลดไฟล์สลิปการโอนเงิน (ถ้ามีการแนบ)
    $payment_slip = "";
    if (!empty($_FILES["imgpayment"]["name"])) {
        $target_dir = "uploads/";
        $payment_slip = $target_dir . basename($_FILES["imgpayment"]["name"]);
        move_uploaded_file($_FILES["imgpayment"]["tmp_name"], $payment_slip);
    }

    // ค้นหา user_id จากชื่อและนามสกุล
    $user_query = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("ss", $first_name, $last_name);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // ถ้าไม่พบ user_id ให้สร้างใหม่
    if (!$user_id) {
        $password_hash = password_hash("defaultpassword", PASSWORD_DEFAULT); // ตั้งรหัสผ่านเริ่มต้น
        $insert_user_query = "INSERT INTO users (first_name, last_name, phone, email, id_card, birthdate, password, userrole) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($insert_user_query);
        $stmt->bind_param("sssssss", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $password_hash);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id; // ดึง `user_id` ที่เพิ่งสร้าง
        } else {
            die("❌ เกิดข้อผิดพลาดในการสร้างบัญชีผู้ใช้: " . $stmt->error);
        }
        $stmt->close();
    }

    // ค้นหา room_id จากหมายเลขห้อง
    $room_query = "SELECT room_id FROM rooms WHERE room_number = ?";
    $stmt = $conn->prepare($room_query);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $stmt->bind_result($room_id);
    $stmt->fetch();
    $stmt->close();

    if (!$room_id) {
        die("❌ ไม่พบข้อมูลห้องพักในระบบ");
    }

    // เพิ่มข้อมูลการจอง
    $insert_query = "INSERT INTO bookings (user_id, room_id, checkin_date, checkout_date, payment_method, payment_slip, status_payment) 
                     VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iissss", $user_id, $room_id, $checkin_date, $checkout_date, $payment_method, $payment_slip);
    
    if ($stmt->execute()) {
        echo "<script>alert('✅ บันทึกข้อมูลการจองสำเร็จ!'); window.location.href='dashboard_booking.php';</script>";
    } else {
        echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการจองห้องพัก</title>
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

    input {
        width: calc(100% - 30px);
        padding: 12px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    select {
        width: 98%;
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
        <h2>เพิ่มข้อมูลการจองห้องพัก</h2>
        <form action="add_booking.php" method="POST" enctype="multipart/form-data">

            <label for="first_name">ชื่อผู้จอง</label>
            <input type="text" id="first_name" name="first_name" placeholder="ชื่อผู้จอง" required>

            <label for="last_name">นามสกุลผู้จอง</label>
            <input type="text" id="last_name" name="last_name" placeholder="นามสกุลผู้จอง" required>

            <label for="checkin_date">วันที่เช็คอิน</label>
            <input type="date" id="checkin_date" name="checkin_date" required>

            <label for="checkout_date">วันที่เช็คเอาท์</label>
            <input type="date" id="checkout_date" name="checkout_date" required>


            <label for="room_number">เลขห้อง</label>
            <input type="text" id="room_number" name="room_number" placeholder="หมายเลขห้อง" required>

            <label for="type">ประเภท</label>
            <select id="type" name="type" required>
                <option value="">-- เลือกประเภท --</option>
                <option value="ห้องพัก">ห้องพัก</option>
                <option value="เต็นท์">เต็นท์</option>
            </select>

            <label for="price">ราคา</label>
            <input type="number" id="price" name="price" placeholder="ราคาห้อง (บาท)" step="0.01" required>

            <label for="description">รายละเอียด</label>
            <input type="text" id="description" name="description" placeholder="รายละเอียดเพิ่มเติม">

            <label for="payment_method">รูปแบบการชำระเงิน</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">-- เลือกรูปแบบการชำระเงิน --</option>
                <option value="โอนเงิน">โอนเงิน</option>
                <option value="เงินสด">เงินสด</option>
            </select>

            <label for="imgpayment">แนบสลิปการโอนเงิน</label>
            <input type="file" id="imgpayment" name="imgpayment">

            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                <button type="button" class="cancel-btn" onclick="window.history.back();">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>

</html>