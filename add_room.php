<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $room_code = isset($_POST['room_code']) ? trim($_POST['room_code']) : '';
    $room_number = isset($_POST['room_number']) ? trim($_POST['room_number']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $isshow = isset($_POST['isshow']) ? 1 : 0;

    try {
        // ตรวจสอบค่าซ้ำ
        $check_sql = "SELECT * FROM rooms WHERE room_code = ? OR room_number = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $room_code, $room_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('❌ รหัสห้อง หรือ เลขห้องนี้ถูกใช้แล้ว!');
                    window.history.back();
                  </script>";
            exit(); // หยุดการทำงานทันที
        }

        // จัดการไฟล์รูปภาพ
        $image_path = "";
        if (!empty($_FILES["image"]["name"])) {
            $image_name = basename($_FILES["image"]["name"]);
            $image_path = "uploads/" . $image_name;
            move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        }

        // ใช้ Prepared Statement เพื่อเพิ่มข้อมูล
        $sql = "INSERT INTO rooms (room_code, room_number, price, type, description, image_path, isshow) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsssi", $room_code, $room_number, $price, $type, $description, $image_path, $isshow);
        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ บันทึกข้อมูลสำเร็จ!');
                    window.location.href = 'room_dashboard.php';
                  </script>";
        } else {
            throw new Exception("เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "<script>
                alert('❌ ข้อผิดพลาด: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลกำหนดราคาห้อง</title>
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


    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #28a745;
    }

    input:checked+.slider:before {
        transform: translateX(18px);
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>ข้อมูลกำหนดราคาห้อง</h2>
        <form action="add_room.php" method="POST" enctype="multipart/form-data">
            <label for="room_code">รหัสห้อง</label>
            <input type="text" id="room_code" name="room_code" placeholder="รหัสห้อง" required>

            <label for="room_number">เลขห้อง</label>
            <input type="text" id="room_number" name="room_number" placeholder="เลขห้อง" required>

            <label for="price">ราคา</label>
            <input type="number" id="price" name="price" placeholder="ราคา" required>

            <label for="type">ประเภท</label>
            <select id="type" name="type" required>
                <option value="">-- เลือกประเภท --</option>
                <option value="ห้องพัก">ห้องพัก</option>
                <option value="เต็นท์">เต็นท์</option>
            </select>

            <label for="description">รายละเอียด</label>
            <input type="text" id="description" name="description" placeholder="รายละเอียด" required>

            <label for="image">รูปภาพ</label>
            <input type="file" id="image" name="image" required>

            <label for="isshow">สถานะการทำงาน</label>
            <label class="switch">
                <input type="checkbox" id="isshow" name="isshow" value="1" checked>
                <span class="slider round"></span>
            </label>
            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                <button type="button" class="cancel-btn" onclick="window.history.back();">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>

</html>