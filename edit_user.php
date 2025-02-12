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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
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
    </script> -->
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
        max-width: 600px;
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
            <h2>แก้ไขข้อมูลลูกค้า</h2>
            <form action="update_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="first_name" class="form-label">ชื่อ</label>
                        <input class="form-control" type="text" id="first_name" name="first_name"
                            value="<?= $row['first_name'] ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="last_name" class="form-label">นามสกุล</label>
                        <input class="form-control" type="text" id="last_name" name="last_name"
                            value="<?= $row['last_name'] ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input class="form-control" type="text" id="phone" name="phone" value="<?= $row['phone'] ?>"
                            required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" id="email" name="email" value="<?= $row['email'] ?>"
                            required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="id_card" class="form-label">เลขบัตรประชาชน</label>
                        <input class="form-control" type="text" id="id_card" name="id_card"
                            value="<?= $row['id_card'] ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="birthdate" class="form-label">วันเกิด</label>
                        <input class="form-control" type="date" id="birthdate" name="birthdate"
                            value="<?= $row['birthdate'] ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="userrole" class="form-label">สถานะ</label>
                        <select class="form-control" id="userrole" name="userrole" required>
                            <option value="">-- เลือกประเภท --</option>
                            <option value="user" <?= ($row['userrole'] == 'user') ? 'selected' : ''; ?>>user</option>
                            <option value="admin" <?= ($row['userrole'] == 'admin') ? 'selected' : ''; ?>>admin</option>
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
</body>

</html>