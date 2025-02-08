<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service | ผาชมดาว</title>
    <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
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
        background: url('bg/service.png') center/cover no-repeat;
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
        width: 100%;
        padding: 40px 90px;
        position: relative;
    }

    .booking-button {
        display: block;
        margin-left: auto;
    }



    .hotel-info h3 {
        font-size: 1.3rem;
        color: #0053A6;
        margin: 0;
    }


    .price-button:hover {
        background: #0053A6;
    }

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

    .booking-button {
        background: #0073E6;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .booking-tent-button {
        background: #0073E6;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
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
            <img src="room/home.jpg" alt="Jomtien Longstay Hotel" class="hotel-image">
            <div class="hotel-info">
                <div>
                    <h3>ข้อมูลห้องพัก <span class="stars"></span></h3>
                    <p>บ้านพัก 1 ห้องนอน 1 ห้องน้ำ</p>
                    <h4>ราคา 1,200 บาท/คืน</h4>
                </div>
                <button class="booking-button">จองที่พัก1</button>
            </div>
        </div>
    </section>


    <section class="hotel-listing">
        <div class="hotel-card">
            <img src="room/tent.jpg" alt="Jomtien Longstay Hotel" class="hotel-image">
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
                    <button style="margin-left: 40px;" class="booking-tent-button">จองที่พัก</button>


                </div>

            </div>

        </div>
    </section>




    <!-- News Section -->
    <section class="news">
        <h2>ข่าวสารประชาสัมพันธ์</h2>
        <div class="news-item">
            <img src="img/1.jpg" alt="โปรโมชั่น">
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