<?php
session_start();
include 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // ตรวจสอบว่าข้อมูลไม่ว่าง
    if (!empty($name) && !empty($email) && !empty($message)) {
        // คำสั่ง SQL สำหรับบันทึกข้อมูล
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Your message has been sent successfully!";
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "All fields are required!";
    }

    // Redirect กลับไปที่หน้า contact.php
    header("Location: contact.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | ผาชมดาว</title>
    <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    .chat-container {
        max-width: 600px;
        margin: auto;
        height: 350px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ccc;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
    }

    .message-wrapper {
        display: flex;
        width: 100%;
        margin-bottom: 10px;
    }

    .message {
        max-width: 75%;
        padding: 10px;
        border-radius: 15px;
        word-wrap: break-word;
    }

    .message.user {
        background-color: white;
        color: black;
        justify-content: flex-end;
        border: 1px solid #ccc;
    }

    .message.admin {
        background-color: #0d6efd;
        color: white;
        justify-content: flex-start;
    }

    .user-message {
        display: flex;
        justify-content: flex-end;
    }

    .admin-message {
        display: flex;
        justify-content: flex-start;
    }

    .contact-section {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 50px;
        background: rgba(255, 255, 255, 0.8);
    }

    .contact-info {
        width: 40%;
        color: black;
        text-align: left;
    }

    .contact-info h2 {
        font-size: 2rem;
    }

    .quote {
        text-align: center;
        padding: 40px;
        background: #f9f9f9;
    }

    .quote h2 {
        font-size: 1.8rem;
    }

    .gallery {
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 20px;
    }

    .gallery img {
        width: 20%;
        border-radius: 10px;
    }

    /* Google Map */
    .map iframe {
        height: 450px;
    }

    .map {
        text-align: center;
    }

    @media (max-width: 768px) {
        .contact-section {
            flex-direction: column;
            text-align: center;
        }

        .contact-info,
        .contact-form {
            width: 90%;
        }

        .gallery {
            flex-wrap: wrap;
        }

        .gallery img {
            width: 45%;
        }
    }

    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('bg/contact.jpg') center/cover no-repeat;
        height: 90vh;
    }

    .hero .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .hero h1 {
        font-size: 5rem;
        z-index: 1;
    }

    .hero p {
        font-size: 1.5rem;
        z-index: 1;
    }

    .logo-img {
        width: 100px;
        height: 100px;
    }

    .btn {
        z-index: 1;
        display: inline-block;
        background: #ffd936;
        color: black;
        padding: 10px 20px;
        text-decoration: none;
        font-size: 1.5rem;
        border-radius: 5px;
        margin-top: 40vh;
    }

    /* Gallery Section */
    .gallery {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 40px;
    }

    .gallery-item img {
        width: 300px;
        height: 250px;
        border-radius: 10px;
    }

    .gallery-item p {
        color: #535d4c;
    }

    /* Review Section */
    .review {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 40px;
    }

    .review-item img {
        width: 300px;
        height: auto;
        border-radius: 10px;
    }

    .review-item p {
        color: #535d4c;
    }

    /* News Section */
    .news {
        text-align: center;
        padding: 40px;
    }

    .news-item img {
        width: 50%;
        border-radius: 10px;
    }

    .news-item p {
        font-size: 1.5rem;
    }

    footer nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
    }

    footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #414678;
        color: white;
        text-align: center;
        padding: 10px;
    }

    nav {
        display: flex;
        gap: 15px;
    }

    .logo-footer img {
        width: 100px;
        height: auto;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 3rem;
        }

        .hero p {
            font-size: 1.5rem;
        }

        .gallery {
            flex-direction: column;
            align-items: center;
        }

        .review-list {
            flex-direction: column;
            align-items: center;
        }

        .news-item img {
            width: 90%;
        }
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 97%;
        top: 0;
        left: 0;
        padding: 10px 20px;
        z-index: 1000;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
    }

    .nav-links li {
        display: inline;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        font-size: 1.2rem;
        padding: 10px;
        transition: 0.3s;
    }

    .nav-links a:hover {
        border-radius: 5px;
    }

    .contact-section {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        background: rgba(255, 255, 255, 0.8);
        padding: 30px;
        border-radius: 15px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-info {
        width: 45%;
    }

    .contact-form {
        width: 100%;
        max-width: 500px;
        /* จำกัดความกว้าง */
        background: white;
        padding: 30px 30px 20px 30px;
        /* เพิ่ม padding ให้ช่องห่างจากขอบ */
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-height: 60vh;

    }

    .contact-form h2 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: black;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        /* เพิ่มระยะห่างระหว่างช่อง */
        border: 1px solid #ccc;
        border-radius: 8px;
        /* ปรับมุมให้ดูโค้ง */
        font-size: 1rem;
        box-sizing: border-box;
        /* ป้องกันขนาดล้น */
        height: fit-content;
    }

    .contact-form textarea {
        height: 120px;
        /* กำหนดความสูงให้พอดี */
        resize: vertical;
        /* อนุญาตให้ปรับขนาดได้เฉพาะแนวตั้ง */
    }

    .contact-form button {
        width: 20%;
        background: #ffd936;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        border-radius: 8px;
        transition: 0.3s;
        margin: 0;
        margin-bottom: 15px;
    }

    .contact-form button:hover {
        background: orange;
    }

    a {
        color: black;
    }

    .social-icons {
        font-size: 1.5rem;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-container i {
        margin-bottom: 20px;
        font-size: 25px;
        margin-right: 10px;
    }

    .blur {
        filter: blur(3px);
        opacity: 0.6;
        pointer-events: none;
        user-select: none;
    }


    .text-login {
        color: red;
        font-weight: bold;
        font-size: 1rem;
    }

    .text-login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }
    </style>
