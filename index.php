<?php 
define('BASE_PATH', __DIR__ . '/');
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
<html lang="<?php echo $_SESSION['lang']; ?>"> <!-- Idioma dinámico -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David, Maribel García, Fernanda Montalvan, Cristian Gómez">
    <meta name="description" content="Vietnam Nails">
    <meta name="keywords" content="nail salon, manicure, pedicure, Salon uñas Badalona">
    <link rel="stylesheet" href="public/Resources/css/style.css">
    <title><?php echo translate('welcome_message', 'Vietnam Nails Echegaray'); ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Encabezado del sitio -->
    <header class="header">
        <div class="container">
            <h1><?php echo translate('welcome_message', 'Bienvenido a Vietnam Nails Echegaray'); ?></h1>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#"><?php echo translate('home', 'Inicio'); ?></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="public/Webpages/servicios.php"><?php echo translate('services', 'Servicios'); ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="public/Webpages/reservas.php"><?php echo translate('reservations', 'Reservas'); ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="public/Webpages/ofertas.php"><?php echo translate('offers', 'Ofertas'); ?></a></li>
                    </ul>
                    <!-- Dropdown de idioma -->
                    <div class="dropdown ml-auto">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <img src="public/Resources/img/icons/<?php echo $_SESSION['lang']; ?>.png" alt="Idioma" width="20">
                            <?php 
                            // Nombre del idioma actual
                            echo $_SESSION['lang'] === 'es' ? 'Español' : ($_SESSION['lang'] === 'ca' ? 'Catalán' : 'Inglés'); 
                            ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="?lang=es">
                                <img src="public/Resources/img/icons/es.png" alt="Español" width="20"> Español
                            </a>
                            <a class="dropdown-item" href="?lang=ca">
                                <img src="public/Resources/img/icons/ca.png" alt="Catalán" width="20"> Catalán
                            </a>
                            <a class="dropdown-item" href="?lang=en">
                                <img src="public/Resources/img/icons/en.png" alt="Inglés" width="20"> Inglés
                            </a>
                        </div>
                    </div>
                </div>
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

        <!-- !-- Sección de testimonios -->
        <section id="testimonials">
          <div class="container">
            <h2><?php echo translate('testimonials', 'Testimonis'); ?></h2>
              <blockquote>
               <p>"<?php echo translate('testimonial_quote', 'Just like every other time, an amazing experience! The quality of the service, as well as the staff, is unbeatable!'); ?>"</p>
              <footer><?php echo translate('testimonial_author', '- Angela'); ?></footer>
             </blockquote>
             </div>
        </section>

        <!-- Sección de suscripción -->
        <section id="subscribe">
            <div class="container">
                <h2><?php echo translate('special_offers', '¡Obtén todas las ofertas especiales!'); ?></h2>
                <form action="#" method="POST">
                    <input type="email" placeholder="<?php echo translate('enter_email', 'Introduce tu correo'); ?>" class="form-control" name="email" required>
                    <button type="submit" class="btn btn-primary"><?php echo translate('subscribe', 'Suscribirse'); ?></button>
                </form>
            </div>
        </section>
    </main>

    <!-- Pie de página -->
    <footer>
        <div class="container">
            <p><?php echo translate('rights_reserved', 'Todos los derechos reservados.'); ?></p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
