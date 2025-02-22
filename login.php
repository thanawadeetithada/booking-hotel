<?php
session_start();
ob_start();
require 'db.php';

$error_message = "";
if (!empty($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['HTTP_HOST']) {
    $referer_page = basename(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH)); // ดึงเฉพาะชื่อไฟล์
    if ($referer_page !== "login.php") {
        $_SESSION['redirect_to'] = $_SERVER['HTTP_REFERER'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['userrole'] = $user['userrole'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];

                if ($user['userrole'] == 'admin') {
                    $redirect_page = "admin_dashboard.php";
                } else {
                    if (isset($_SESSION['redirect_to']) && basename($_SESSION['redirect_to']) === "register.php") {
                        $redirect_page = "index.php";
                    } else {
                        $redirect_page = $_SESSION['redirect_to'] ?? 'index.php';
                    }
                    unset($_SESSION['redirect_to']);
                }

                header("Location: " . $redirect_page);
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
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        font-family: 'Prompt', sans-serif;
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
        max-width: 500px;
        text-align: center;
        margin: 30px;
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
        width: 100%;
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

    .forgot-password {
        display: flex;
        justify-content: center;
    }

    .forgot-password button {
        width: 30%;
        margin: 5px;
    }

    .form-group {
        display: flex;
        justify-content: center;
    }

    .form-control {
        width: 80%;
    }

    form a {
        color:black;
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>เข้าสู่ระบบ</h2>
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
            <a href="#" id="forgotPasswordLink" data-toggle="modal"
            data-target="#forgotPasswordModal">ลืมรหัสผ่าน?</a>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        
        <a href="register.php">สมัครสมาชิก</a>
    </div>
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title mx-auto">ลืมรหัสผ่าน</h5>
                </div>
                <div class="modal-body px-4 ">
                    <form id="forgotPasswordForm" method="POST" action="process_forgot_password.php">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control rounded-pill"
                                placeholder="กรุณาใส่อีเมล" required>
                        </div>
                        <div class="forgot-password">
                            <button type="submit" class="btn btn-primary rounded-pill">ตกลง</button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill"
                                data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>