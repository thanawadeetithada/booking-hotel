<?php
session_start();
include 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// ดึงค่าอีเมลของผู้ใช้ที่ล็อกอิน
$email = $_SESSION['email'] ?? ''; // ✅ ใช้ ?? เพื่อป้องกัน error ถ้าเซสชันไม่ถูกตั้งค่า

// ตรวจสอบว่ามีค่า email จริงหรือไม่
if (empty($email)) {
    die("⚠️ ไม่พบข้อมูลอีเมล กรุณาเข้าสู่ระบบใหม่");
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT first_name, last_name, phone FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $phone = $user['phone'];
} else {
    // กำหนดค่าเริ่มต้นหากไม่มีข้อมูล
    $first_name = "";
    $last_name = "";
    $phone = "";
}

$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงินอ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .checkout-container {
        max-width: 900px;
        margin: 30px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .btn-black {
        background-color: black;
        color: white;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        border: none;
    }

    .btn-black:hover {
        background-color: #333;
    }

    .summary-box {
        background: #f1f1f1;
        padding: 15px;
        border-radius: 10px;
    }

    .summary-box img {
        width: 390px;
        height: autp;
        border-radius: 5px;
    }

    .locked-payment {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border-top: 1px solid #ddd;
        font-size: 14px;
        color: #666;
    }
    </style>
</head>

<body>

    <div class="container checkout-container">
        <h3 class="text-center">ชำระเงิน</h3>

        <div class="alert alert-light">
            <strong>ล็อกอินด้วย</strong> <?php echo htmlspecialchars($email); ?>
            <a href="logout.php" class="text-decoration-none">ออกจากระบบ</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5>รายละเอียดลูกค้า</h5>
                <form action="payment_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ *</label>
                        <input type="text" class="form-control" name="first_name"
                            value="<?php echo htmlspecialchars($first_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล *</label>
                        <input type="text" class="form-control" name="last_name"
                            value="<?php echo htmlspecialchars($last_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">โทรศัพท์ *</label>
                        <input type="text" class="form-control" name="phone"
                            value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-black">บันทึกและดำเนินการต่อ</button>
                </form>
                <br>
                <div class="summary-box">

                    <img src="img/QR_code.jpg" alt="Product Image">


                    <hr>
                    <div>
                        <form action="update_payment.php" method="POST" enctype="multipart/form-data">
                            <label for="payment_proof">หากชำระเรียบร้อยแล้ว โปรดแนบหลักฐานการชำระเงิน</label>
                            <input type="file" name="payment_proof" id="payment_proof" required>
                            <input type="hidden" name="cart_order_id"
                                value="<?php echo htmlspecialchars($cart_order_id); ?>">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <h5>สรุปรายการสั่งซื้อ</h5>
                <div class="summary-box">
                    <div class="d-flex align-items-center">
                        <!-- <img src="https://via.placeholder.com/60" alt="Product Image"> -->
                        <div class="ms-3">
                            <p class="mb-0">ลานกางเต็นท์คามมายตารีสอร์ท</p>
                            <p class="text-muted small">มัดจำ: ฿1200.00</p>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p><strong>ยอดรวม:</strong> ฿1200.00</p>
                        <p><strong>ภาษี:</strong> ฿0.00</p>
                        <p><strong>รวมทั้งหมด:</strong> ฿800.00</p>
                    </div>
                    <hr>
                    <p><strong>ชำระเงินตอนนี้:</strong> ฿1200.00</p>
                    <p><strong>ชำระเงินภายหลัง:</strong> ฿0.00</p>
                    <button class="btn btn-black mt-3">ชำระเงินแล้ว</button>
                    <div class="locked-payment">
                        🔒 ชำระเงินแล้ว
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>