<?php
include 'db.php';

$sql = "SELECT 
            b.invoice_id,
            b.invoice_number,
            b.first_name,
            b.last_name,
            b.checkin_date,
            b.checkout_date,
            b.room_number,
            b.room_type,
            b.guest_count,
            b.room_count,
            b.price,
            b.description,
            b.payment_method,
            b.status_payment,
            b.payment_slip,
            b.total_amount,
            b.paid_amount
        FROM invoice b
        ORDER BY b.invoice_id DESC";
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
                        <th>จำนวนผู้เข้าพัก</th>
                        <th>จำนวนห้องพัก</th>
                        <th>ราคา</th>
                        <th>ราคาทั้งหมด</th>
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
            $slip_path = "{$row['payment_slip']}";

            echo "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['checkin_date']}</td>
                <td>{$row['checkout_date']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['room_type']}</td>
                <td>{$row['guest_count']}</td>
                <td>{$row['room_count']}</td>
                <td>{$row['price']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['description']}</td>
                <td>{$row['payment_method']}</td>
                <td>";

                if ($row['status_payment'] == 'paid') {
                    echo "<span class='badge bg-success'>ชำระแล้ว</span>";
                } elseif ($row['status_payment'] == 'pending') {
                    echo "<span class='badge bg-warning text-dark'>รอชำระ</span>";
                } else {
                    echo "<span class='badge bg-secondary'>ยกเลิก</span>";
                }

                echo "</td>";

           
                echo "<td>";
                if (!empty($row['payment_slip'])) {
                    $slip_path = "uploads/" . basename($row['payment_slip']);
                    echo "<img src='$slip_path' alt='สลิปการชำระเงิน' width='100' height='100' style='border-radius: 5px;'>";
                } else {
                    echo "ไม่มีสลิป";
                }
                    echo "</td>";

                    echo "<td class='btn-action1'>
                        <a href='edit_booking.php?invoice_id={$row['invoice_id']}' class='btn btn-warning btn-sm'>
                            <i class='fa-solid fa-pencil'></i>
                        </a>
                        &nbsp;&nbsp;
                        <a href='receipt_booking.php?invoice_id={$row['invoice_id']}' class='btn btn-primary btn-sm'>
                            <i class='fa-solid fa-file-invoice-dollar'></i>
                        </a>
                        &nbsp;&nbsp;
                        <a href='#' class='btn btn-danger btn-sm delete-btn' data-id='{$row['invoice_id']}'>
                            <i class='fa-regular fa-trash-can'></i>
                        </a>
                    </td>
                    </tr>";
                    }
                    } else {
                    echo "<tr>
                        <td colspan='13' class='text-center'>ไม่มีข้อมูลการจอง</td>
                    </tr>";
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