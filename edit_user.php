<?php
session_start();
require 'db.php';

// รับค่า user_id จาก URL
if (!isset($_GET['user_id'])) {
    die("ไม่มีการระบุ ID");
}

$user_id = $_GET['user_id'];

// ดึงข้อมูลลูกค้าจากฐานข้อมูล
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบข้อมูลลูกค้า");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลลูกค้า</title>
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

    select {
        width: 100%;
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
        <h2>แก้ไขข้อมูลลูกค้า</h2>
        <form action="update_user.php" method="POST">
            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">

            <label for="first_name">ชื่อ</label>
            <input type="text" id="first_name" name="first_name" value="<?= $row['first_name'] ?>" required>

            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" value="<?= $row['last_name'] ?>" required>

            <label for="phone">เบอร์โทรศัพท์</label>
            <input type="text" id="phone" name="phone" value="<?= $row['phone'] ?>" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= $row['email'] ?>" required>

            <label for="id_card">เลขบัตรประชาชน</label>
            <input type="text" id="id_card" name="id_card" value="<?= $row['id_card'] ?>" required>

            <label for="birthdate">วันเกิด</label>
            <input type="date" id="birthdate" name="birthdate" value="<?= $row['birthdate'] ?>" required>

            <label for="userrole">สถานะ</label>
            <select id="userrole" name="userrole" required>
                <option value="">-- เลือกประเภท --</option>
                <option value="user" <?= ($row['userrole'] == 'user') ? 'selected' : ''; ?>>user</option>
                <option value="admin" <?= ($row['userrole'] == 'admin') ? 'selected' : ''; ?>>admin</option>
            </select>

            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">บันทึก</button>
                <button type="button" class="cancel-btn"
                    onclick="window.location.href='dashboard_user.php'">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>

</html>