<?php
session_start();
include 'db.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['invoice_id'])) {
    die("❌ ไม่พบหมายเลขใบแจ้งชำระเงิน");
}

$booking_id = $_GET['invoice_id'];
$userrole = $_SESSION['userrole'] ?? 'user'; // ป้องกัน Undefined Variable

$stmt = $conn->prepare("SELECT * FROM invoice WHERE invoice_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    die("❌ ไม่พบข้อมูลการจอง");
}

$email = $_SESSION['email'] ?? null;
$invoice_items = [$invoice];

if ($userrole === 'admin') {
    $recipient_email = $invoice['email'] ?? null; 
} else {
    $recipient_email = $email;
}

$options = new Options();
$options->set('defaultFont', 'THSarabunNew');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->setChroot(__DIR__);


$dompdf = new Dompdf($options);
$fontDir = __DIR__ . "/fonts/";
$fontPath = realpath($fontDir . "THSarabunNew.ttf");
if (!$fontPath) {
    die("❌ ไม่พบฟอนต์ THSarabunNew");
}

$created_date = date("d-m-Y เวลา H:i", strtotime($invoice['created_at']));
$tax_amount = (float)$invoice['paid_amount'] - (float)$invoice['total_amount'];
$tax_display = number_format($tax_amount, 2);
$html = <<<HTML
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบแจ้งชำระเงิน</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew.ttf') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew-Bold.ttf') format('truetype');
            font-weight: bold;
        }
        
        .header-text { 
            text-align: right; 
            font-size: 16pt;
            margin: 0;
            padding:0;

        }
        .invoice-container h2 { 
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew-Bold.ttf') format('truetype');
            text-align: center; 
            font-size: 24pt;
        }
        .info { 
            text-align: left; 
            font-size: 16pt;
            background-color : #F9F9F9;
            padding-left: 10px;
        }
        .info h4 { 
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew-Bold.ttf') format('truetype');
            margin-bottom: 20px; 
            text-align: left; 
            font-size: 16pt;
        }
        .table-container { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table-container th, 
        .table-container td { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; 
        }
        .table-container th:first-child,
        .table-container td:first-child {
            text-align: left;
        }

        .table-container th { 
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew-Bold.ttf') format('truetype');
            font-size: 18pt;
            background-color : #E0E0E0;
        }      
        
        .table-container td { 
            font-size: 16pt;
        }      
        .total-section h4 { 
            font-family: 'THSarabunNew';
            src: url('fonts/THSarabunNew-Bold.ttf') format('truetype');
            text-align: right; 
            font-size: 18pt;
            margin: 0;
            padding:0;
        }

        .total-section {
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2>ใบเสร็จ</h2>
        <p class="header-text">เลขที่ใบเสร็จ {$invoice['invoice_number']}</p>
        <p class="header-text">วันที่ชำระเงิน {$created_date}</p>

        <div class="info">
            <h4>ข้อมูลลูกค้า:</h4>
            {$invoice['first_name']} {$invoice['last_name']}
        </div>

        <table class="table-container">
            <tr>
                <th>ประเภทห้อง x คืน</th>
                <th>จำนวนคน</th>
                <th>ราคา</th>
                <th>รวม</th>
            </tr>
HTML;

$total = 0;
foreach ($invoice_items as $item) {

    $html .= "<tr>
                <td>{$item['room_type']} (เลขห้อง {$item['room_number']}) x {$item['nights']}</td>
                <td>{$item['guest_count']}</td>
                <td>฿{$item['price']}</td>
                <td>฿{$item['total_amount']}</td>
              </tr>";
}

$status_payment = $item['status_payment'];
$status_text = '';

if ($status_payment === 'paid') {
    $status_text = '<h4 style="color: red;">จ่ายแล้ว</h4>';
} elseif ($status_payment === 'pending') {
    $status_text = '<h4 style="color: red;">รอชำระเงิน</h4>';
} else {
    $status_text = '<h4 style="color: gray;">สถานะไม่ทราบ</h4>';
}

$html .= <<<HTML
        </table>
        <div class="total-section">
            <h4>ยอดรวม: ฿{$item['total_amount']}</h4>
            <h4>ภาษี (7%): ฿{$tax_display}</h4>
            <br>
            <h4>ยอดรวมสุทธิ: ฿{$item['paid_amount']}</h4>
            <h4>ยอดชำระ: ฿{$item['paid_amount']}</h4>
            <h4>{$status_text}</h4>
        </div>
    </div>
</body>
</html>
HTML;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$invoice_dir = 'invoices/';
if (!is_dir($invoice_dir)) {
    mkdir($invoice_dir, 0777, true);
}

$pdfPath = __DIR__ . "/invoices/invoice_" . $booking_id . ".pdf";
if (!file_put_contents($pdfPath, $dompdf->output())) {
    die("❌ ไม่สามารถบันทึกไฟล์ PDF ได้");
}

// ส่งอีเมล
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mitinventor015@gmail.com';
    $mail->Password = 'etptordrjdzhhsas';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('mitinventor015@gmail.com', 'Pha Chom Dao Resort : Receipt');
    $mail->addAddress($recipient_email);
    $mail->addAttachment($pdfPath);

    $mail->isHTML(true);
    $mail->Subject = mb_encode_mimeheader('ใบเสร็จ Pha Chom Dao Resort', 'UTF-8', 'B');
    $mail->Body = "<p>เรียน " . htmlspecialchars($invoice['first_name']) . ",</p>
                   <p>แนบไฟล์ใบเสร็จของคุณ โปรดตรวจสอบรายละเอียด</p>";

    if ($mail->send()) {
        header("Location: receipt_booking.php?invoice_id={$invoice['invoice_id']}");
    exit();
    }
                
} catch (Exception $e) {
    echo "❌ ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}";
}
?>