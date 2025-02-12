<?php
require 'db.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $id_card = $_POST['id_card'];
    $birthdate = $_POST['birthdate'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userrole = 'user';

    $check_duplicate_sql = "SELECT email, phone, id_card FROM users WHERE email = ? OR phone = ? OR id_card = ?";
    
    if ($stmt = $conn->prepare($check_duplicate_sql)) {
        $stmt->bind_param("sss", $email, $phone, $id_card);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existing_email, $existing_phone, $existing_id_card);
            $stmt->fetch();

            if ($email === $existing_email) {
                $error_message = "อีเมลนี้ถูกใช้ไปแล้ว กรุณาใช้อีเมลอื่น";
            } elseif ($phone === $existing_phone) {
                $error_message = "เบอร์โทรศัพท์นี้ถูกใช้ไปแล้ว กรุณาใช้เบอร์โทรศัพท์อื่น";
            } elseif ($id_card === $existing_id_card) {
                $error_message = "เลขบัตรประชาชนนี้ถูกใช้ไปแล้ว กรุณาใช้เลขบัตรประชาชนอื่น";
            }
        }
        $stmt->close();
    }

    if (empty($error_message)) {
        $sql = "INSERT INTO users (first_name, last_name, phone, email, id_card, birthdate, password, userrole, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssss", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $password, $userrole);
            
            if ($stmt->execute()) {
                echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $error_message = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL";
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
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
        max-width: 400px;
    }

    h2 {
        margin-bottom: 20px;
        color: black;
        text-align: center;
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

    input {
        width: calc(100% - 20px);
        padding: 12px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #8c99bc;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 15px;
    }

    a {
        text-align: center;
    }

    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        font-size: 14px;
        text-align: center;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>สมัครสมาชิก</h2>

        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="first_name">ชื่อ</label>
            <input type="text" id="first_name" name="first_name" placeholder="ชื่อ" required
                value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">

            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" placeholder="นามสกุล" required
                value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">

            <label for="phone">เบอร์โทรศัพท์</label>
            <input type="text" id="phone" name="phone" placeholder="เบอร์โทรศัพท์" required
                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

            <label for="id_card">เลขบัตรประชาชน</label>
            <input type="text" id="id_card" name="id_card" placeholder="เลขบัตรประชาชน" required
                value="<?php echo isset($_POST['id_card']) ? htmlspecialchars($_POST['id_card']) : ''; ?>">

            <label for="birthdate">วันเกิด</label>
            <input type="date" id="birthdate" name="birthdate" required
                value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>">

            <label for="password">รหัสผ่าน</label>
            <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required>

            <button type="submit">สมัครสมาชิก</button>
            <br>
            <a href="login.php">เข้าสู่ระบบ</a>
        </form>

    </div>
</body>

</html>