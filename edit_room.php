

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลกำหนดราคาห้อง</title>
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
        }
        h2 {
            margin-bottom: 20px;
            color: black;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>ข้อมูลกำหนดราคาห้อง</h2>
        <form action="register.php" method="POST">
            <label for="first_name">รหัสห้อง</label>
            <input type="text" id="first_name" name="first_name" placeholder="ชื่อ" required>
            
            <label for="last_name">เลขห้อง</label>
            <input type="text" id="last_name" name="last_name" placeholder="นามสกุล" required>
            
            <label for="phone">ราคา</label>
            <input type="text" id="phone" name="phone" placeholder="เบอร์โทรศัพท์" required>
            
            <label for="email">ประเภท</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required>
            
            <label for="id_card">รายละเอียด</label>
            <input type="text" id="id_card" name="id_card" placeholder="เลขบัตรประชาชน" required>
            
            <label for="birthdate">รูปภาพ</label>
            <input type="date" id="birthdate" name="birthdate" required onfocus="this.showPicker()">
            
            <div class="button-group">
                <button type="submit" class="submit-btn">สมัครสมาชิก</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='index.html'">ยกเลิก</button>
            </div>
          

        </form>
    </div>
</body>
</html>
