<?php
session_start();
include 'db.php';

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;

$startDateFormatted = date('Y-m-d', strtotime($startDate));
$endDateFormatted = date('Y-m-d', strtotime($endDate));

$searchPerformed = isset($_POST['startDate']) && isset($_POST['endDate']);

$sql_rooms = "SELECT r.room_code, r.room_number, r.price, r.type, r.description, r.image_path
        FROM rooms r
        WHERE r.isshow = 1
        AND r.type = 'ห้องพัก'
        AND NOT EXISTS (
            SELECT 1 FROM invoice b
            WHERE b.room_number = r.room_number
            AND (
                (b.checkin_date BETWEEN '$startDateFormatted' AND '$endDateFormatted') OR
                (b.checkout_date BETWEEN '$startDateFormatted' AND '$endDateFormatted') OR
                ('$startDateFormatted' BETWEEN b.checkin_date AND b.checkout_date)
            )
        )";

$result_rooms = $conn->query($sql_rooms);

$rooms = [];
if ($result_rooms->num_rows > 0) {
    while ($row = $result_rooms->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$sql_tents = "SELECT r.room_number, r.price, r.type, r.description, r.image_path
        FROM rooms r
        WHERE r.isshow = 1 AND r.type = 'เต็นท์'";

$result_tents = $conn->query($sql_tents);

$tents = [];
if ($result_tents->num_rows > 0) {
    while ($row = $result_tents->fetch_assoc()) {
        $tents[] = $row;
    }
}

$bookedTents = [];
if ($searchPerformed) {
    $sql_tent_booking = "SELECT room_number FROM invoice
                         WHERE room_type = 'เต็นท์'
                         AND (
                            (checkin_date BETWEEN '$startDateFormatted' AND '$endDateFormatted') OR
                            (checkout_date BETWEEN '$startDateFormatted' AND '$endDateFormatted') OR
                            ('$startDateFormatted' BETWEEN checkin_date AND checkout_date)
                         )";

    $result_tent_booking = $conn->query($sql_tent_booking);

    if ($result_tent_booking->num_rows > 0) {
        while ($row = $result_tent_booking->fetch_assoc()) {
            $bookedTents[] = $row['room_number'];
        }
    }
}

$conn->close();

$response = [];

if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'not_logged_in';
} else {
    $response['status'] = 'logged_in';
    $response['first_name'] = $_SESSION['first_name'] ?? 'Guest';
    $response['last_name'] = $_SESSION['last_name'] ?? 'User';
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองห้องพัก</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background: #414678;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .logo-img {
        width: 80px;
        height: 80px;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 5px;
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

    .btn-custom-search {
        width: auto;
        background-color: #007bff;
        color: white;
        font-size: 18px;
    }

    .btn-custom-search:hover {
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
    }

    .hotel-listing {
        display: flex;
        justify-content: center;
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
        height: auto;
        object-fit: cover;
    }

    .hotel-info {
        width: 100%;
        padding: 40px 40px 15px 40px;
        position: relative;
    }

    .empty-hotel {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }

    .empty-hotel-details {
        display: flex;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 2px solid #ddd;
        justify-content: center;
        align-items: center;
        align-content: center;
        padding: 30px;
    }

    .empty-hotel-details p {
        margin: 0;
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
        padding: 0 20px 20px 20px;
    }

    .btn-reserve {
        display: flex;
        justify-content: flex-end;
    }

    .form-control {
        width: auto;
    }

    .hotel-icon {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .tent-box {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .tent-icon {
        font-size: 3rem;
        color: blue;
    }

    .tent-icon.booked {
        color: gray !important;
        opacity: 0.5;
        cursor: not-allowed;
    }

    .tent-number {
        margin-top: 5px;
        font-size: 1.2rem;
        font-weight: bold;
        color: black;
        text-align: center;
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
        margin: 40px auto;
    }

    .news {
        text-align: center;
        padding: 40px;
    }

    .news-item img {
        width: 50%;
        border-radius: 10px;
    }

    .news-item p {
        font-size: 1.5rem;
    }

    .gallery {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 40px;
    }

    .gallery-item img {
        width: 300px;
        height: 250px;
        border-radius: 10px;
    }

    .gallery-item p {
        color: #535d4c;
    }

    footer a {
        color: white;
        text-decoration: none;
        font-size: 1.2rem;
    }

    footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #414678;
        color: white;
        text-align: center;
        padding: 15px;
        flex-wrap: wrap;
    }

    footer nav {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-grow: 1;
    }

    footer .logo-footer {
        display: flex;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    nav {
        display: flex;
        gap: 15px;
    }

    .logo-footer img {
        width: 100px;
        height: auto;
    }

    .tent-icon.selected {
        color: green !important;
    }

    @media (max-width: 390px) {
        .logo-img {
            width: 50px;
            height: 50px;
        }

        .logo-container i {
            font-size: 1rem;
            margin-bottom: 10px;
            margin-right: 10px;
        }

        .nav-links a {
            font-size: 0.8rem !important;
        }
    }

    @media (max-width: 768px) {
        .hero {
            height: 25vh !important;
        }

        .hotel-info {
            padding: 10px;
        }

        .hotel-listing {
            padding: 0px;
            margin-bottom: 10px;
        }

        .hotel-info h3 {
            font-size: 0.8rem;
        }

        .hotel-info p {
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .hotel-info label {
            font-size: 0.8rem;
        }

        .hotel-info select {
            font-size: 0.8rem;
            padding: 5px;
        }

        .btn-custom {
            font-size: 0.8rem;
            padding: 5px;
        }

        .hotel-icon i {
            font-size: 1rem;
        }

        .tent-number {
            font-size: 0.8rem;
        }

        .tent-box {
            margin-top: 5px;
        }

        .hotel-icon-container {
            margin-bottom: 5px;
        }

        .hotel-icon {
            gap: 5px;
        }

        .hotel-image {
            width: 150px;
            height: auto;
        }

        .logo-container {
            padding-left: 10px;
            padding-top: 10px;
        }

        .navbar {
            padding: 5px;
        }

        .nav-links a {
            font-size: 1rem;
        }

        .gallery,
        .review {
            flex-direction: column;
            align-items: center;
        }

        footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        footer nav {
            flex-direction: column;
            gap: 10px;
        }

        .news-item img {
            width: 300px;
            height: 250px;
            border-radius: 10px;
        }

    }

    .fa-arrow-left {
        color: white;
    }

    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('bg/service.png') center/cover no-repeat;
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-top: 80px;
        margin-top: 6rem;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo-container">
            <i class="fa-solid fa-arrow-left" onclick="goBack()"></i>
            <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="service.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="hero"></header>

    <div class="container">
        <div class="booking-container">
            <h3 class="text-center mb-4">📅 จองห้องพักของคุณ</h3>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">📅 วันที่เข้าพัก - วันที่ออก</label>
                    <div class="input-group">
                        <input type="text" id="startDate" autocomplete="off" name="startDate" class="form-control"
                            placeholder="เช็คอิน"
                            value="<?= isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate']) : '' ?>">
                        <span class="input-group-text">—</span>
                        <input type="text" id="endDate" autocomplete="off" name="endDate" class="form-control"
                            placeholder="เช็คเอาท์"
                            value="<?= isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : '' ?>">
                    </div>
                </div>
                <div class="row g-3 mb-3 justify-content-center">
                    <div class="col-md-6">
                        <label class="form-label">👨‍👩‍👧‍👦 จำนวนคนเข้าพัก</label>
                        <div class="input-group text-center">
                            <button type="button" class="btn btn-outline-secondary minus-btn"
                                data-target="adults">-</button>
                            <input type="text" id="adults" name="adults" class="form-control small-input"
                                value="<?= isset($_POST['adults']) ? htmlspecialchars($_POST['adults']) : '2' ?>">
                            <button type="button" class="btn btn-outline-secondary plus-btn"
                                data-target="adults">+</button>
                        </div>
                    </div>

                </div>
                <div class="btn-sesrch-book">
                    <button type="submit" id="searchButton" class="btn btn-custom-search">🔍 ค้นหา</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($searchPerformed && !empty($rooms)): ?>
    <section class="hotel-listing">
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
                    <?php foreach (array_unique($room_numbers_by_image[$room['image_path']]) as $room_number): ?>
                    <option value="<?= htmlspecialchars($room_number) ?>">ห้อง <?= htmlspecialchars($room_number) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="btn-reserve">
                    <button type="button" class="btn btn-custom"
                        onclick="bookRoom('<?= htmlspecialchars($room['room_number']) ?>', '<?= htmlspecialchars($room['type']) ?>', '<?= htmlspecialchars($room['price']) ?>')">
                        จองห้องพัก
                    </button>
                </div>
            </div>
        </div>
        <?php 
            endif;
        endforeach; 
    ?>
    </section>
    <?php endif; ?>


    <?php if ($searchPerformed && !empty($tents)): ?>
    <section class="hotel-listing">
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
                        <?php foreach ($tents as $tent): 
                $roomNumber = $tent['room_number'];
                $isBooked = in_array($roomNumber, $bookedTents);
            ?>
                        <div class="tent-box">
                            <i class="fa-solid fa-tent tent-icon <?= $isBooked ? 'booked' : '' ?>"
                                data-room-number="<?= htmlspecialchars($roomNumber) ?>"></i>
                            <span class="tent-number"><?= $roomNumber ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="btn-reserve">
                    <button type="button" id="bookTentBtn" class="btn btn-custom"
                        data-price="<?= htmlspecialchars($tent['price']) ?>">
                        จองเต็นท์
                    </button>
                </div>

            </div>
        </div>
        <?php 
            endif;
        endforeach; 
    ?>
    </section>
    <?php endif; ?>

    <?php if ($searchPerformed && empty($rooms) && empty($tents)): ?>
    <section class="empty-hotel">
        <div class="empty-hotel-details">
            <p class="text-center">ไม่มีห้องพักหรือเต็นท์ที่พร้อมให้จองในขณะนี้</p>
        </div>
    </section>
    <?php endif; ?>

    <section class="news">
        <h2>ข่าวสารประชาสัมพันธ์</h2>
        <div class="news-item">
            <img src="img/1.jpg" alt="โปรโมชั่น">
        </div>
    </section>

    <section class="gallery">
        <div class="gallery-item"><img src="img/mg1.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="gallery-item"><img src="img/img2.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="gallery-item"><img src="img/img3.jpg" alt="พระอาทิตย์ตก">
        </div>
    </section>

    <footer>
        <div></div>
        <nav style="font-size: 25px;">
            <a href="index.php">Home</a>
            <a href="service.php">Services</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="logo-footer">
            <img src="bg/logo.png" alt="โลโก้ผาชมดาว">
        </div>
    </footer>

    <script>
    function goBack() {
        window.history.back();
    }
    $(document).ready(function() {
        $("#startDate, #endDate").datepicker({
            dateFormat: "dd M yy",
            minDate: 0
        });
        if ($("#startDate").val()) {
            $("#startDate").datepicker("setDate", new Date($("#startDate").val()));
        }
        if ($("#endDate").val()) {
            $("#endDate").datepicker("setDate", new Date($("#endDate").val()));
        }

        $("#startDate").on("change", function() {
            var minDate = $(this).datepicker("getDate");
            $("#endDate").datepicker("option", "minDate", minDate);
        });
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

        $("form").on("submit", function(event) {
            var startDate = $("#startDate").val();
            var endDate = $("#endDate").val();

            if (!startDate || !endDate) {
                alert("กรุณากรอกวันที่เข้าพัก และวันที่ออกให้ครบถ้วน");
                event.preventDefault();
            }
        });
        $(".tent-icon").click(function() {
            if (!$(this).hasClass("booked")) {
                $(".tent-icon").removeClass("selected");
                $(this).toggleClass("selected");

                let roomNumber = $(this).attr("data-room-number");
                console.log("Selected Tent Room Number:", roomNumber);

                $("#bookTentBtn").off("click").on("click", function() {
                    let tentType = "เต็นท์";
                    let tentPrice = $(this).data("price");
                    bookTent(roomNumber, tentType, tentPrice);
                });
            }
        });
    });

    function bookRoom(roomNumber, roomType, price) {
        $.ajax({
            url: "check_login.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === "not_logged_in") {
                    window.location.href = "login.php";
                } else if (response.status === "logged_in") {
                    let checkinDate = $("#startDate").val();
                    let checkoutDate = $("#endDate").val();
                    let guestCount = $("#adults").val();
                    let roomCount = $("#rooms").val();
                    let firstName = response.first_name;
                    let lastName = response.last_name;
                    let description = "การจองห้องพักผ่านระบบ";
                    let paymentMethod = "โอนเงิน";

                    if (!checkinDate || !checkoutDate) {
                        alert("กรุณากรอกวันที่เข้าพักและวันที่ออกจากที่พัก");
                        return;
                    }

                    let data = {
                        first_name: firstName,
                        last_name: lastName,
                        checkin_date: checkinDate,
                        checkout_date: checkoutDate,
                        room_number: roomNumber,
                        room_type: roomType,
                        guest_count: guestCount,
                        price: price,
                        description: description,
                        payment_method: paymentMethod
                    };

                    $.ajax({
                        url: "insert_booking.php",
                        method: "POST",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                let confirmBooking = confirm("คุณต้องการจองห้องพักใช่ไหม?");
                                if (confirmBooking) {
                                    window.location.href =
                                    `payment.php?booking_id=${response.booking_id}`;
                                }
                            } else {
                                console.error("Booking Error:", response);
                                alert("เกิดข้อผิดพลาดในการจอง: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", xhr.responseText);
                            alert("เกิดข้อผิดพลาด: " + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", xhr.responseText);
                alert("เกิดข้อผิดพลาด: " + xhr.responseText);
            }
        });
    }

    function bookTent(roomNumber, roomType, price) {
        $.ajax({
            url: "check_login.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === "not_logged_in") {
                    window.location.href = "login.php";
                } else if (response.status === "logged_in") {
                    let checkinDate = $("#startDate").val();
                    let checkoutDate = $("#endDate").val();
                    let guestCount = $("#adults").val();
                    let roomCount = $("#rooms").val();
                    let firstName = response.first_name || "Guest";
                    let lastName = response.last_name || "User";
                    let description = "การจองเต็นท์ผ่านระบบ";
                    let paymentMethod = "โอนเงิน";

                    if (!checkinDate || !checkoutDate) {
                        alert("กรุณากรอกวันที่เข้าพักและวันที่ออกจากที่พัก");
                        return;
                    }

                    let data = {
                        first_name: firstName,
                        last_name: lastName,
                        checkin_date: checkinDate,
                        checkout_date: checkoutDate,
                        room_number: roomNumber,
                        room_type: roomType,
                        guest_count: guestCount,
                        price: price,
                        description: description,
                        payment_method: paymentMethod
                    };

                    $.ajax({
                        url: "insert_booking.php",
                        method: "POST",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                let confirmBooking = confirm("คุณต้องการจองเต็นท์ใช่ไหม?");
                                if (confirmBooking) {
                                    window.location.href =
                                    `payment.php?booking_id=${response.booking_id}`;
                                }
                            } else {
                                alert("เกิดข้อผิดพลาดในการจอง: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", xhr.responseText);
                            alert("เกิดข้อผิดพลาด: " + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", xhr.responseText);
                alert("เกิดข้อผิดพลาด: " + xhr.responseText);
            }
        });
    }
    </script>
</body>

</html>