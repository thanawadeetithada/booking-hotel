<?php
include 'db.php';
// ดึงข้อมูลห้องพักที่เปิดให้จอง
$sql = "SELECT room_code, room_number, price, type, description, image_path FROM rooms WHERE isshow = 1";
$result = $conn->query($sql);

$rooms = [];
$tents = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['type'] === 'ห้องพัก') {
            $rooms[] = $row; // เก็บข้อมูลห้องพักทั้งหมด
        }
        if ($row['type'] === 'เต็นท์') {
            $tents[] = $row; // เก็บข้อมูลเต็นท์ทั้งหมด
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองห้องพัก</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- jQuery & jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('bg/service.png') center/cover no-repeat;
        height: 90vh;
    }

    .hero .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .hero h1 {
        font-size: 5rem;
        z-index: 1;
    }

    .hero p {
        font-size: 2rem;
        z-index: 1;
    }

    .hero-img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .logo-img {
        width: 100px;
        height: 100px;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 97%;
        top: 0;
        left: 0;
        padding: 10px 20px;
        z-index: 1000;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
    }

    .nav-links li {
        display: inline;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        font-size: 1.2rem;
        padding: 10px;
        transition: 0.3s;
    }

    .nav-links a:hover {
        border-radius: 5px;
    }

    .booking-container {
        max-width: 600px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .input-group-text {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .small-input {
        width: 50px;
        text-align: center;
    }

    .btn-custom {
        width: auto;
        background-color: #007bff;
        color: white;
        font-size: 18px;
    }

    .btn-custom:hover {
        background-color: #0056b3;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-container i {
        margin-bottom: 20px;
        font-size: 25px;
        margin-right: 10px;
        color: black;
    }

    /* /////card hote; */
    .hotel-listing {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }

    .hotel-card {
        display: flex;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 900px;
        overflow: hidden;
        border: 2px solid #ddd;
    }

    .hotel-image {
        width: 400px;
        height: 300px;
        object-fit: cover;
    }

    .hotel-info {
        width: 100%;
        padding: 40px 40px 15px 40px;
        position: relative;
    }

    .booking-button {
        display: block;
        margin-left: auto;
    }



    .hotel-info h3 {
        font-size: 1.3rem;
        color: #0053A6;
        margin: 0;
    }

    .hotel-listing {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        /* ระยะห่างระหว่างแถว */
        padding: 20px;
    }

    .btn-reserve {
        display: flex;
        justify-content: flex-end;
    }

    .form-control {
        width: auto;
    }

    /* /////////////tent */
    .hotel-icon {
        display: flex;
        justify-content: center;
        gap: 15px;
        /* ระยะห่างระหว่างไอคอน */
    }

    .hotel-icon i {
        font-size: 2rem;
        color: #0073E6;
    }

    .hotel-icon-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        justify-content: center;
        margin-bottom: 20px;
    }

    .btn-sesrch-book {
        display: flex;
        justify-content: center;
    }

    .container {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }
    </style>
</head>

<body>

    <header class="hero">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="logo-container">
                <i class="fa-solid fa-arrow-left" onclick="goBack()"></i>
                <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="overlay"></div>
    </header>

    <div class="container">
        <div class="booking-container">
            <h3 class="text-center mb-4">📅 จองห้องพักของคุณ</h3>

            <form action="process.php" method="POST">
                <!-- Date Picker -->
                <div class="mb-3">
                    <label class="form-label">📅 วันที่เข้าพัก - วันที่ออก</label>
                    <div class="input-group">
                        <input type="text" id="startDate" name="startDate" class="form-control" placeholder="เช็คอิน">
                        <span class="input-group-text">—</span>
                        <input type="text" id="endDate" name="endDate" class="form-control" placeholder="เช็คเอาท์">
                    </div>
                </div>

                <!-- จำนวนผู้ใหญ่ และ จำนวนห้อง (บรรทัดเดียวกัน) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">👨‍👩‍👧‍👦 จำนวนผู้ใหญ่</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary minus-btn"
                                data-target="adults">-</button>
                            <input type="text" id="adults" name="adults" class="form-control small-input" value="2">
                            <button type="button" class="btn btn-outline-secondary plus-btn"
                                data-target="adults">+</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">🏨 จำนวนห้อง</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary minus-btn"
                                data-target="rooms">-</button>
                            <input type="text" id="rooms" name="rooms" class="form-control small-input" value="1">
                            <button type="button" class="btn btn-outline-secondary plus-btn"
                                data-target="rooms">+</button>
                        </div>
                    </div>
                </div>

                <div class="btn-sesrch-book">
                    <button type="submit" class="btn btn-custom">🔍 ค้นหา</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($rooms)): ?>
    <section class="hotel-listing">
        <!-- <h2 class="text-center">🏨 ห้องพัก</h2> -->

        <?php 
    $displayed_images = []; 
    $room_numbers_by_image = []; 
    foreach ($rooms as $room) {
        $room_numbers_by_image[$room['image_path']][] = $room['room_number'];
    }

    foreach ($rooms as $room): 
        if (!in_array($room['image_path'], $displayed_images)): 
            $displayed_images[] = $room['image_path'];
    ?>
        <div class="hotel-card">
            <img src="<?= htmlspecialchars($room['image_path']) ?>" alt="ห้องพัก" class="hotel-image">
            <div class="hotel-info">
                <h3>ข้อมูลห้องพัก</h3>
                <p><?= nl2br(htmlspecialchars($room['description'])) ?></p>
                <p><strong>ราคา <?= number_format($room['price'], 2) ?> บาท/คืน</strong></p>
                <label for="roomSelect<?= md5($room['image_path']) ?>">เลือกหมายเลขห้อง </label><br>

                <select class="form-control" id="roomSelect<?= md5($room['image_path']) ?>" name="room_number">
                    <?php 
                    foreach (array_unique($room_numbers_by_image[$room['image_path']]) as $room_number): ?>
                    <option value="<?= htmlspecialchars($room_number) ?>">ห้อง <?= htmlspecialchars($room_number) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <div class="btn-reserve">
                    <button type="button" class="btn btn-custom mt-2">จองห้องพัก</button>
                </div>
            </div>
        </div>
        <?php 
        endif;
    endforeach; 
    ?>
    </section>

    <?php endif; ?>

    <!-- Section สำหรับเต็นท์ -->
    <?php if (!empty($tents)): ?>
    <section class="hotel-listing">
        <!-- <h2 class="text-center">⛺ เต็นท์</h2> -->
        <?php 
    $displayed_images = [];
    foreach ($tents as $tent): 
        if (!in_array($tent['image_path'], $displayed_images)): 
            $displayed_images[] = $tent['image_path'];
    ?>
        <div class="hotel-card">
            <img src="<?= htmlspecialchars($tent['image_path']) ?>" alt="เต็นท์" class="hotel-image">
            <div class="hotel-info">
                <h3>ข้อมูลเต็นท์</h3>
                <p><?= nl2br(htmlspecialchars($tent['description'])) ?></p>
                <p><strong>ราคา <?= number_format($tent['price'], 2) ?> บาท/คน</strong></p>
                <div class="hotel-icon-container">
                    <div class="hotel-icon">
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                    </div>
                    <div class="hotel-icon">
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                        <i class="fa-solid fa-tent" style="color:gray"></i>
                    </div>
                </div>

                <div class="btn-reserve">
                    <button type="button" class="btn btn-custom">จองเต็นท์</button>

                </div>
            </div>
        </div>
        <?php 
        endif;
    endforeach; 
    ?>
    </section>

    <?php endif; ?>

    <!-- กรณีไม่มีข้อมูล -->
    <?php if (empty($rooms) && empty($tents)): ?>
    <p class="text-center">ไม่มีห้องพักหรือเต็นท์ที่พร้อมให้จองในขณะนี้</p>
    <?php endif; ?>

    <!-- JavaScript -->
    <script>
    $(document).ready(function() {
        // Datepicker
        $("#startDate, #endDate").datepicker({
            dateFormat: "dd M yy",
            minDate: 0
        });

        $("#startDate").on("change", function() {
            var minDate = $(this).datepicker("getDate");
            $("#endDate").datepicker("option", "minDate", minDate);
        });

        // ปุ่มเพิ่ม/ลดจำนวน
        $(".plus-btn").click(function() {
            var target = $(this).data("target");
            var input = $("#" + target);
            var value = parseInt(input.val()) + 1;
            input.val(value);
        });

        $(".minus-btn").click(function() {
            var target = $(this).data("target");
            var input = $("#" + target);
            var value = parseInt(input.val()) - 1;
            if (value > 0) input.val(value);
        });
    });
    </script>

</body>

</html>