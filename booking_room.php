<?php
include 'db.php';

$sql = "SELECT 
            b.booking_id,
            u.first_name,
            u.last_name,
            b.checkin_date,
            b.checkout_date,
            r.room_number,
            r.type,
            r.price,
            r.description,
            b.payment_method,
            b.payment_slip,
            b.status_payment
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        ORDER BY b.booking_id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจองห้องพัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    /* ปรับแต่ง UI */
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
/* 
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
    } */

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
    </style>
</head>

<body>
    <div class="navbar navbar-dark bg-dark justify-content-end">
        <div class="nav-item d-flex">
            <a class="nav-link" href="logout.php"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;Logout</a>
        </div>
    </div>

    <div class="card">
        <div class="header-card">
            <h3 class="text-left">ข้อมูลการจอง</h3>
            <div class="search-add">
                <div class="tab-func">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='add_booking.php'">
                        <i class="fa-solid fa-file-medical"></i> เพิ่มการจองห้องพัก
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
                        <th>ชื่อผู้จองห้องพัก</th>
                        <th>วันที่เช็คอิน</th>
                        <th>วันที่เช็คเอ้า</th>
                        <th>เลขห้อง</th>
                        <th>ประเภท</th>
                        <th>ราคา</th>
                        <th>รายละเอียด</th>
                        <th>รูปแบบการชำระเงิน</th>
                        <th>สถานะการจ่ายเงิน</th>
                        <th>รูปสลิป</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['checkin_date']}</td>
                <td>{$row['checkout_date']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['type']}</td>
                <td>{$row['price']}</td>
                <td>{$row['description']}</td>
                <td>{$row['payment_method']}</td>
                <td>{$row['status_payment']}</td>";

            // แสดงรูปสลิปการชำระเงิน
            echo "<td>";
            if (!empty($row['payment_slip'])) {
                echo "<a href='{$row['payment_slip']}' target='_blank'>
                        <img src='{$row['payment_slip']}' alt='Slip' width='80px'>
                      </a>";
            } else {
                echo "ไม่มีสลิป";
            }
            echo "</td>";

            // ปุ่มแก้ไขและลบ
            echo "<td class='btn-action1'>
                    <a href='edit_booking.php?booking_id={$row['booking_id']}' class='btn btn-warning btn-sm'>
                        <i class='fa-solid fa-pencil'></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href='#' class='btn btn-danger btn-sm delete-btn' data-id='{$row['booking_id']}'>
                        <i class='fa-regular fa-trash-can'></i>
                    </a>
                  </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='11' class='text-center'>ไม่มีข้อมูลการจอง</td></tr>";
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
        $(".delete-btn").on("click", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var row = $(this).closest("tr");

            if (confirm("คุณต้องการลบใช่หรือไม่?")) {
                $.post("delete_booking.php", {
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
    </script>
</body>

</html>