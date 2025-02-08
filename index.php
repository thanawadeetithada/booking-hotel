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

    /* Hero Section */
    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('hero.png') center/cover no-repeat;
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .hero .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        /* background: rgba(0, 0, 0, 0.4); */
    }

    .hero h1 {
        font-size: 5rem;
        z-index: 1;
    }

    .hero p {
        font-size: 2rem;
        z-index: 1;
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
        margin-top: 20px;
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
    justify-content: space-between; /* ให้ nav และโลโก้ไปอยู่คนละข้าง */
    align-items: center;
    background: #414678;
    color: white;
    text-align: center;
    padding: 50px;
}

nav {
    display: flex;
    gap: 15px;
}

.logo-footer img {
    width: 100px;  /* ปรับขนาดรูป */
    height: auto;
}
    /* Scroll to Top Button */
    .scroll-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: yellow;
        border: none;
        padding: 10px 15px;
        font-size: 1.5rem;
        border-radius: 50%;
        cursor: pointer;
        display: none;
    }

    .scroll-top:hover {
        background: orange;
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

    .hero-img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .hero {
        background: url('img/hero.png') center/cover no-repeat;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        width: 97%;
        top: 0;
        left: 0;
        /* border-bottom: 1px solid; */
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
        color: black;
        border-radius: 5px;
    }

    .logo-img {
        width: 50px;
        height: 50px;
    }

    </style>
</head>

<body>
    <header class="hero">
        <!-- Navigation Bar -->
        <nav class="navbar">
                <img class="logo-img" src="img/logo.png" alt="ผาชมดาว">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="overlay"></div>
        <img src="hero.png" alt="วิวสวยๆ" class="hero-img">
        <h1></h1>
        <p style="margin-top: 150px"></p>
        <br>
        <a href="#" class="btn">เข้าสู่ระบบ</a>

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
        <nav style="
    font-size: 25px;">
            <a href="#">Home</a>
            <a href="#">Services</a>
            <a href="#">Contact</a>
        </nav>
        <div class="logo-footer">
            <img src="img/logo.png" alt="โลโก้ผาชมดาว">
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-top" onclick="scrollToTop()">↑</button>

    <script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    </script>

</body>

</html>