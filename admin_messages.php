<?php
session_start();
include "db.php";

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    die("ไม่พบ user_id");
}

$user_id = intval($_GET['user_id']);

if ($user_id == 0) {
    die("user_id ไม่ถูกต้อง");
}

$user = $conn->query("SELECT first_name, last_name, userrole FROM users WHERE user_id = $user_id")->fetch_assoc();
if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

if ($user['userrole'] !== 'user') {
    die("ไม่สามารถเข้าถึงแชทของผู้ใช้ admin ได้");
}

$messages = $conn->query("
    SELECT * FROM messages 
    WHERE user_id = $user_id 
    ORDER BY created_at ASC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        font-family: 'Prompt', sans-serif;
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

    .chat-container {
        max-width: 600px;
        margin: auto;
        height: 500px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ccc;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
    }

    .message-wrapper {
        display: flex;
        width: 100%;
        margin-bottom: 10px;
    }

    .message {
        max-width: 75%;
        padding: 10px;
        border-radius: 15px;
        word-wrap: break-word;
    }

    .message.user {
        background-color: #0d6efd;
        color: white;
        justify-content: flex-start;
    }

    .message.admin {
        background-color: white;
        color: black;
        justify-content: flex-end;
        border: 1px solid #ccc;
    }

    .user-message {
        display: flex;
        justify-content: flex-start;
    }

    .admin-message {
        display: flex;
        justify-content: flex-end;
    }

    .input-container {
        max-width: 600px;
        margin: auto;
        display: flex;
        width: 100%;
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
                <li><a href="view_messages.php" class="text-white text-decoration-none d-block py-2"><i
                            class="fa-solid fa-comment"></i> ข้อความจากผู้ใช้งาน</a></li>
            </ul>
        </div>
    </div>

    <div class="container mt-4">
        <div class="chat-container" id="chatBox">
            <?php 
            if ($messages->num_rows > 0) {
                while ($row = $messages->fetch_assoc()) { ?>
            <div class="message <?= ($row['sender'] === 'admin') ? 'admin-message' : 'user-message' ?>">
                <p><?= htmlspecialchars($row['message']) ?></p>
            </div>
            <?php 
                }
            } else {
                echo "<p class='text-center'>ไม่มีข้อความ</p>";
            }
            ?>
        </div>

        <div class="input-container d-flex mt-3">
            <input type="text" class="form-control me-2" id="chatInput" placeholder="พิมพ์ข้อความ...">
            <button class="btn btn-primary" onclick="sendMessage()">ส่ง</button>
        </div>

    </div>

    <script>
    function sendMessage() {
        let message = $("#chatInput").val();
        if (message.trim() === "") return;

        $.post("send_message.php", {
            sender: "admin",
            user_id: <?= $user_id ?>,
            message: message
        }, function() {
            $("#chatInput").val("");
            loadMessages();
        });
    }

    function loadMessages() {
        $.get("load_messages.php", {
            user_id: <?= $user_id ?>
        }, function(data) {
            $("#chatBox").html(data);
            $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
        });
    }

    setInterval(loadMessages, 2000);
    </script>
</body>

</html>