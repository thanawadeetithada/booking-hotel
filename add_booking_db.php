<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = $_POST['phone'] ?? '';  
    $email = $_POST['email'] ?? '';  
    $id_card = $_POST['id_card'] ?? '';  
    $birthdate = $_POST['birthdate'] ?? '0000-00-00';  
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $room_number = $_POST['room_number'];
    $payment_method = $_POST['payment_method'];

    // ค้นหา user_id จากชื่อและนามสกุล
    $user_query = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("ss", $first_name, $last_name);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // ถ้าไม่พบ user_id ให้สร้างใหม่
    if (!$user_id) {
        $password_hash = password_hash("defaultpassword", PASSWORD_DEFAULT);
        $insert_user_query = "INSERT INTO users (first_name, last_name, phone, email, id_card, birthdate, password, userrole) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($insert_user_query);
        $stmt->bind_param("sssssss", $first_name, $last_name, $phone, $email, $id_card, $birthdate, $password_hash);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
        } else {
            die("❌ เกิดข้อผิดพลาดในการสร้างบัญชีผู้ใช้: " . $stmt->error);
        }
        $stmt->close();
    }

    // ตรวจสอบว่า user_id มีค่าหรือไม่
    if (!$user_id) {
        die("❌ ไม่สามารถกำหนด user_id ได้");
    }

    // ค้นหา room_id จากหมายเลขห้อง
    $room_query = "SELECT room_id FROM rooms WHERE room_number = ?";
    $stmt = $conn->prepare($room_query);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $stmt->bind_result($room_id);
    $stmt->fetch();
    $stmt->close();

    if (!$room_id) {
        die("❌ ไม่พบข้อมูลห้องพักในระบบ");
    }

    // อัปโหลดไฟล์สลิปการโอนเงิน (ถ้ามีการแนบ และเป็น "โอนเงิน")
    $payment_slip = "";
    if ($payment_method === "โอนเงิน" && !empty($_FILES["imgpayment"]["name"])) {
        $target_dir = "uploads/";
        $payment_slip = $target_dir . basename($_FILES["imgpayment"]["name"]);
        move_uploaded_file($_FILES["imgpayment"]["tmp_name"], $payment_slip);
    }

    // กำหนดสถานะการชำระเงิน
    $status_payment = (!empty($payment_slip) && $payment_method === "โอนเงิน") ? 'paid' : 'pending';

   // รับค่าจากฟอร์ม (แก้ไขให้แน่ใจว่าไม่มีค่า NULL)
$room_count = isset($_POST['room_count']) ? intval($_POST['room_count']) : 1;
$guest_count = isset($_POST['guest_count']) ? intval($_POST['guest_count']) : 1;

// ตรวจสอบว่า room_count ไม่เป็น NULL ก่อน INSERT
if ($room_count <= 0) {
    die("❌ จำนวนห้องต้องมากกว่า 0");
}

// เพิ่มข้อมูลการจอง (แก้ไข bind_param)
$insert_query = "INSERT INTO bookings (user_id, room_id, room_count, guest_count, checkin_date, checkout_date, payment_method, payment_slip, status_payment) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iiiisssss", $user_id, $room_id, $room_count, $guest_count, $checkin_date, $checkout_date, $payment_method, $payment_slip, $status_payment);

    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id; // ดึง booking_id ที่เพิ่งเพิ่ม
    
        // ดึงข้อมูลราคาห้อง
        $room_query = "SELECT price, description, type FROM rooms WHERE room_id = ?";
        $stmt = $conn->prepare($room_query);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->bind_result($room_price, $room_description, $room_type);
        $stmt->fetch();
        $stmt->close();        
    
        // คำนวณยอดเงินทั้งหมด
        $checkin = new DateTime($checkin_date);
        $checkout = new DateTime($checkout_date);
        $interval = $checkin->diff($checkout);
        $nights = $interval->days; // จำนวนคืนที่เข้าพัก
        $total_amount = $room_price * $nights;
    
        // สร้างหมายเลขใบแจ้งหนี้
        $invoice_number = "INV-" . time();
        $order_number = "ORD-" . time();
        $order_date = date("Y-m-d");
        $due_date = date("Y-m-d", strtotime("+7 days")); // กำหนดวันครบกำหนด 7 วันหลังจากออกใบแจ้งหนี้
    
        if ($room_type === "เต็นท์") {
            $total_amount = $room_price * $room_count * $nights; // ถ้าเป็นเต็นท์ คิดเป็นราคาต่อจำนวนห้อง
        } else {
            $total_amount = $room_price * $nights * $room_count;  // ถ้าเป็นห้องพักทั่วไป คิดตามจำนวนคืน
        }

        // เพิ่มข้อมูลในตาราง invoices
        $insert_invoice_query = "INSERT INTO invoice (
            booking_id, invoice_number, first_name, last_name, checkin_date, checkout_date, 
            room_number, room_type, guest_count, room_count, price, description, 
            payment_method, status_payment, payment_slip, total_amount, paid_amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $paid_amount = ($status_payment === 'paid') ? $total_amount : 0; // ถ้าจ่ายแล้ว ให้ paid_amount = total_amount
        $invoice_status = ($status_payment === 'paid') ? 'paid' : 'pending';
    
        $stmt = $conn->prepare($insert_invoice_query);
$stmt->bind_param("isssssssiidssssdd", 
    $booking_id, $invoice_number, $first_name, $last_name, $checkin_date, $checkout_date, 
    $room_number, $room_type, $guest_count, $room_count, $room_price, $room_description, 
    $payment_method, $status_payment, $payment_slip, $total_amount, $paid_amount


        );
        
        if ($stmt->execute()) {
            echo "<script>alert('✅ บันทึกข้อมูลการจองสำเร็จ และออกใบแจ้งหนี้เรียบร้อย!'); window.location.href='dashboard_booking.php';</script>";
        } else {
            echo "❌ เกิดข้อผิดพลาดในการออกใบแจ้งหนี้: " . $stmt->error;
        }
    } else {
        echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
    }
    

    $stmt->close();
    $conn->close();
}
?>