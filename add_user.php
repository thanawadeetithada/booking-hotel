<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $id_card = trim($_POST['id_card']);
    $birthdate = $_POST['birthdate'];
    $userrole = $_POST['userrole'];
    $default_password = "123456"; 
    $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);
    $check_email_sql = "SELECT email FROM users WHERE email = ?";
    if ($check_stmt = $conn->prepare($check_email_sql)) {
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('อีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น!'); history.back();</script>";
            exit();
        }

        $check_stmt->close();
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#email").on("blur", function() {
            var email = $(this).val();
            if (email.length > 0) {
                $.ajax({
                    url: "check_email.php",
                    type: "POST",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.trim() == "used") {
                            alert("อีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น!");
                            $("#email").val("").focus();
                        }
                    }
                });
            }
        });

        $("#id_card").on("blur", function() {
            var id_card = $(this).val();
            if (id_card.length > 0) {
                $.ajax({
                    url: "check_id_card.php",
                    type: "POST",
                    data: {
                        id_card: id_card
                    },
                    success: function(response) {
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
        font-family: 'Prompt', sans-serif;
        height: auto;
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
        max-width: 600px;
        margin: 20px;
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
        margin-left: 5px;
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
                            class="fa-regular fa-money-bill-1"></i> ตั้งค่าราคาห้องพัก</a></li>
                <li><a href="dashboard_room.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-bed"></i> รายละเอียดห้องพัก</a></li>
                <li><a href="dashboard_user.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-user"></i> รายชื่อลูกค้า</a></li>
                <li><a href="dashboard_booking.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-suitcase"></i> สถานะการจอง</a></li>
                <li>
                    <a href="view_messages.php" class="text-white text-decoration-none d-block py-2">
                        <i class="fa-solid fa-comment"></i> ข้อความจากผู้ใช้งาน
                        <span id="notification-badge" class="badge bg-danger" style="display: none;"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-wrapper">
        <div class="container">
            <h2>เพิ่มข้อมูลลูกค้า</h2>
            <form action="add_user.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="first_name" class="form-label">ชื่อ</label>
                        <input class="form-control" type="text" id="first_name" name="first_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="last_name" class="form-label">นามสกุล</label>
                        <input class="form-control" type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input class="form-control" type="text" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="id_card" class="form-label">เลขบัตรประชาชน</label>
                        <input class="form-control" type="text" id="id_card" name="id_card" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="birthdate" class="form-label">วันเกิด</label>
                        <input class="form-control" type="date" id="birthdate" name="birthdate" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="userrole" class="form-label">สถานะ</label>
                        <select class="form-control" id="userrole" name="userrole" required>
                            <option value="">-- เลือกประเภท --</option>
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                </div>

                <br>
                <div class="button-group">
                    <button type="submit" class="submit-btn">บันทึก</button>
                    <button type="button" class="cancel-btn"
                        onclick="window.location.href='dashboard_user.php'">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function checkNotifications() {
        fetch('check_notifications.php')
            .then(response => response.json())
            .then(data => {
                console.log("Notification Data:", data);
                let notificationBadge = document.getElementById("notification-badge");
                if (data.unread_count > 0) {
                    notificationBadge.innerText = data.unread_count;
                    notificationBadge.style.display = "inline-block";
                } else {
                    notificationBadge.style.display = "none";
                }
            })
            .catch(error => console.error("Error fetching notifications:", error));
    }

    setInterval(checkNotifications, 1000);
    checkNotifications();
    </script>
</body>

</html>