<?php
// Definir la ruta base del proyecto
define('BASE_PATH', __DIR__ . '/');

// Incluir archivo de configuración inicial
include_once BASE_PATH . 'init.php';

// Cargar las traducciones
$lang = include BASE_PATH . "languages/{$_SESSION['lang']}.php";

// Función para obtener las traducciones con valores predeterminados
function translate($key, $default = '') {
    global $lang;
    return $lang[$key] ?? $default;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David, Maribel García, Fernanda Montalvan, Cristian Gómez">
    <meta name="description" content="Vietnam Nails">
    <meta name="keywords" content="nail salon, manicure, pedicure, Salon uñas Badalona">
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
    <!-- Encabezado del sitio -->
    <header class="header">
        <div class="container">
            <h1><?php echo translate('welcome_message', 'Bienvenido a Vietnam Nails Echegaray'); ?></h1>
            <nav>
                <ul>
                    <li><a href="#"><?php echo translate('home', 'Inicio'); ?></a></li>
                    <li><a href="public/Webpages/servicios.php"><?php echo translate('services', 'Servicios'); ?></a></li>
                    <li><a href="public/Webpages/reservas.php"><?php echo translate('reservations', 'Reservas'); ?></a></li>
                    <li><a href="public/Webpages/ofertas.php"><?php echo translate('offers', 'Ofertas'); ?></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Sección de bienvenida -->
    <main>
        <section id="home">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2><?php echo translate('home_highlight', 'Decenas de colores ¡Pintura de uñas Orgánica!'); ?></h2>
                        <p><?php echo translate('home_description', 'Con una obsesión por los momentos, servicio personalizado, y muchos colores.'); ?></p>
                        <a href="public/Webpages/reservas.php" class="btn btn-primary"><?php echo translate('book_now', 'Reserva ahora'); ?></a>
                    </div>
                    <div class="col-md-6">
                        <img src="public/Resources/img/unas/portrait_nails/nail_face2.jpg" alt="Nail Polish" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección sobre nosotros -->
        <section id="about">
            <div class="container">
                <h2><?php echo translate('about_us', 'Nuestro trabajo...'); ?></h2>
                <p><?php echo translate('about_quote', 'Cada año ayudamos a que las mujeres de Barcelona se vean y se sientan mejor.'); ?></p>
                <a href="#more-about" class="btn btn-secondary"><?php echo translate('read_more', 'Leer más'); ?></a>

                <!-- Carrusel de imágenes -->
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="public/Resources/img/unas/hand_nails/unas_gel.jpg" class="d-block w-100" alt="Uñas de gel">
                        </div>
                        <div class="carousel-item">
                            <img src="public/Resources/img/unas/hand_nails/halloween_nails.jpg" class="d-block w-100" alt="Uñas de Halloween">
                        </div>
                        <div class="carousel-item">
                            <img src="public/Resources/img/unas/hand_nails/green_nails.jpg" class="d-block w-100" alt="Uñas verdes">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only"><?php echo translate('previous', 'Anterior'); ?></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only"><?php echo translate('next', 'Siguiente'); ?></span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Sección de testimonios -->
        <section id="testimonials">
            <div class="container">
                <h2><?php echo translate('testimonials', 'Testimonios'); ?></h2>
                <blockquote>
                    <p>"Just like every other girl, I love spending time doing my nails and looking stylish. So glad I found this place!"</p>
                    <footer>- Jane Doe</footer>
                </blockquote>
            </div>
        </section>
    </main>

    <!-- Pie de página -->
    <footer class="footer text-center">
        <div class="container">
            <h2><?php echo translate('special_offers', '¡Obtén todas las ofertas especiales!'); ?></h2>
            <form action="submit.php" method="POST">
                <input type="email" name="email" placeholder="<?php echo translate('enter_email', 'Introduce tu correo'); ?>" required>
                <button type="submit" class="btn btn-success"><?php echo translate('subscribe', 'Suscribirse'); ?></button>
            </form>
            <p>&copy; 2024 Vietnam Nails Echegaray. <?php echo translate('rights_reserved', 'Todos los derechos reservados.'); ?></p>
        </div>
    </footer>
</body>
</html>
