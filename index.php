<?php
session_start(); // เริ่ม session
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pha Chom Dao Resort</title>
    <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
    /* ตั้งค่าพื้นฐาน */
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
        background: url('bg/main.jpg') center/cover no-repeat;
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
        font-size: 2rem;
        z-index: 1;
    }

    .hero-img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
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
    </style>
</head>

<body>
    <header class="hero">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <img class="logo-img" src="bg/logo.png" alt="ผาชมดาว">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="overlay"></div>
        <h1></h1>
        <p style="margin-top: 150px"></p>
        <br>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn">เข้าสู่ระบบ</a>
        <?php endif; ?>
    </header>


    <!-- Gallery Section -->
    <section class="gallery">
        <div class="gallery-item"><img src="img/img1.jpg" alt="วิวทะเลหมอก">
            <p>ตั้งใจมาดูพระอาทิตย์ขึ้นแต่ดันลุกไม่ขึ้นซะนี่ !!!</p>
        </div>
        <div class="gallery-item"><img src="img/img2.jpg" alt="วิวต้นไม้ตอนเช้า">
            <p>"ตอนเด็กชอบกินขนมแต่ตอนโตชอบกินลมชมวิว"</p>
        </div>
        <div class="gallery-item"><img src="img/img3.jpg" alt="พระอาทิตย์ตก">
            <p>ถึงตะวันจะชิงพลบแต่เราจะรบเพื่อชิงเธอ</p>
        </div>
    </section>

    <!-- Review Section -->
    <section class="review">
        <div class="review-item"><img src="img/review1.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="review-item"><img src="img/review2.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="review-item"><img src="img/review3.jpg" alt="พระอาทิตย์ตก">
        </div>
    </section>
    <section class="review">
        <div class="review-item"><img src="img/review4.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="review-item"><img src="img/review5.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="review-item"><img src="img/review6.jpg" alt="พระอาทิตย์ตก">
        </div>
    </section>

    <!-- News Section -->
    <section class="news">
        <h2>ข่าวสารประชาสัมพันธ์</h2>
        <div class="news-item">
            <img src="img/promo.jpg" alt="โปรโมชั่น">
        </div>
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

</body>

</html>