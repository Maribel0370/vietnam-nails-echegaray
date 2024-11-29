<?php  
session_start();
require_once 'setup_files/connection.php';
include_once 'setup_files/init.php';

$langFile = "setup_files/languages/{$_SESSION['lang']}.php";
if (!file_exists($langFile)) {
    $langFile = "setup_files/languages/en.php";
}
$lang = file_exists($langFile) ? include $langFile : [];

function translate($key, $default = '') {
    global $lang;
    return $lang[$key] ?? $default;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<!-- *********************************************************************** -->

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
        <h1>Vietnam Nails Echegaray</h1>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><?php echo translate('home', 'Inicio'); ?></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="setup_files/services.php"><?php echo translate('services', 'Servicios'); ?></a></li>
                    <!-- Aquí el enlace para abrir el modal de reservas -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#reservationModal"><?php echo translate('reservations', 'Reservas'); ?></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="setup_files/ofertas.php"><?php echo translate('offers', 'Ofertas'); ?></a></li>
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
                            <img src="public/Resources/img/icons/ca.png" alt="Catalán" width="20"> Català
                        </a>
                        <a class="dropdown-item" href="?lang=en">
                            <img src="public/Resources/img/icons/en.png" alt="Inglés" width="20"> English
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <!-- Sección de bienvenida -->
    <main>
        <section id="home">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        
                        <h2><?php echo translate('home_highlight', 'Decenas de colores ¡Pintura de uñas Orgánica!'); ?></h2>
                        <p><?php echo translate('home_description', 'Con una obsesión por los momentos, servicio personalizado, y muchos colores.'); ?></p>
                        <!-- Botón para abrir el modal -->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#reservationModal"><?php echo translate('book_now', 'Reserva ahora'); ?></button>
                    </div>
                    
                    <img src="public/Resources/img/unas/portrait_nails/nail_face2.jpg" alt="Nail Polish" class="img-fluid">
                </div>
            </div>
        </section>

        <!-- Modal de Reservas -->
        <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel"><?php echo translate('make_reservation', 'Realizar una reserva'); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="reservations.php" method="POST">
                            <!-- Campos para el nombre y móvil -->
                            <div class="form-group">
                                <label for="name"><?php echo translate('your_name', 'Tu nombre'); ?></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php echo translate('your_phone', 'Tu teléfono'); ?></label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <!-- Campo para seleccionar el servicio -->
                            <div class="form-group">
                                <label for="service"><?php echo translate('select_service', 'Selecciona el servicio'); ?></label>
                                <select class="form-control" id="service" name="service" required>
                                    <option value="gel"><?php echo translate('gel_nails', 'Uñas gel'); ?></option>
                                    <option value="semi-capa"><?php echo translate('semi_capa', 'Semi Capa'); ?></option>
                                    <option value="semi-permanente"><?php echo translate('semi_permanent', 'Semi permanente'); ?></option>
                                    <option value="manicura"><?php echo translate('manicure', 'Manicura'); ?></option>
                                    <option value="pedicura"><?php echo translate('pedicure', 'Pedicura'); ?></option>
                                    <option value="masajes"><?php echo translate('massages', 'Masajes'); ?></option>
                                </select>
                            </div>
                            <!-- Campo para seleccionar el personal -->
                            <div class="form-group">
                                <label for="staff"><?php echo translate('select_staff', 'Selecciona al personal'); ?></label>
                                <select class="form-control" id="staff" name="staff" required>
                                    <option value="Georgina"><?php echo translate('georgina', 'Georgina'); ?></option>
                                    <option value="Yulia"><?php echo translate('yulia', 'Yulia'); ?></option>
                                    <option value="Heip"><?php echo translate('heip', 'Heip'); ?></option>
                                    <option value="Sin Preferencia"><?php echo translate('no_preference', 'Sin Preferencia'); ?></option>
                                    <option value="Sin Preferencia"><?php echo translate('no_preference', 'Sin Preferencia'); ?></option>
                                </select>
                            </div>
                            <!-- Campo para seleccionar la fecha -->
                            <div class="form-group">
                                <label for="date"><?php echo translate('select_date', 'Selecciona la fecha'); ?></label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <!-- Campo para seleccionar la hora -->
                            <div class="form-group">
                                <label for="time"><?php echo translate('select_time', 'Selecciona la hora'); ?></label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo translate('book_now', 'Reservar ahora'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección sobre nosotros -->
        <section id="about">
            <div class="container">
                <div>
                    <h2><?php echo translate('about_us', 'Nuestro trabajo...'); ?></h2>
                    <p><?php echo translate('about_quote', 'Cada año ayudamos a que las mujeres de Barcelona se vean y se sientan mejor.'); ?></p>
                    <a href="#more-about" class="btn btn-secondary"><?php echo translate('read_more', 'Leer más'); ?></a>
                </div>

                <!-- Carrusel de imágenes -->
                <div id="carrousel">
                    <div class="content-all">
                        <div class="content-carrousel">
                        <figure><img src="public/Resources/img/unas/hand_nails/unas_gel.jpg" alt="uñas de gel"></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/halloween_nails.jpg" alt="uñas con diseño de Halloween"></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/green_nails.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/nails3.jpg" alt="Uñas de color violeta"></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/nails4.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/nails5.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/nails6.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/nails7.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/silver_nails.jpg" alt="..."></figure>
                        <figure><img src="public/Resources/img/unas/hand_nails/portrait_nails.jpg" alt="..."></figure>
                        </div>
                    </div> 
                </div>
            </div>
        </section>

          <!-- Sección de testimonios -->
          <section id="testimonials">
            <div class="container">
                <h2><?php echo translate('testimonials', 'Testimonios'); ?></h2>
                <blockquote>
                    <p>"<?php echo translate('testimonial_quote', 'Just like every other time, an amazing experience! The quality of the service, as well as the staff, is unbeatable!'); ?>"</p>
                    <p><?php echo translate('testimonial_author', '- Angela'); ?></p>
                </blockquote>
            </div>
        </section>
    </main>

    <!-- Pie de página -->
    <footer>
            <div>
                <h2><?php echo translate('special_offers', '¡Obtén todas las ofertas especiales!'); ?></h2>
                <form action="#" method="POST">
                    <input type="email" placeholder="<?php echo translate('enter_email', 'Introduce tu correo'); ?>" class="form-control" name="email" required>
                    <button type="submit" class="btn btn-primary"><?php echo translate('subscribe', 'Suscribirse'); ?></button>
                </form>
            </div>
            <p>&copy; 2024 Vietnam Nails Echegaray. <?php echo translate('rights_reserved', 'Todos los derechos reservados.'); ?></p>
    </footer>



    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
