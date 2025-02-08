<?php
require 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $id_card = $_POST['id_card'];
    $birthdate = $_POST['birthdate'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
    $userrole = 'user'; // กำหนดค่าเริ่มต้นเป็น user

    // ตรวจสอบว่า email มีอยู่แล้วหรือไม่
    $check_email_sql = "SELECT user_id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($check_email_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // ถ้า Email มีอยู่แล้ว
            echo "<script>alert('อีเมลนี้ถูกใช้ไปแล้ว กรุณาใช้อีเมลอื่น'); window.location.href='register.php';</script>";
            exit();
        }
        $stmt->close();
    }

    // SQL สำหรับ Insert ข้อมูล
    $sql = "INSERT INTO users (first_name, last_name, phone, email, id_card, birthdate, password, userrole, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $password, $userrole);
        
        if ($stmt->execute()) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.history.back();</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL');</script>";
    }
    
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
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
        width: calc(100% - 20px);
        padding: 12px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #8c99bc;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 15px;
    }

    a {
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>สมัครสมาชิก</h2>
        <form action="register.php" method="POST">
            <label for="first_name">ชื่อ</label>
            <input type="text" id="first_name" name="first_name" placeholder="ชื่อ" required>

            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" placeholder="นามสกุล" required>

            <label for="phone">เบอร์โทรศัพท์</label>
            <input type="text" id="phone" name="phone" placeholder="เบอร์โทรศัพท์" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required>

            <label for="id_card">เลขบัตรประชาชน</label>
            <input type="text" id="id_card" name="id_card" placeholder="เลขบัตรประชาชน" required>

            <label for="birthdate">วันเกิด</label>
            <input type="date" id="birthdate" name="birthdate" required onfocus="this.showPicker()">

            <label for="password">รหัสผ่าน</label>
            <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required>

            <button type="submit">สมัครสมาชิก</button>
            <br>
            <a href="login.php">เข้าสู่ระบบ</a>

        </form>
    </div>
</body>

</html>