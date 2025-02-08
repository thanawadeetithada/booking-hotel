<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบแจ้งชำระเงิน</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .invoice-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
        }
        h2, h3 {
            text-align: center;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .customer-info {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .table-container {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th, .table-container td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .table-container th {
            background: #e0e0e0;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section p {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        <h2>ใบแจ้งชำระเงิน #0000001</h2>
        <p style="text-align: right;">ใบแจ้งชำระเงินสำหรับคำสั่งซื้อ: #10001</p>
        <p style="text-align: right;">วันที่สั่งซื้อ: 19/2/2567</p>
        <p style="text-align: right;">วันครบกำหนด: 20/3/2567</p>
    </div>

    <div class="customer-info">
        <strong>ข้อมูลลูกค้า:</strong><br>
        มณฑิกา แก้วระบำ<br>
        monthika-k@rmutp.ac.th
    </div>

    <table class="table-container">
        <tr>
            <th>สินค้า/บริการ</th>
            <th>จำนวน</th>
            <th>ราคา</th>
            <th>รวม</th>
        </tr>
        <tr>
            <td>ห้องพักผาชมดาว<br>15 กุมภาพันธ์ 2567 เวลา 21:25</td>
            <td>1</td>
            <td>฿1200.00</td>
            <td>฿1200.00</td>
        </tr>
    </table>

    <div class="total-section">
        <p>ยอดรวม: ฿500.00</p>
        <p>ภาษี (0%): ฿0.00</p>
        <p>ยอดรวมภาษี: ฿0.00</p>
        <p>ยอดรวมใบแจ้งชำระเงิน: ฿1200.00</p>
        <p>ยอดเงินที่ชำระแล้ว: ฿1200.00</p>
    </div>
</div>

</body>
</html>
