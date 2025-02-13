<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['room_code'])) {
    $room_code = $_GET['room_code'];

    $sql = "SELECT * FROM rooms WHERE room_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $room_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if (!$room) {
        echo "<script>alert('ไม่พบข้อมูลห้องพัก'); window.location.href = 'dashboard_room.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีรหัสห้องพักที่ต้องการแก้ไข'); window.location.href = 'dashboard_room.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลห้องพัก</title>
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

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
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
        border-radius: 24px;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    input[readonly] {
        background-color: rgba(200, 200, 200, 0.5);
        color: black;
        border: 1px solid #ccc;
        cursor: not-allowed;
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
            <h2>แก้ไขข้อมูลห้องพัก</h2>
            <form action="update_room.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="room_code" class="form-label">รหัสห้อง</label>
                        <input class="form-control" type="text" id="room_code" name="room_code"
                            value="<?php echo $room['room_code']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="room_number" class="form-label">เลขห้อง</label>
                        <input class="form-control" type="text" id="room_number" name="room_number"
                            value="<?php echo $room['room_number']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">ประเภท</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">-- เลือกประเภท --</option>
                            <option value="ห้องพัก" <?php echo ($room['type'] == 'ห้องพัก') ? 'selected' : ''; ?>>
                                ห้องพัก</option>
                            <option value="เต็นท์" <?php echo ($room['type'] == 'เต็นท์') ? 'selected' : ''; ?>>เต็นท์
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">ราคา</label>
                        <input class="form-control" type="number" id="price" name="price"
                            value="<?php echo $room['price']; ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">รายละเอียด</label>
                        <input class="form-control" type="text" id="description" name="description"
                            value="<?php echo $room['description']; ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="image" class="form-label">รูปภาพ</label><br>
                        <?php if (!empty($room['image_path'])): ?>
                        <img id="preview-image" src="<?php echo $room['image_path']; ?>" alt="Room Image" width="200px"
                            style="display:block; margin-bottom:10px;">
                        <?php else: ?>
                        <img id="preview-image" src="" alt="Preview Image" width="200px"
                            style="display:none; margin-bottom:10px;">
                        <?php endif; ?>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        <input type="hidden" name="existing_image" value="<?php echo $room['image_path']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="isshow" class="form-label">สถานะการทำงาน</label>
                        <br>
                        <label class="toggle-switch">
                            <input type="checkbox" id="isshow" name="isshow" value="1"
                                <?php echo ($room['isshow'] == 1) ? 'checked' : ''; ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <br>
                <div class="button-group">
                    <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                    <button type="button" class="cancel-btn"
                        onclick="window.location.href='dashboard_room.php'">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const preview = document.getElementById('preview-image');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
    </script>
</body>

</html>