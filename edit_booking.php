<?php
include 'db.php';

if (!isset($_GET['invoice_id']) || empty($_GET['invoice_id'])) {
    die("ไม่พบข้อมูลการจองที่ต้องการแก้ไข");
}

$invoice_id = intval($_GET['invoice_id']);

$sql = "SELECT * FROM invoice WHERE invoice_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("ไม่พบข้อมูลการจอง");
}

$booking = $result->fetch_assoc();
$slip_path = (!empty($booking['payment_slip']) && file_exists($booking['payment_slip'])) 
    ? $booking['payment_slip'] 
    : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $guest_count = $_POST['guest_count'];
    $room_count = $_POST['room_count'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $payment_method = $_POST['payment_method'];
    $status_payment = ($payment_method == "โอนเงิน") ? 'paid' : 'pending';

    $checkin = new DateTime($checkin_date);
    $checkout = new DateTime($checkout_date);
    $interval = $checkin->diff($checkout);
    $nights = $interval->days;

    if ($nights <= 0) {
        die("วันที่เช็คเอาท์ต้องมากกว่าวันที่เช็คอิน");
    }

    if ($room_type == "เต็นท์") {
        $total_amount = $price * $guest_count * $room_count * $nights;
    } else {
        $total_amount = $price * $room_count * $nights;
    }

    $paid_amount = ($status_payment == 'paid') ? $total_amount : 0;

$payment_slip = $booking['payment_slip'];
if ($payment_method == "โอนเงิน") {
    if (isset($_FILES["imgpayment"]["name"]) && $_FILES["imgpayment"]["size"] > 0) {
        $target_dir = "uploads/";
        $file_ext = pathinfo($_FILES["imgpayment"]["name"], PATHINFO_EXTENSION);
        $new_file_name = "INV-" . $invoice_id . "." . $file_ext;
        $new_file_path = $target_dir . $new_file_name;

        if (!empty($booking['payment_slip']) && file_exists($booking['payment_slip'])) {
            unlink($booking['payment_slip']);
        }

        if (move_uploaded_file($_FILES["imgpayment"]["tmp_name"], $new_file_path)) {
            $payment_slip = $new_file_path;
        } else {
            die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์สลิป");
        }
    }
} else {
    $payment_slip = "";
}

    $sql_update = "UPDATE invoice SET 
    first_name = ?, last_name = ?, checkin_date = ?, checkout_date = ?, room_number = ?, 
    room_type = ?, guest_count = ?, room_count = ?, price = ?, description = ?, 
    payment_method = ?, status_payment = ?, payment_slip = ?, total_amount = ?, paid_amount = ? 
    WHERE invoice_id = ?";

$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("ssssssiidssssddi",
    $first_name, $last_name, $checkin_date, $checkout_date, $room_number,
    $room_type, $guest_count, $room_count, $price, $description,
    $payment_method, $status_payment, $payment_slip, $total_amount, $paid_amount, $invoice_id
);

if ($stmt_update->execute()) {
    echo "<script>alert('อัปเดตข้อมูลการจองสำเร็จ!'); window.location.href='dashboard_booking.php';</script>";
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt_update->error;
}
    $stmt_update->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการจอง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Arial', sans-serif;
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

    .container {
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px;
        margin: 30px auto;
    }

    h2 {
        margin-bottom: 20px;
        color: black;
        text-align: center;
        margin-top: 20px;
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


    button {
        width: 48%;
        padding: 12px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .submit-btn {
        background: #8c99bc;
        color: white;
    }

    .cancel-btn {
        background: #ccc;
        color: black;
        margin-left: 10px;
    }

    .form-control {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    .form-label {
        margin-top: 10px;
        margin-bottom: 0;
    }

    .container-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 56px);
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
    <div class="container-wrapper">
        <div class="container">
            <h2>แก้ไขข้อมูลการจอง</h2>
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>ชื่อผู้จอง</label>
                        <input type="text" name="first_name" class="form-control" value="<?= $booking['first_name'] ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label>นามสกุลผู้จอง</label>
                        <input type="text" name="last_name" class="form-control" value="<?= $booking['last_name'] ?>"
                            required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>วันที่เช็คอิน</label>
                        <input type="date" name="checkin_date" class="form-control"
                            value="<?= $booking['checkin_date'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>วันที่เช็คเอาท์</label>
                        <input type="date" name="checkout_date" class="form-control"
                            value="<?= $booking['checkout_date'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>ประเภทห้อง</label>
                        <select class="form-control" id="room_type" name="room_type" required onchange="fetchRooms()">
                            <option value="">-- เลือกประเภทห้อง --</option>
                            <?php
    include 'db.php';
    $room_query = "SELECT DISTINCT type FROM rooms WHERE isshow = 1";
    $result = $conn->query($room_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($row['type'] == $booking['room_type']) ? "selected" : "";
            echo "<option value='" . $row['type'] . "' $selected>" . $row['type'] . "</option>";
        }
    }
    ?>
                        </select>


                    </div>
                    <div class="col-md-6">
                        <label>เลขห้อง</label>
                        <select class="form-control" id="room_number" name="room_number" required
                            onchange="fetchPrice(this.value)">
                            <?php
        include 'db.php';
        $room_query = "SELECT room_number FROM rooms WHERE type = ? AND isshow = 1";
        $stmt = $conn->prepare($room_query);
        $stmt->bind_param("s", $booking['room_type']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $selected = ($row['room_number'] == $booking['room_number']) ? "selected" : "";
            echo "<option value='" . $row['room_number'] . "' $selected>" . $row['room_number'] . "</option>";
        }
        ?>
                        </select>
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>ราคา</label>
                        <input type="number" name="price" class="form-control" value="<?= $booking['price'] ?>"
                            required>

                    </div>
                    <div class="col-md-6">
                        <label>รูปแบบการชำระเงิน</label>
                        <select name="payment_method" class="form-control" onchange="toggleSlipRequirement()" required>
                            <option value="โอนเงิน" <?= ($booking['payment_method'] == "โอนเงิน") ? "selected" : "" ?>>
                                โอนเงิน
                            </option>
                            <option value="เงินสด" <?= ($booking['payment_method'] == "เงินสด") ? "selected" : "" ?>>
                                เงินสด
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>จำนวนผู้เข้าพัก:</label>
                        <input type="number" name="guest_count" class="form-control"
                            value="<?= $booking['guest_count'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>จำนวนห้อง:</label>
                        <input type="number" name="room_count" class="form-control"
                            value="<?= $booking['room_count'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>รายละเอียด</label>
                        <input type="text" name="description" class="form-control"
                            value="<?= $booking['description'] ?>">
                    </div>
                </div>

                <div class="row mb-3" id="slip_container"
                    style="display: <?= ($booking['payment_method'] == "เงินสด") ? 'none' : 'block'; ?>">
                    <div class="col-md-12">
                        <label>สลิปการโอนเงิน</label><br>

                        <?php if (!empty($slip_path)) : ?>
                        <img id="preview-slip" src="<?php echo $slip_path; ?>" alt="Payment Slip" width="200px"
                            style="display:block; margin-bottom:10px;"
                            onerror="this.onerror=null; this.style.display='none';">
                        <?php else: ?>
                        <p style="color: red;">ไม่มีสลิปการโอนเงิน</p>
                        <?php endif; ?>

                        <input type="file" id="imgpayment" name="imgpayment" class="form-control">
                        <input type="hidden" name="existing_slip" value="<?php echo $booking['payment_slip']; ?>">
                    </div>
                </div>

                <br>
                <div class="button-group">
                    <button type="submit" class="submit-btn mt-3">บันทึกการเปลี่ยนแปลง</button>
                    <button type="button" class="cancel-btn" onclick="window.history.back();">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('imgpayment').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const preview = document.getElementById('preview-slip');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });

    function toggleSlipRequirement() {
        var paymentMethod = document.querySelector('select[name="payment_method"]').value;
        var slipContainer = document.getElementById("slip_container");

        if (paymentMethod === "โอนเงิน") {
            slipContainer.style.display = "block";
        } else {
            slipContainer.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleSlipRequirement();
    });

    document.querySelector('select[name="payment_method"]').addEventListener("change", toggleSlipRequirement);

    document.addEventListener("DOMContentLoaded", function() {
        fetchRooms();
    });

    document.getElementById("room_number").addEventListener("change", function() {
        fetchPrice(this.value);
    });

    function fetchRooms() {
        var roomType = document.getElementById("room_type").value;
        var roomNumberSelect = document.getElementById("room_number");

        if (roomType) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetchRooms.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    roomNumberSelect.innerHTML = xhr.responseText;
                    fetchPrice(roomNumberSelect.value);
                }
            };
            xhr.send("room_type=" + roomType);
        }
    }

    function fetchPrice(roomNumber) {
        var priceInput = document.querySelector('input[name="price"]');

        if (roomNumber) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetchPrice.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    priceInput.value = xhr.responseText;
                }
            };
            xhr.send("room_number=" + roomNumber);
        }
    }
    </script>

</body>

</html>