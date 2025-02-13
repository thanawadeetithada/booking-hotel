<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['type'];
    $guest_count = $_POST['guest_count'];
    $room_count = $_POST['room_count'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $payment_method = $_POST['payment_method'];
    $status_payment = ($payment_method == "โอนเงิน") ? 'paid' : 'pending';
    $invoice_number = "INV-" . strtoupper(substr(md5(time() . rand()), 0, 10));
    $checkin = new DateTime($checkin_date);
    $checkout = new DateTime($checkout_date);
    $interval = $checkin->diff($checkout);
    $nights = $interval->days;
    
    if ($nights <= 0) {
        die("วันที่เช็คเอาท์ต้องมากกว่าวันที่เช็คอิน");
    }
    
    if ($room_type == "เต็นท์") {
        $total_amount = $price * $guest_count * $room_count * $nights;
    } else {
        $total_amount = $price * $room_count * $nights;
    }
    
    $paid_amount = ($status_payment == 'paid') ? $total_amount : 0;
    
    $payment_slip = NULL;
    if ($payment_method == "โอนเงิน" && isset($_FILES["imgpayment"]["name"]) && $_FILES["imgpayment"]["size"] > 0) {
        $target_dir = "uploads/";
        $file_ext = pathinfo($_FILES["imgpayment"]["name"], PATHINFO_EXTENSION);
        $payment_slip = $target_dir . $invoice_number . "." . $file_ext;

        if (!move_uploaded_file($_FILES["imgpayment"]["tmp_name"], $payment_slip)) {
            die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์สลิป");
        }
    }

        $stmt_invoice = $conn->prepare("INSERT INTO invoice 
        (invoice_number, first_name, last_name, checkin_date, checkout_date, room_number, room_type, 
        guest_count, room_count, price, description, payment_method, status_payment, payment_slip, 
        total_amount, paid_amount) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        $stmt_invoice->bind_param("sssssssiidssssdd", 
            $invoice_number, $first_name, $last_name, $checkin_date, $checkout_date,
            $room_number, $room_type, $guest_count, $room_count, $price, 
            $description, $payment_method, $status_payment, $payment_slip, 
            $total_amount, $paid_amount);
    
        if ($stmt_invoice->execute()) {
            echo "<script>alert('เพิ่มข้อมูลการจองและออกใบแจ้งหนี้สำเร็จ!'); window.location.href='dashboard_booking.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกใบแจ้งหนี้: " . $stmt_invoice->error;
        }
    
        $stmt_invoice->close();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Arial', sans-serif;
        height: 100vh;
        background: url('bg/sky.png') no-repeat center center/cover;
        margin: 0;
    }

    .nav-item a {
        color: white;
        margin-right: 1rem;
    }

    .navbar {
        padding: 20px;
    }

    .nav-link:hover {
        color: white;
    }

    .container {
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px;
    }

    h2 {
        margin-bottom: 20px;
        color: black;
        text-align: center;
        margin-top: 20px;
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

    .form-control {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    .form-label {
        margin-top: 10px;
        margin-bottom: 0;
    }

    .container-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 56px);
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <i class="fa-solid fa-bars text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                style="cursor: pointer;"></i>
            <div class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;Logout</a>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">รายการ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled">
                <li><a href="admin_dashboard.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li><a href="add_room.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-regular fa-money-bill-1"></i> ข้อมูลกำหนดราคาห้องพัก</a></li>
                <li><a href="dashboard_room.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-bed"></i> ข้อมูลห้องพัก</a></li>
                <li><a href="dashboard_user.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-user"></i> ข้อมูลลูกค้า</a></li>
                <li><a href="dashboard_booking.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-suitcase"></i> ข้อมูลการจองห้องพัก</a></li>
                <li><a href="view_messages.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-comment"></i> ข้อความจากผู้ใช้งาน</a></li>
            </ul>
        </div>
    </div>
    <div class="container-wrapper">
        <div class="container">
            <h2>เพิ่มข้อมูลการจองห้องพัก</h2>
            <form action="add_booking.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">ชื่อผู้จอง</label>
                        <input type="text" id="first_name" name="first_name" class="form-control"
                            placeholder="ชื่อผู้จอง" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">นามสกุลผู้จอง</label>
                        <input type="text" id="last_name" name="last_name" class="form-control"
                            placeholder="นามสกุลผู้จอง" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="checkin_date" class="form-label">วันที่เช็คอิน</label>
                        <input type="date" id="checkin_date" name="checkin_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="checkout_date" class="form-label">วันที่เช็คเอาท์</label>
                        <input type="date" id="checkout_date" name="checkout_date" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">ประเภท</label>
                        <select class="form-control" id="type" name="type" required onchange="fetchRooms()">
                            <option value="">-- เลือกประเภท --</option>
                            <?php
    include 'db.php';
    $room_query = "SELECT DISTINCT type FROM rooms WHERE isshow = 1";
    $result = $conn->query($room_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['type'] . "'>" . $row['type'] . "</option>";
        }
    }
    ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="room_number" class="form-label">เลขห้อง</label>
                        <select class="form-control" id="room_number" name="room_number" required>
                            <option value="">-- เลือกหมายเลขห้อง --</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">ราคา</label>
                        <input class="form-control" type="number" id="price" name="price" placeholder="ราคาห้อง (บาท)"
                            step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="payment_method" class="form-label">รูปแบบการชำระเงิน</label>
                        <select class="form-control" id="payment_method" name="payment_method" required
                            onchange="toggleSlipRequirement()">
                            <option value="">-- เลือกรูปแบบการชำระเงิน --</option>
                            <option value="โอนเงิน">โอนเงิน</option>
                            <option value="เงินสด">เงินสด</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="guest_count " class="form-label">จำนวนผู้เข้าพัก</label>
                        <input class="form-control" type="number" id="guest_count" name="guest_count"
                            placeholder="จำนวนผู้เข้าพัก" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_count" class="form-label">จำนวนห้อง</label>
                        <input class="form-control" type="number" id="room_count" name="room_count"
                            placeholder="จำนวนห้องพัก" required>

                    </div>
                </div>



                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">รายละเอียด</label>
                        <input class="form-control" type="text" id="description" name="description"
                            placeholder="รายละเอียดเพิ่มเติม">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12" id="slip_container" style="display: none;">
                        <label for="imgpayment" class="form-label">แนบสลิปการโอนเงิน</label><br>
                        <img id="preview-slip" src="" alt="Preview Slip" width="200px"
                            style="display:none; margin-bottom:10px; border: 1px solid #ccc; padding: 5px;">

                        <input class="form-control" type="file" id="imgpayment" name="imgpayment" accept="image/*">
                    </div>
                </div>

                <br>
                <div class="button-group">
                    <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                    <button type="button" class="cancel-btn" onclick="window.history.back();">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('imgpayment').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const preview = document.getElementById('preview-slip');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });

    function toggleSlipRequirement() {
        var paymentMethod = document.getElementById("payment_method").value;
        var slipContainer = document.getElementById("slip_container");
        var imgPayment = document.getElementById("imgpayment");
        var preview = document.getElementById("preview-slip");

        if (paymentMethod === "โอนเงิน") {
            slipContainer.style.display = "block";
            imgPayment.required = true;
        } else {
            slipContainer.style.display = "none";
            imgPayment.required = false;
        }
    }

    function fetchRooms() {
        var type = document.getElementById("type").value;
        var roomSelect = document.getElementById("room_number");
        var priceInput = document.getElementById("price");

        roomSelect.innerHTML = "<option value=''>-- เลือกหมายเลขห้อง --</option>";
        priceInput.value = "";

        if (type) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_rooms.php?type=" + encodeURIComponent(type), true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var rooms = JSON.parse(xhr.responseText);
                    if (rooms.length > 0) {
                        rooms.forEach(function(room) {
                            var option = document.createElement("option");
                            option.value = room.room_number;
                            option.textContent = room.room_number;
                            option.dataset.price = room.price;
                            roomSelect.appendChild(option);
                        });
                    } else {
                        roomSelect.innerHTML = "<option value=''>ไม่มีห้องว่าง</option>";
                    }
                }
            };
            xhr.send();
        }
    }

    document.getElementById("room_number").addEventListener("change", function() {
        var selectedOption = this.options[this.selectedIndex];
        var priceInput = document.getElementById("price");
        if (selectedOption.dataset.price) {
            priceInput.value = selectedOption.dataset.price;
        } else {
            priceInput.value = "";
        }
    });
    </script>
</body>

</html>