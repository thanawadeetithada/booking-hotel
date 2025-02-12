<?php
include 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $invoice_id = intval($_POST['id']);
    
    // ตรวจสอบว่ามีการจองที่ต้องการลบหรือไม่
    $check_sql = "SELECT payment_slip FROM invoice WHERE invoice_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $payment_slip = $row['payment_slip'];
        
        // ลบไฟล์สลิปการชำระเงินถ้ามี
        if (!empty($payment_slip)) {
            $slip_path = "uploads/" . $payment_slip;
            if (file_exists($slip_path)) {
                unlink($slip_path);
            }
        }
        
        // ลบข้อมูลจากฐานข้อมูล
        $delete_sql = "DELETE FROM invoice WHERE invoice_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $invoice_id);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "not_found";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "invalid_request";
}
?>