<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Your message has been sent successfully!";
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "All fields are required!";
    }

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

    .contact-info {
        padding-left: 4rem;
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

    .map {
        text-align: center;
    }

    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background: #414678;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .logo-img {
        width: 80px;
        height: 80px;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 5px;
        margin: 0;
        padding: 0;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        font-size: 1.2rem;
        padding: 10px;
        transition: 0.3s;
    }

    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('bg/main.jpg') center/cover no-repeat;
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-top: 80px;
    }

    .btn {
        display: inline-block;
        background: #ffd936;
        color: black;
        padding: 12px 24px;
        font-size: 1.5rem;
        border-radius: 5px;
        margin-top: 20px;
        text-decoration: none;
    }

    .gallery,
    .review {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 40px;
        text-align: center;
    }

    .gallery-item img,
    .review-item img {
        width: 300px;
        height: auto;
        border-radius: 10px;
    }

    .gallery-item p,
    .review-item p {
        color: #535d4c;
    }

    .news {
        text-align: center;
        padding: 40px;
    }

    .news-item img {
        width: 50%;
        border-radius: 10px;
    }

    footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #414678;
        color: white;
        text-align: center;
        padding: 15px;
        flex-wrap: wrap;
    }

    footer nav {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-grow: 1;
    }

    footer .logo-footer {
        display: flex;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    footer a {
        color: white;
        text-decoration: none;
        font-size: 1.2rem;
    }

    @media (max-width: 390px) {

        .chat-container {
            height: 100px;
        }

        .contact-section {
            margin-top: 1.7rem !important;
            padding: 10px !important;
        }

        .logo-img {
            width: 50px !important;
            height: 50px !important;
        }

        h2 {
            font-size: 0.8rem !important;
            margin-bottom: 0px !important;
        }

        .social-icons {
            font-size: 1rem !important;
        }

        p {
            margin-bottom: 2px !important;
        }

        .contact-form {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .container {
            margin-top: 5px !important;
        }

        .contact-form input {
            padding: 5px !important;
            font-size: 0.8rem !important;
            margin-bottom: 0px !important;
        }

        .contact-form button {
            padding: 5px !important;
            font-size: 0.8rem !important;
        }

        .fa-facebook,
        .fa-location-dot,
        .fa-arrow-left {
            font-size: 0.8rem !important;
        }

        .logo-container i {
            margin-bottom: 10px !important;
        }
        .text-input {
            margin-top: 10px !important;
        }

        .message.user {
            padding: 5px;
            font-size: 0.8rem;
        }

        .message.admin {
            padding: 5px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 412px) {
        .contact-section {
            padding-bottom: 10px !important;
            padding-top: 10px !important;
        }
    }

    @media (max-width: 1152px) {
        .logo-container {
            padding-left: 10px;
            padding-top: 10px;
        }

        .navbar {
            padding: 5px;
        }

        .nav-links a {
            font-size: 1rem;
        }

        .contact-section {
            flex-direction: column;
            align-items: center;
            padding: 0;
            width: 90% !important;
            margin-top: 3rem;
        }

        .contact-info {
            padding-left: 0;
            width: 100%;
            text-align: center;
        }

        .contact-info h2 {
            font-size: 0.8rem;
            font-weight: bold;
        }

        .contact-info p {
            font-size: 0.8rem !important;
        }

        .contact-form {
            margin-top: 0.5rem;
            width: 100%;
            max-width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .contact-form h2 {
            font-size: 0.8rem !important;
            margin-bottom: 15px;
        }

        .contact-form input {
            width: 100%;
            margin-bottom: 10px;
        }

        .gallery {
            flex-direction: column;
            align-items: center;
        }

        .gallery img {
            width: 80%;
            margin-bottom: 10px;
        }

        footer {
            flex-direction: column;
            align-items: center;
        }

        footer nav {
            flex-direction: column;
            gap: 10px;
        }

        .chat-container {
            max-height: 200px;
            min-height: 200px;
            width: 100%;
        }

        .text-login-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .text-login {
            font-size: 1rem;
            margin-top: 10px;
        }
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
        justify-content: space-between;
        align-items: center;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-form {
        width: 100%;
        max-width: 500px;
        background: white;
        padding: 30px 30px 20px 30px;
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

    .contact-form input {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
        height: fit-content;
    }

    .contact-form button {
        width: fit-content;
        background: #ffd936;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        border-radius: 8px;
        transition: 0.3s;
        margin: 0;
        margin-bottom: 15px;
        padding: 10px;
    }

    .contact-form button:hover {
        background: orange;
    }

    .social-icons {
        font-size: 1.5rem;
    }

    .social-icons a {
        color: black;
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

    .contact-info p {
        font-size: 1.5rem;
    }

    .fa-arrow-left {
        color: white;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo-container">
            <i class="fa-solid fa-arrow-left" onclick="goBack()"></i>
            <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="service.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="hero">
        <h1></h1>
        <p style="margin-top: 150px"></p>
        <br>
        <?php
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; text-align: center; font-size: 1.2rem;'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div style='color: red; text-align: center; font-size: 1.2rem;'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
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
                    <a href="https://www.facebook.com/p/%E0%B8%9A%E0%B9%89%E0%B8%B2%E0%B8%99%E0%B8%9C%E0%B8%B2%E0%B8%8A%E0%B8%A1%E0%B8%94%E0%B8%B2%E0%B8%A7-100057387581971/"
                        style="margin-right: 20px;"><i class="fa-brands fa-facebook"></i></a>
                    <a
                        href="https://www.google.com/maps/dir//PJGR%2B5J6+%E0%B8%9A%E0%B9%89%E0%B8%B2%E0%B8%99%E0%B8%9C%E0%B8%B2%E0%B8%8A%E0%B8%A1%E0%B8%94%E0%B8%B2%E0%B8%A7+%E0%B8%9A%E0%B9%89%E0%B8%B2%E0%B8%99%E0%B8%A3%E0%B8%B1%E0%B8%81%E0%B9%84%E0%B8%97%E0%B8%A2+%E0%B8%9E%E0%B8%B4%E0%B8%A9%E0%B8%93%E0%B8%B8%E0%B9%82%E0%B8%A5%E0%B8%81+Chomphu,+Noen+Maprang+District,+Phitsanulok+65190/@16.7254889,100.6003282,13z/data=!4m8!4m7!1m0!1m5!1m1!1s0x312075e0cedf9253:0x8d6135e2caa50517!2m2!1d100.6415278!2d16.7254108?entry=ttu&g_ep=EgoyMDI1MDIxMC4wIKXMDSoASAFQAw%3D%3D"><i
                            class="fa-solid fa-location-dot"></i></a>
                </div>
            </div>

            <div class="contact-form">
                <h2>Let's get in touch</h2>
                <div class="container mt-4">
                    <div class="chat-container <?php echo isset($_SESSION['user_id']) ? '' : 'blur'; ?>" id="chatBox">
                    </div>

                    <div class="text-input d-flex mt-3">
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

    <section class="gallery">
        <img src="img/mg1.jpg" alt="วิวภูเขา">
        <img src="img/img2.jpg" alt="วิวตอนเช้า">
        <img src="img/contact.jpg" alt="พระอาทิตย์ตก">
        <img src="img/img4.jpg" alt="เต็นท์พักแรม">
    </section>

    <section class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61904.84892639381!2d100.8276970622087!3d16.8951222984756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xecb332e4401bdffd!2z4LiE4LiT4Liy4LiZ4Liq4Li04LiX4LiiIOC4quC4hOC4q-C4seC4mSDguJXguLPguIHguK3guIfguLTguKrguJvguLLguKXguYzguLLguJQ!5e0!3m2!1sth!2sth!4v1617861290194!5m2!1sth!2sth"
            width="80%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
    </section>

    <footer>
        <nav>
            <a href="index.php">Home</a>
            <a href="service.php">Services</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="logo-footer">
            <img src="bg/logo.png" alt="โลโก้" width="80">
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