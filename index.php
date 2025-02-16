<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pha Chom Dao Resort</title>
    <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Navbar */
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

    /* Hero Section */
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
        margin-top: 5rem;
    }
     .hero p {
        margin-top: 150px
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

    /* Gallery & Review */
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

    /* News Section */
    .news {
        text-align: center;
        padding: 40px;
    }

    .news-item img {
        width: 50%;
        border-radius: 10px;
    }

    /* Footer */
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

    /* Responsive */
    @media (max-width: 768px) {

        .gallery,
        .review {
            flex-direction: column;
            align-items: center;
        }

        .news-item img {
            width: 90%;
        }

        footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        footer nav {
            flex-direction: column;
            gap: 10px;
        }

        .navbar {
            padding: 5px;
        }

        .nav-links a {
            font-size: 1rem;
        }

        .hero {
            height: 25vh !important;
        }

        .hero p {
            margin-top: 0px !important;
        }
        .btn {
            margin-top: 0px !important;
            padding: 10px;
            font-size: 1rem;
        }
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php"><img class="logo-img" src="bg/logo.png" alt="ผาชมดาว"></a>
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
        <p></p>
        <br>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn">เข้าสู่ระบบ</a>
        <?php endif; ?>
    </header>

    <section class="gallery">
        <div class="gallery-item"><img src="img/mg1.jpg" alt="วิวทะเลหมอก">
            <p>ตั้งใจมาดูพระอาทิตย์ขึ้นแต่ดันลุกไม่ขึ้นซะนี่ !!!</p>
        </div>
        <div class="gallery-item"><img src="img/img2.jpg" alt="วิวต้นไม้">
            <p>"ตอนเด็กชอบกินขนมแต่ตอนโตชอบกินลมชมวิว"</p>
        </div>
        <div class="gallery-item"><img src="img/img3.jpg" alt="พระอาทิตย์ตก">
            <p>ถึงตะวันจะชิงพลบแต่เราจะรบเพื่อชิงเธอ</p>
        </div>
    </section>

    <section class="review">
        <div class="review-item"><img src="review/review1.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="review-item"><img src="review/review2.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="review-item"><img src="review/review3.jpg" alt="พระอาทิตย์ตก">
        </div>
    </section>
    <section class="review">
        <div class="review-item"><img src="review/review4.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="review-item"><img src="review/review5.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="review-item"><img src="review/review6.jpg" alt="พระอาทิตย์ตก">
        </div>
    </section>

    <section class="news">
        <h2>ข่าวสาร</h2>
        <div class="news-item"><img src="img/promo.jpg" alt="โปรโมชั่น"></div>
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
</body>

</html>