<?php
session_start();
require 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

$error_message = ""; // สร้างตัวแปรสำหรับเก็บข้อความผิดพลาด

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบอีเมล
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['userrole'] = $user['userrole'];
                $_SESSION['email'] = $user['email']; // ✅ เพิ่มเซสชันนี้ให้แน่ใจว่าใช้งานได้
            
                // ตรวจสอบบทบาทและเปลี่ยนหน้า
                if ($user['userrole'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();            
            } else {
                $error_message = "❌ รหัสผ่านไม่ถูกต้อง!";
            }
        } else {
            $error_message = "⚠️ อีเมลนี้ไม่มีอยู่ในระบบ!";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
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
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: black;
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
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เข้าสู่ระบบ</h2>
        
        <!-- แสดงข้อความผิดพลาด ถ้ามี -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">อีเมล</label>
            <input type="email" id="email" name="email" placeholder="กรอกอีเมล" required>
            
            <label for="password">รหัสผ่าน</label>
            <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
            
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        
        <!-- ลิงก์ไปหน้า "สมัครสมาชิก" -->
        <a href="register.php">สมัครสมาชิก</a>
    </div>
</body>
</html>
