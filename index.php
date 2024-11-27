<?php 

?>
<!-- *********************************************************************** -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" name="David, Maria Isabel, Fernanda Montalvan, Cristian">
    <meta name="description" content="Vietnam Nails">
    <meta name="keywords" content="nail salon, manicure, pedicure">
    <link rel="stylesheet" href="public/Resources/css/style.css">
    <title>Vietnam Nails Echegaray</title>
    
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Vietnam Nails Echegaray</h1>
            <nav>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="public/Webpages/servicios.php">Servicios</a></li>
                    <li><a href="public/Webpages/reservas.php">Reservas</a></li>
                    <li><a href="public/Webpages/ofertas.php">Ofertas</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="home" class="hero">
        <div class="container">
            <div class="text-content">
                <h2>Hundreds of Colors, Organic Nail Polish!</h2>
                <p>With an obsession for moments, personalized nail enhancement, and lots of tidy colors!</p>
                <a href="#services" class="btn">Browse Now</a>
            </div>
            <div class="image-content">
                <img src="public/Resources/img/" alt="Nail Polish">
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <h2>About us...</h2>
            <p>"Every year we help thousands of women feel, and look even more stylish!"</p>
            <a href="#more-about" class="btn">Read More</a>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2>Take Care of <span>Your Nails</span></h2>
            <p>Paint Them Color Awesome!</p>
            <div class="service">
                <h3>Do Nails & Waxing!</h3>
                <p>Professional manicure and waxing services for a refreshed look.</p>
            </div>
        </div>
    </section>

    <section id="testimonials" class="testimonials">
        <div class="container">
            <h2>Testimonials</h2>
            <div class="testimonial">
                <p>"Just like every other girl, I love spending time doing my nails and looking stylish. So glad I found this place!"</p>
                <span>- Jane Doe</span>
            </div>
        </div>
    </section>

    <section id="blog" class="blog">
        <div class="container">
            <h2>Recent Blog Entries</h2>
            <div class="blog-entry">
                <h3>Can Aloe Be Used for Skin Care?</h3>
                <p>Exploring natural remedies and aloe's benefits for your skin.</p>
            </div>
            <div class="blog-entry">
                <h3>What is Non-Toxic Nail Polish?</h3>
                <p>Discover safer options for nail care.</p>
            </div>
            <div class="blog-entry">
                <h3>Top Colors for Nail Polish</h3>
                <p>Stay trendy with the latest nail polish shades.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <h2>Get All Special Offers!</h2>
            <form action="submit.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
            <p>&copy; 2024 Polish Nails. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
