<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php'; // โหลด TCPDF

// ตั้งค่าข้อมูลตัวอย่าง
$invoice = [
    'invoice_number' => 'INV123456',
    'first_name' => 'สมชาย',
    'last_name' => 'ใจดี'
];
$created_date = date("d/m/Y");

$invoice_items = [
    [
        'room_type' => 'Deluxe Room',
        'room_number' => '101',
        'nights' => 2,
        'guest_count' => 2,
        'price' => 1500,
        'total_amount' => 3000,
        'status_payment' => 'paid',
        'paid_amount' => 3210
    ]
];

$tax_display = number_format(3000 * 0.07, 2); // ภาษี 7%

// แปลงสถานะการชำระเงิน
$status_text = '';
$status_payment = $invoice_items[0]['status_payment'];
if ($status_payment === 'paid') {
    $status_text = '<h4 style="color: red;">จ่ายแล้ว</h4>';
} elseif ($status_payment === 'pending') {
    $status_text = '<h4 style="color: red;">รอชำระเงิน</h4>';
} else {
    $status_text = '<h4 style="color: gray;">สถานะไม่ทราบ</h4>';
}

// สร้าง HTML สำหรับ PDF
$html = "
<!DOCTYPE html>
<html lang='th'>
<head>
    <meta charset='UTF-8'>
    <title>ใบแจ้งชำระเงิน</title>
    <style>
        body { font-family: 'thsarabunnew', sans-serif; font-size: 16pt; }
        h2 { text-align: center; font-size: 24pt; }
        .header-text { text-align: right; font-size: 16pt; margin: 0; padding: 0; }
        .info { background-color: #F9F9F9; padding-left: 10px; font-size: 16pt; }
        .info h4 { font-size: 16pt; margin-bottom: 10px; }
        .table-container { width: 100%; border-collapse: collapse; }
        .table-container th, .table-container td { padding: 10px; border-bottom: 1px solid #ddd; text-align: center; }
        .table-container th { font-size: 18pt; background-color: #E0E0E0; }
        .table-container td { font-size: 16pt; }
        .total-section h4 { text-align: right; font-size: 18pt; margin: 0; padding: 0; }
        .total-section { margin-top: 20px; }
    </style>
</head>
<body>
    <div class='invoice-container'>
        <h2>ใบเสร็จ</h2>
        <p class='header-text'>เลขที่ใบเสร็จ {$invoice['invoice_number']}</p>
        <p class='header-text'>วันที่ชำระเงิน {$created_date}</p>

        <div class='info'>
            <h4>ข้อมูลลูกค้า:</h4>
            {$invoice['first_name']} {$invoice['last_name']}
        </div>
        <hr>
        <table class='table-container'>
            <tr>
                <th>ประเภทห้อง x คืน</th>
                <th>จำนวนคน</th>
                <th>ราคา</th>
                <th>รวม</th>
            </tr>
             <hr>
             ";

foreach ($invoice_items as $item) {
    $html .= "
            <tr>
                <td>{$item['room_type']} (เลขห้อง {$item['room_number']}) x {$item['nights']}</td>
                <td>{$item['guest_count']}</td>
                <td>฿{$item['price']}</td>
                <td>฿{$item['total_amount']}</td>
            </tr>"
            ;
}

$html .= "
        </table>
        <div class='total-section'>
            <h4>ยอดรวม: ฿{$invoice_items[0]['total_amount']}</h4>
            <h4>ภาษี (7%): ฿{$tax_display}</h4>
            <br>
            <h4>ยอดรวมสุทธิ: ฿{$invoice_items[0]['paid_amount']}</h4>
            <h4>ยอดชำระ: ฿{$invoice_items[0]['paid_amount']}</h4>
            <h4>{$status_text}</h4>
        </div>
    </div>
</body>
</html>";

// สร้างไฟล์ PDF
$pdf = new TCPDF();
$pdf->AddFont('thsarabunnew', '', 'thsarabunnew.php'); // โหลดฟอนต์
$pdf->AddFont('thsarabunnew', 'B', 'thsarabunnewb.php'); // โหลดฟอนต์ตัวหนา
$pdf->AddPage();
$pdf->SetFont('thsarabunnew', '', 16); // ใช้ฟอนต์ภาษาไทย

// เขียน HTML ลง PDF
$pdf->writeHTML($html, true, false, true, false, '');

// บันทึกไฟล์ PDF
$pdfFilePath = __DIR__ . '/invoices/receipt.pdf';
$pdf->Output($pdfFilePath, 'F'); // บันทึกเป็นไฟล์

echo "✅ ไฟล์ PDF สร้างเสร็จแล้ว: " . $pdfFilePath;
?>
