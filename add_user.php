<?php
include 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $id_card = trim($_POST['id_card']);
    $birthdate = $_POST['birthdate'];
    $userrole = $_POST['userrole'];

    // ตั้งรหัสผ่านเริ่มต้น
    $default_password = "123456"; 
    $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);

    // ตรวจสอบว่ามีอีเมลนี้อยู่แล้วหรือไม่
    $check_email_sql = "SELECT email FROM users WHERE email = ?";
    if ($check_stmt = $conn->prepare($check_email_sql)) {
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('อีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น!'); history.back();</script>";
            exit(); // หยุดการทำงาน
        }

        $check_stmt->close();
    }

    // ตรวจสอบว่ามีเลขบัตรประชาชนซ้ำหรือไม่
    $check_id_card_sql = "SELECT id_card FROM users WHERE id_card = ?";
    if ($check_stmt = $conn->prepare($check_id_card_sql)) {
        $check_stmt->bind_param("s", $id_card);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('เลขบัตรประชาชนนี้ถูกใช้แล้ว กรุณาตรวจสอบ!'); history.back();</script>";
            exit();
        }

        $check_stmt->close();
    }

    // เพิ่มข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO users (first_name, last_name, phone, email, id_card, birthdate, password, userrole)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $hashed_password, $userrole);

        if ($stmt->execute()) {
            echo "<script>alert('เพิ่มข้อมูลลูกค้าสำเร็จ!'); window.location.href='dashboard_user.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "'); history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL'); history.back();</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลลูกค้า</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $("#email").on("blur", function () {
            var email = $(this).val();
            if (email.length > 0) {
                $.ajax({
                    url: "check_email.php",
                    type: "POST",
                    data: { email: email },
                    success: function (response) {
                        if (response.trim() == "used") {
                            alert("อีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น!");
                            $("#email").val("").focus();
                        }
                    }
                });
            }
        });

        $("#id_card").on("blur", function () {
            var id_card = $(this).val();
            if (id_card.length > 0) {
                $.ajax({
                    url: "check_id_card.php",
                    type: "POST",
                    data: { id_card: id_card },
                    success: function (response) {
                        if (response.trim() == "used") {
                            alert("เลขบัตรประชาชนนี้ถูกใช้แล้ว กรุณาตรวจสอบ!");
                            $("#id_card").val("").focus();
                        }
                    }
                });
            }
        });
    });
    </script>
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
        width: calc(100% - 20px);
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
        <h2>เพิ่มข้อมูลลูกค้า</h2>
        <form action="add_user.php" method="POST">
            <label for="first_name">ชื่อ</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="phone">เบอร์โทรศัพท์</label>
            <input type="text" id="phone" name="phone" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="id_card">เลขบัตรประชาชน</label>
            <input type="text" id="id_card" name="id_card" required>

            <label for="birthdate">วันเกิด</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="userrole">สถานะ</label>
            <select id="userrole" name="userrole" required>
                <option value="">-- เลือกประเภท --</option>
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>

            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">บันทึก</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='dashboard_user.php'">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>
</html>
