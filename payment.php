<?php
session_start();
include 'db.php';

if (!isset($_GET['booking_id'])) {
    die("ไม่มีรหัสการจอง");
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : "ไม่ทราบอีเมล";
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : "";

$booking_id = intval($_GET['booking_id']);

$sql = "SELECT * FROM booking WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("ไม่พบข้อมูลการจอง");
}

$booking = $result->fetch_assoc();
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
                <form id="customerForm" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ *</label>
                        <input type="text" class="form-control" name="first_name"
                            value="<?= htmlspecialchars($booking['first_name']) ?>" disabled required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล *</label>
                        <input type="text" class="form-control" name="last_name"
                            value="<?= htmlspecialchars($booking['last_name']) ?>" disabled required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">โทรศัพท์ *</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>"
                            disabled required>
                    </div>

                </form>
                <br>
                <div class="summary-box">

                    <img src="img/QR_code.jpg" alt="Product Image">
                    <hr>
                    <div>
                        <form id="paymentForm" method="POST" enctype="multipart/form-data">
                            <label for="payment_proof">หากชำระเรียบร้อยแล้ว โปรดแนบหลักฐานการชำระเงิน</label>
                            <input type="file" name="payment_proof" id="payment_proof" required>
                            <input type="hidden" name="payment_slip"
                                value="<?= htmlspecialchars($booking['payment_slip']) ?>">
                        </form>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <h5>สรุปรายการสั่งซื้อ</h5>
                <div class="summary-box">
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <p class="mb-0"><strong><?= htmlspecialchars($booking['room_type']) ?></strong></p>
                            <p class="mb-0">จำนวนห้อง <?= htmlspecialchars($booking['room_count']) ?> x จำนวนคนเข้าพัก
                                <?= htmlspecialchars($booking['guest_count']) ?></p>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p><strong>ยอดรวม:</strong> ฿<?= htmlspecialchars($booking['total_amount']) ?></p>
                        <p><strong>ภาษี:</strong> ฿0.00</p>
                        <p><strong>รวมทั้งหมด:</strong> ฿<?= htmlspecialchars($booking['total_amount']) ?></p>
                    </div>
                    <hr>
                    <p><strong>ชำระเงินตอนนี้:</strong> ฿<?= htmlspecialchars($booking['total_amount']) ?></p>
                    <p><strong>ชำระเงินภายหลัง:</strong> ฿0.00</p>
                    <button id="paymentBtn" class="btn btn-black mt-3">ชำระเงิน</button>
                    <div class="locked-payment">
                        🔒 ชำระเงิน
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("payment_proof");
        const paymentBtn = document.getElementById("paymentBtn");
        const paymentForm = document.getElementById("paymentForm");

        // paymentBtn.disabled = true;

        fileInput.addEventListener("change", function() {
            paymentBtn.disabled = fileInput.files.length === 0;
        });

        paymentBtn.addEventListener("click", function() {
            const formData = new FormData(paymentForm);
            formData.append("booking_id", <?= $booking_id ?>);

            fetch("process_payment.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json()) // แปลงเป็น JSON
                .then(data => {
                    if (data.status === "success") {
                        alert("ชำระเงินสำเร็จ!");
                        window.location.href = `receipt_booking.php?invoice_id=${data.invoice_id}`; // ใช้ค่า invoice_number ที่คืนมาจาก PHP
                    } else {
                        alert("กรุณาแนบสลิป");
                        // alert("เกิดข้อผิดพลาด: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("เกิดข้อผิดพลาด: " + error);
                });
        });
    });
    </script>

</body>

</html>