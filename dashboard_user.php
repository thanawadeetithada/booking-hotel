<?php
session_start();
require 'db.php';
// ----login-----

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT user_id, first_name, last_name, phone, email, id_card, birthdate, userrole FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายชื่อลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Prompt', sans-serif;
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
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-control modal-text {
        height: fit-content;
        width: 50%;
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
        gap: 15px;
        align-items: center;
    }

    .tab-func {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    @media (max-width: 768px) {
        .search-add {
            flex-direction: row;
            gap: 10px;
        }

        .search-name {
            width: 20%;
            flex: 1;
        }

        .tab-func button {
            width: max-content;
        }
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
                            class="fa-solid fa-chart-line"></i> หน้าหลัก</a></li>
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

    <div class="card">
        <div class="header-card">
            <h3 class="text-left">รายชื่อลูกค้า</h3>
            <div class="search-add">
                <div class="tab-func">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='add_user.php'">
                        <i class="fa-solid fa-file-medical"></i> เพิ่มรายชื่อลูกค้า
                    </button>
                </div>
                <div class="tab-func">
                    <input type="text" class="form-control search-name" placeholder="ค้นหาด้วยชื่อ">
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>เบอร์โทร</th>
                        <th>Email</th>
                        <th>เลขบัตรประชาชน</th>
                        <th>วัน / เดือน / ปี เกิด</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['id_card']}</td>
                                <td>{$row['birthdate']}</td>
                                <td>{$row['userrole']}</td>
                                <td class='btn-action1'>
                                    <a href='edit_user.php?user_id={$row['user_id']}' class='btn btn-warning btn-sm'>
                                        <i class='fa-solid fa-pencil'></i>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href='#' class='btn btn-danger btn-sm delete-btn' data-id='{$row['user_id']}'>
                                        <i class='fa-regular fa-trash-can'></i>
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>ไม่มีข้อมูล</td></tr>";
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

            if (confirm("คุณต้องการลบใช่หรือไม่?")) {
                $.post("delete_user.php", {
                    id: id
                }, function(response) {
                    if (response.trim() === "success") {
                        alert("ลบข้อมูลเรียบร้อยแล้ว!");
                        row.fadeOut(500, function() {
                            $(this).remove();
                        });
                    } else {
                        alert("เกิดข้อผิดพลาดในการลบข้อมูล: " + response);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์: " + textStatus);
                });
            }
        });
    });

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