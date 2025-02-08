<?php
session_start();
require 'db.php';

// ตรวจสอบว่ามีการส่ง room_code มาหรือไม่
if (isset($_GET['room_code'])) {
    $room_code = $_GET['room_code'];

    // ดึงข้อมูลห้องจากฐานข้อมูล
    $sql = "SELECT * FROM rooms WHERE room_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $room_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if (!$room) {
        echo "<script>alert('ไม่พบข้อมูลห้องพัก'); window.location.href = 'room_dashboard.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีรหัสห้องพักที่ต้องการแก้ไข'); window.location.href = 'edit_room.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลห้องพัก</title>
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
        <h2>แก้ไขข้อมูลห้องพัก</h2>
        <form action="update_room.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="room_code" value="<?php echo $room['room_code']; ?>">

            <label for="room_number">เลขห้อง</label>
            <input type="text" id="room_number" name="room_number" value="<?php echo $room['room_number']; ?>" required>

            <label for="price">ราคา</label>
            <input type="number" id="price" name="price" value="<?php echo $room['price']; ?>" required>

            <label for="type">ประเภท</label>
            <select id="type" name="type" required>
                <option value="">-- เลือกประเภท --</option>
                <option value="ห้องพัก" <?php echo ($room['type'] == 'ห้องพัก') ? 'selected' : ''; ?>>ห้องพัก</option>
                <option value="เต็นท์" <?php echo ($room['type'] == 'เต็นท์') ? 'selected' : ''; ?>>เต็นท์</option>
            </select>

            <label for="description">รายละเอียด</label>
            <input type="text" id="description" name="description" value="<?php echo $room['description']; ?>" required>

            <label for="image">รูปภาพ</label>
            <input type="file" id="image" name="image">

            <label for="isshow">สถานะการทำงาน</label>
            <label class="switch">
                <input type="checkbox" id="isshow" name="isshow" value="1"
                    <?php echo ($room['isshow'] == 1) ? 'checked' : ''; ?>>
                <span class="slider round"></span>
            </label>

            <br>
            <div class="button-group">
                <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                <button type="button" class="cancel-btn"
                    onclick="window.location.href='room_dashboard.php'">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>

</html>