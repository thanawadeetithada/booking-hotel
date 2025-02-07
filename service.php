<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pha Chom Dao Resort</title>
    <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    /* ตั้งค่าพื้นฐาน */
    body {
        font-family: 'Sriracha', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Hero Section */
    .hero {
        position: relative;
        text-align: center;
        color: white;
        background: url('service.png') center/cover no-repeat;
        height: 50vh;
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

    /* Footer */
    footer nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
    }

    footer {
        display: flex;
        justify-content: space-between;
        /* ให้ nav และโลโก้ไปอยู่คนละข้าง */
        align-items: center;
        background: #2c3e50;
        color: white;
        text-align: center;
        padding: 20px;
    }

    nav {
        display: flex;
        gap: 15px;
    }

    .logo-footer img {
        width: 100px;
        /* ปรับขนาดรูป */
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
        background: url('img/service.png') center/cover no-repeat;
    }

    .navbar {
        display: flex;
        justify-content: flex-end;
        position: fixed;
        width: 97%;
        top: 0;
        left: 0;
        border-bottom: 0px solid;
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

    body {
        font-family: 'Sriracha', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Contact Section */
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
    }

    .contact-info h2 {
        font-size: 2rem;
    }

    .contact-form {
        width: 50%;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-form h2 {
        text-align: center;
        color: black;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .contact-form button {
        width: 100%;
        padding: 10px;
        background: yellow;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        border-radius: 15px;
    }

    .contact-form button:hover {
        background: orange;
    }

    /* Quote Section */
    .quote {
        text-align: center;
        padding: 40px;
        background: #f9f9f9;
    }

    .quote h2 {
        font-size: 1.8rem;
    }

    /* Gallery Section */
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
        width: 100%;
        height: 450px;
    }

    .map {
        text-align: center;
    }



    footer nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
    }

    footer {
        display: flex;
        justify-content: space-between;
        /* ให้ nav และโลโก้ไปอยู่คนละข้าง */
        align-items: center;
        background: #2c3e50;
        color: white;
        text-align: center;
        padding: 20px;
    }

    nav {
        display: flex;
        gap: 15px;
    }

    .logo-footer img {
        width: 100px;
        /* ปรับขนาดรูป */
        height: auto;
    }


    /* Responsive Design */
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

    /* ตั้งค่าพื้นฐาน */
    body {
        font-family: 'Sriracha', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Hero Section */
    .hero {
        position: relative;
        text-align: left;
        color: white;
        background: url('img3.jpg') center/cover no-repeat;
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
        font-size: 1.5rem;
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

    /* Footer */
    footer {
        background: #414678;
        color: white;
        text-align: center;
        padding: 50px;
    }

    footer nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
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
        background: url('img/img3.jpg') center/cover no-repeat;
    }

    .navbar {
        display: flex;
        justify-content: flex-end;
        position: fixed;
        width: 97%;
        top: 0;
        left: 0;
        border-bottom: 0px solid;
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

    .contact-info,
    .contact-form {
        width: 45%;
    }

    .contact-form {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .hero {
        position: relative;
        text-align: left;
        color: white;
        background: url('img/service.png') center/cover no-repeat;
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .logo-img {
        width: 100px;
        height: 100px;

    }

    .logo-text {
        text-align: center;
        width: 100%;
    }

    a {
        color: black;
    }

    .social-icons {
        font-size: 1.5rem;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;
    }

    .search-box {
        display: flex;
        background: white;
        padding: 15px;
        /* เพิ่ม padding */
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 3px solid #FFC107;
        overflow: hidden;
        width: 60%;
        /* เพิ่มความกว้างของกล่องค้นหา */
        max-width: 800px;
        /* จำกัดความกว้างสูงสุด */
    }

    .search-box input,
    .search-box select {
        border: none;
        padding: 12px;
        font-size: 1.2rem;
        /* เพิ่มขนาดตัวอักษร */
        outline: none;
        width: 100%;
        /* ให้กินพื้นที่เต็ม */
    }

    .search-box select {
        cursor: pointer;
    }

    .search-box button {
        background: #0073E6;
        color: white;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 1.2rem;
        border-radius: 5px;
    }

    .search-box button:hover {
        background: #0053A6;
    }

    .icon {
        padding: 12px;
        font-size: 1.5rem;
        /* ขยายขนาดไอคอน */
        color: #333;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .search-box {
            flex-direction: column;
            align-items: center;
            width: 90%;
        }

        .search-box input,
        .search-box select,
        .search-box button {
            width: 100%;
            margin-top: 5px;
        }
    }

    /* Hotel Listing */
    .hotel-listing {
        display: flex;
        justify-content: center;
        margin: 40px 0;
    }

    .hotel-card {
        display: flex;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 900px;
        overflow: hidden;
        border: 2px solid #ddd;
    }

    .hotel-image {
        width: 400px;
        height: 300px;
        object-fit: cover;
    }

    .hotel-info {
        padding: 40px 0 0 90px;
        flex: 1;
    }

    .hotel-info h3 {
        font-size: 1.3rem;
        color: #0053A6;
        margin: 0;
    }

    .stars {
        color: orange;
        font-size: 1rem;
    }

    .tag {
        color: #0073E6;
        font-size: 0.9rem;
        display: block;
        margin: 5px 0;
    }

    .hotel-rating {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .rating-text {
        background: #0073E6;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .rating-score {
        font-size: 1.2rem;
        font-weight: bold;
        color: #0073E6;
        margin-left: 10px;
    }

    .reviews {
        font-size: 0.9rem;
        color: gray;
        margin-left: 10px;
    }

    .price-button {
        background: #0073E6;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .price-button:hover {
        background: #0053A6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hotel-card {
            flex-direction: column;
            width: 90%;
        }

        .hotel-image {
            width: 100%;
            height: 180px;
        }
    }

    .hotel-icon-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding-right: 20px;
        justify-content: center;
    }

    .hotel-icon {
        display: flex;
        justify-content: center;
        gap: 15px;
        /* ระยะห่างระหว่างไอคอน */
    }

    .hotel-icon i {
        font-size: 2rem;
        color: #0073E6;
    }
    </style>
</head>

<body>
    <header class="hero">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="overlay"></div>
        <img src="service.png" alt="วิวสวยๆ" class="hero-img">
    </header>


    <div class="search-container">
        <div class="search-box">
            <span class="icon"><i class="fas fa-calendar"></i></span>
            <input type="text" placeholder="วันเช็คอิน — วันเช็คเอาท์">

            <span class="icon"><i class="fas fa-user"></i></span>
            <select>
                <option>ผู้ใหญ่ 2 - เด็ก 0 - 1 ห้อง</option>
                <option>ผู้ใหญ่ 1 - เด็ก 0 - 1 ห้อง</option>
                <option>ผู้ใหญ่ 2 - เด็ก 1 - 1 ห้อง</option>
            </select>

            <button>ค้นหา</button>
        </div>
    </div>

    <!-- Hotel Listing -->
    <section class="hotel-listing">
        <div class="hotel-card">
            <img src="img/home.jpg" alt="Jomtien Longstay Hotel" class="hotel-image">
            <div class="hotel-info">
                <h3>ข้อมูลห้องพัก<span class="stars"></span></h3>
                <p>บ้านพัก 1 ห้องนอน 1 ห้องน้ำ</p>
                <h4>ราคา 1,200 บาท/คืน</h4>
                <button class="price-button">จองที่พัก</button>
            </div>
        </div>
    </section>

    <section class="hotel-listing">
        <div class="hotel-card">
            <img src="img/tent.jpg" alt="Jomtien Longstay Hotel" class="hotel-image">
            <div class="hotel-info">
                <h3>ข้อมูลเต็นท์ <span class="stars"></span></h3>
                <p>ห้องน้ำรวม</p>
                <h4>ราคา 150 บาท/คน</h4>
            </div>

            <div class="hotel-icon-container">
                <div class="hotel-icon">
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                </div>
                <div class="hotel-icon">
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                </div>
                <div class="hotel-icon">
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                    <i class="fa-solid fa-tent" style="color:gray"></i>
                </div>

                <div class="hotel-icon"></div>
                <div class="hotel-icon"></div>
                <div class="hotel-icon1">
                    <div class="hotel-icon">
                        <i class="fa-solid fa-tent" style="font-size: 20px; color:gray"></i>จองได้
                        <i class="fa-solid fa-tent" style="font-size: 20px; color:red"></i>เต็ม

                    </div>
                    <button style="margin-left: 40px;" class="price-button">จองที่พัก</button>


                </div>

            </div>

        </div>
    </section>




    <!-- News Section -->
    <section class="news">
        <h2>ข่าวสารประชาสัมพันธ์</h2>
        <div class="news-item">
            <img src="img/promo.jpg" alt="โปรโมชั่น">
        </div>
    </section>

    <section class="gallery">
        <div class="gallery-item"><img src="img/img1.jpg" alt="วิวทะเลหมอก">
        </div>
        <div class="gallery-item"><img src="img/img2.jpg" alt="วิวต้นไม้ตอนเช้า">
        </div>
        <div class="gallery-item"><img src="img/img3.jpg" alt="พระอาทิตย์ตก">
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