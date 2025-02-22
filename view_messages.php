<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT m.id, m.user_id, u.first_name, u.last_name, u.email, m.created_at, m.notification
        FROM messages m
        LEFT JOIN users u ON m.user_id = u.user_id
        GROUP BY m.user_id
        ORDER BY m.created_at DESC";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's get in touch</title>
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

    .table-danger {
        background-color: #f8d7da !important;
        /* สีแดงอ่อน */
        color: #721c24 !important;
        /* สีตัวอักษร */
    }

    @keyframes blink {
        0% {
            background-color: #f8d7da;
        }

        50% {
            background-color: #ffffff;
        }

        100% {
            background-color: #f8d7da;
        }
    }

    .blinking-row td {
        animation: blink 1s infinite;
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
            <h3 class="text-left">ข้อความ</h3>
            <div class="search-add">
                <div class="tab-func">
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อ</th>
                        <th>Email</th>
                        <th>วันที่ เวลา</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row_<?php echo $row['user_id']; ?>" class="blinking-row">
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="admin_messages.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-primary">
                                <i class="fa-solid fa-comment"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a href="#" class="btn btn-danger delete-btn" data-userid="<?php echo $row['user_id']; ?>">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr class="no-messages">
                        <td colspan="4" class="text-center">ไม่มีข้อความ</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on("click", ".delete-btn", function(e) {
            e.preventDefault();
            var user_id = $(this).data("userid");
            var row = $("#row_" + user_id);

            if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบข้อความของผู้ใช้นี้?")) {
                $.ajax({
                    url: "delete_messages.php",
                    type: "POST",
                    data: {
                        user_id: user_id
                    },
                    success: function(response) {
                        if (response.trim() === "success") {
                            alert("ลบข้อความของผู้ใช้เรียบร้อย!");
                            row.fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด: " + response);
                        }
                    },
                    error: function() {
                        alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
                    }
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

    function checkNewMessages() {
        fetch('check_blink.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(row => {
                    let rowElement = document.getElementById("row_" + row.user_id);
                    if (rowElement) {
                        if (row.notification == 1) {
                            if (!rowElement.classList.contains("blinking-row")) {
                                rowElement.classList.add("blinking-row");
                            }
                        } else {
                            rowElement.classList.remove("blinking-row");
                        }
                    }
                });
            })
            .catch(error => console.error("Error checking messages:", error));
    }

    setInterval(checkNewMessages, 3000);
    checkNewMessages();

    function loadMessages() {
        $.ajax({
            url: "fetch_messages.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                let tbody = $("table tbody");
                tbody.find(".no-messages").remove();

                if (response.length > 0) {
                    response.forEach(row => {
                        let rowId = `row_${row.user_id}`;
                        let existingRow = $("#" + rowId);

                        let blinkingClass = row.notification == 1 ? "blinking-row" : "";

                        let newRow = `
                            <tr id="${rowId}" class="${blinkingClass}">
                                <td>${row.first_name} ${row.last_name}</td>
                                <td>${row.email}</td>
                                <td>${row.created_at}</td>
                                <td>
                                    <a href='admin_messages.php?user_id=${row.user_id}' class='btn btn-primary'>
                                        <i class='fa-solid fa-comment'></i>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href='#' class='btn btn-danger delete-btn' data-userid='${row.user_id}'>
                                        <i class='fa-regular fa-trash-can'></i>
                                    </a>
                                </td>
                            </tr>
                        `;

                        if (existingRow.length) {
                            if (existingRow.html() !== $(newRow).html()) {
                                existingRow.replaceWith(newRow);
                            }
                        } else {
                            tbody.append(newRow);
                        }
                    });
                }

                if (tbody.find("tr").length === 0) {
                    tbody.append(
                        "<tr class='no-messages'><td colspan='4' class='text-center'>ไม่มีข้อความ</td></tr>"
                    );
                }
            },
            error: function() {
                console.error("Error loading messages.");
            }
        });
    }
    setInterval(loadMessages, 3000);
    loadMessages();
    </script>

</body>

</html>