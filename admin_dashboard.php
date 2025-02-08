<?php
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผงควบคุมผู้ดูแลระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
          body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('bg/sky.png') no-repeat center center/cover;
            margin: 0;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
        }
        .card {
            transition: 0.3s;
            background-color: #96a1cd;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">ผู้ดูแลระบบ</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <a href="add_room.php" class="text-decoration-none">
                    <div class="card text-center p-4 text-white">
                        <h4>ข้อมูลกำหนดราคาห้องพัก</h4>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="room_dashboard.php" class="text-decoration-none">
                    <div class="card text-center p-4  text-white">
                        <h4>ข้อมูลห้องพัก</h4>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="user_management.php" class="text-decoration-none">
                    <div class="card text-center p-4 text-white">
                        <h4>ข้อมูลลูกค้า</h4>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="booking_room.php" class="text-decoration-none">
                    <div class="card text-center p-4 text-white">
                        <h4>ข้อมูลการจองห้องพัก</h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
