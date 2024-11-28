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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
</head>
<body>
    <header class="header">
        <h1>Vietnam Nails Echegaray</h1>
        <nav>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="public/Webpages/servicios.php">Servicios</a></li>
                <li><a href="public/Webpages/reservas.php">Reservas</a></li>
                <li><a href="public/Webpages/ofertas.php">Ofertas</a></li>
            </ul>
        </nav>
    </header>

    <section id="home">
        <div class="container">
            <div class="text-content">
                <h2>Decenas de colores ¡Pintura de uñas Organica!</h2>
                <p>Con una obsesión por los momentos, servicio personalizado, y muchos colores!</p>
                <a href="public/Webpages/reservas.php" class="btn">Reserva ahora</a>
            </div>
            
            <img src="public/Resources/img/unas/portrait_nails/nail_face2.jpg" alt="Nail Polish">
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div>
                <h2>Nuestro trabajo...</h2>
                <p>"Cada año ayudamos a que las mujeres de Barcelona se vean y se sientan mejor!"</p>
                <a href="#more-about" class="btn">Read More</a>
            </div>

            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="public/Resources/img/unas/hand_nails/unas_gel.jpg" class="d-block w-100" alt="uñas de gel">
                    </div>
                    <div class="carousel-item">
                        <img src="public/Resources/img/unas/hand_nails/halloween_nails.jpg" class="d-block w-100" alt="uñas con diseño de Halloween">
                    </div>
                    <div class="carousel-item">
                        <img src="public/Resources/img/unas/hand_nails/green_nails.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                </div>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <h2>Take Care of <span>Your Nails</span></h2>
            <p>Paint Them Color Awesome!</p>
            <div class="service">
                <h3>Do Nails & Waxing!</h3>
                <p>Professional manicure and waxing services for a refreshed look.</p>
            </div>
        </div>
    </section>

    <section id="testimonials">
        <div class="container">
            <h2>Testimonials</h2>
            <div class="testimonial">
                <p>"Just like every other girl, I love spending time doing my nails and looking stylish. So glad I found this place!"</p>
                <span>- Jane Doe</span>
            </div>
        </div>
    </section>

    <footer class="footer">
        <h2>Get All Special Offers!</h2>
        <form action="submit.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
        <p>&copy; 2024 Polish Nails. All rights reserved.</p>
    </footer>
</body>
</html>