</head>

<body>
    <header class="hero">
        <nav class="navbar">
            <div class="logo-container">
                <i class="fa-solid fa-arrow-left" onclick="goBack()"></i>
                <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></li>
            </ul>
        </nav>
        <div class="overlay"></div>
        <h1></h1>
        <p style="margin-top: 150px"></p>
        <br>
        <?php
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; text-align: center; font-size: 1.2rem;'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']); // ลบข้อความหลังแสดงผล
}
if (isset($_SESSION['error'])) {
    echo "<div style='color: red; text-align: center; font-size: 1.2rem;'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // ลบข้อความหลังแสดงผล
}
?>

        <section class="contact-section">

            <div class="contact-info">
                <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">

                <h2>You can find us at</h2>
                <p><strong>ที่อยู่:</strong> หมู่บ้านรักไทย ต.ชมพู อ.เนินมะปราง จ.พิษณุโลก</p>
                <p><strong>EMAIL:</strong> onjira.suwan@gmail.com</p>
                <p><strong>PHONE NUMBER:</strong> 087 523 1709</p>
                <div class="social-icons">
                    <a href="#" style="margin-right: 20px;"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-solid fa-location-dot"></i></a>
                </div>
            </div>

            <div class="contact-form">
                <h2>Let's get in touch</h2>
                <div class="container mt-4">
                    <div class="chat-container <?php echo isset($_SESSION['user_id']) ? '' : 'blur'; ?>" id="chatBox">
                    </div>

                    <div class="d-flex mt-3">
                        <input type="hidden" id="user_id"
                            value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">

                        <?php if (isset($_SESSION['user_id'])) : ?>
                        <input type="text" class="form-control me-2" id="chatInput" placeholder="พิมพ์ข้อความ...">
                        <button class="btn btn-primary" onclick="sendMessage()">ส่ง</button>
                        <?php else : ?>
                        <div class="text-login-container">
                            <p class="text-login" onclick="window.location.href='login.php'">
                                กรุณาเข้าสู่ระบบเพื่อส่งข้อความ
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>
    </header>

    <section class="quote">
        <h2>The world is a book and those who don't travel read only one page.</h2>
    </section>

    <!-- Gallery Section -->
    <section class="gallery">
        <img src="img/img1.jpg" alt="วิวภูเขา">
        <img src="img/img2.jpg" alt="วิวตอนเช้า">
        <img src="bg/contact.jpg" alt="พระอาทิตย์ตก">
        <img src="bg/service.jpg" alt="เต็นท์พักแรม">
    </section>

    <!-- Google Map Section -->
    <section class="map">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61904.84892639381!2d100.8276970622087!3d16.8951222984756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xecb332e4401bdffd!2z4LiE4LiT4Liy4LiZ4Liq4Li04LiX4LiiIOC4quC4hOC4q-C4seC4mSDguJXguLPguIHguK3guIfguLTguKrguJvguLLguKXguYzguLLguJQ!5e0!3m2!1sth!2sth!4v1617861290194!5m2!1sth!2sth" 
        width="80%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
</section>


    <!-- Footer -->
    <footer>
        <div></div>
        <nav style="font-size: 25px;">
            <a href="index.php">Home</a>
            <a href="service.php">Services</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="logo-footer">
            <img src="bg/logo.png" alt="โลโก้ผาชมดาว">
        </div>
    </footer>
    <script>
    function goBack() {
        window.history.back();
    }

    function loadMessages() {
        $.ajax({
            url: "load_messages.php",
            method: "GET",
            success: function(data) {
                $("#chatBox").html(data);
                $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
            }
        });
    }

    function sendMessage() {
        let message = $("#chatInput").val();
        let userId = $("#user_id").val();

        if (message.trim() === "") return;

        $.post("send_message.php", {
            sender: "user",
            user_id: userId,
            message: message
        }, function() {
            $("#chatInput").val("");
            loadMessages();
        });
    }

    setInterval(loadMessages, 1000);
    $(document).ready(loadMessages);
    </script>

</body>

</html>