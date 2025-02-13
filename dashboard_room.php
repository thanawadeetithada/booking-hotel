<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT room_id, room_code, room_number, price, type, description, image_path, isshow FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลห้องพัก</title>
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

    .card {
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        background: white;
        margin-top: 50px;
        margin: 3% 5%;
        background-color: #ffffff;
    }

    .table th,
    .table td {
        text-align: center;
        font-size: 14px;

    }

    .table {
        background: #f8f9fa;
        border-radius: 10px;
    }

    .table th {
        background-color: #f9fafc;
        color: black;
    }

    .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;

    }

    .modal-content {
        width: 100%;
        max-width: 500px;
    }

    .header-card {
        display: flex;
        justify-content: space-between;
    }

    .form-control modal-text {
        height: fit-content;
        width: 50%;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 17%;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 15%;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 15%;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 13%;
    }

    .table td:nth-child(9) {
        text-align: center;
        vertical-align: middle;
    }

    .btn-action {
        display: flex;
        justify-content: center;
        align-items: center;
    }


    .modal-text {
        width: 100%;
    }

    .modal-header {
        font-weight: bold;
        padding: 25px;
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

    .modal-body {
        padding: 10px 40px;
    }

    .search-add {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }

    .tab-func {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
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

    <div class="card">
        <div class="header-card">
            <h3 class="text-left">ข้อมูลห้องพัก</h3>
            <div class="search-add">
                <div class="tab-func">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='add_room.php'">
                        <i class="fa-solid fa-file-medical"></i> เพิ่มห้องพัก
                    </button>
                </div>
                <div class="tab-func">
                    <input type="text" class="form-control search-name" placeholder="ค้นหาด้วยรหัสห้อง">
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>รหัสห้อง</th>
                        <th>เลขห้อง</th>
                        <th>ราคา</th>
                        <th>ประเภท</th>
                        <th>รายละเอียด</th>
                        <th>รูปภาพ</th>
                        <th>สถานะห้องพัก</th>
                        <th>จัดการ</th>
                    </tr>
                <tbody>
                    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $checked = $row['isshow'] == 1 ? "checked" : "";
            echo "<tr>
                <td>{$row['room_code']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['price']} บาท/คืน</td>
                <td>{$row['type']}</td>
                <td>{$row['description']}</td>
                <td><img src='{$row['image_path']}' width='100'></td>
                <td>
                    <label class='switch'>
                        <input type='checkbox' class='toggle-status' data-id='{$row['room_id']}' $checked>
                        <span class='slider round'></span>
                    </label>
                </td>
                <td class='btn-action1'>
                    <a href='edit_room.php?room_code={$row['room_code']}' class='btn btn-warning btn-sm'>
                        <i class='fa-solid fa-pencil'></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href='#' class='btn btn-danger btn-sm delete-btn' data-id='{$row['room_id']}'>
                        <i class='fa-regular fa-trash-can'></i>
                    </a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>ไม่มีข้อมูล</td></tr>";
    }
    $conn->close();
    ?>
                </tbody>

            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".search-name").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(".delete-btn").on("click", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var row = $(this).closest("tr");

            if (confirm("คุณต้องการลบห้องนี้ใช่หรือไม่?")) {
                $.post("delete_room.php", {
                    id: id
                }, function(response) {
                    if (response === "success") {
                        alert("ลบข้อมูลเรียบร้อยแล้ว!");
                        row.fadeOut(500, function() {
                            $(this).remove();
                        });
                    } else {
                        alert("เกิดข้อผิดพลาดในการลบข้อมูล");
                    }
                }).fail(function() {
                    alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
                });
            }
        });
    });


    $(document).ready(function() {
        $(".toggle-status").on("change", function() {
            var room_id = $(this).data("id");
            var isshow = $(this).is(":checked") ? 1 : 0;

            $.post("update_isshow.php", {
                room_id: room_id,
                isshow: isshow
            }, function(response) {
                if (response == "success") {
                    console.log("สถานะห้องพักอัปเดตสำเร็จ!");
                } else {
                    alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล");
                }
            }).fail(function() {
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
            });
        });
    });
    </script>
</body>

</html>