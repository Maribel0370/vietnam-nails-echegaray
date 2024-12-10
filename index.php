<?php  
session_start();
require_once 'setup_files/connection.php'; // Configuración de la conexión a la base de datos

// Verificar si el archivo init.php existe y actualizar la ruta si es necesario
if (file_exists(__DIR__ . '/setup_files/init.php')) {
    include_once __DIR__ . '/setup_files/init.php';
} else {
    die('El archivo init.php no se encuentra en la ruta especificada.');
}


// Verificar si la variable de sesión 'lang' está definida
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Establecer un valor predeterminado si no está definido
}

$langFile = "setup_files/languages/{$_SESSION['lang']}.php";
if (!file_exists($langFile)) {
    $langFile = "setup_files/languages/es.php"; // Cambiar a español si el archivo no existe
}
$lang = file_exists($langFile) ? include $langFile : [];

function translate($key, $default = '') {
    global $lang;
    return $lang[$key] ?? $default;
}

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'setup_files/header.php'; // Incluir el header
?>


<!-- *********************************************************************** -->

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>"> <!-- Idioma dinámico -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David Gutierrez, Maribel García, Fernanda Montalvan, Cristian Gómez">
    <meta name="description" content="Vietnam Nails">
    <meta name="keywords" content="nail salon, manicure, pedicure, Salon uñas Badalona">
    <link rel="stylesheet" href="public/Resources/css/style.css">
    <title><?php echo translate('welcome_message', 'Vietnam Nails Echegaray'); ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>  
<body>
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
                        <form action="setup_files/reservations.php" method="POST">
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
                    <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#servicesModalAbout"><?php echo translate('read_more', 'Leer más'); ?></a>
                    <!-- Carrusel de imágenes -->
                        <div class="banner">
                            <div class="slider" style="--quantity: 10">
                                <div class="item" style="--position: 1"><img src="public/Resources/img/unas/nails1.jpg" alt=""></div>
                                <div class="item" style="--position: 2"><img src="public/Resources/img/unas/nails2.jpg" alt=""></div>
                                <div class="item" style="--position: 3"><img src="public/Resources/img/unas/nails3.jpg" alt=""></div>
                                <div class="item" style="--position: 4"><img src="public/Resources/img/unas/nails4.jpg" alt=""></div>
                                <div class="item" style="--position: 5"><img src="public/Resources/img/unas/nails5.jpg" alt=""></div>
                                <div class="item" style="--position: 6"><img src="public/Resources/img/unas/nails6.jpg" alt=""></div>
                                <div class="item" style="--position: 7"><img src="public/Resources/img/unas/nails7.jpg" alt=""></div>
                                <div class="item" style="--position: 8"><img src="public/Resources/img/unas/nails8.jpg" alt=""></div>
                                <div class="item" style="--position: 9"><img src="public/Resources/img/unas/nails9.jpg" alt=""></div>
                                <div class="item" style="--position: 10"><img src="public/Resources/img/unas/nails10.jpg" alt=""></div>
                            </div>
                        </div>
                  
                </div>
            </div>
            
    </section>        

        <!-- Añadir el modal de servicios justo después de la sección about -->
        <div class="modal fade" id="servicesModalAbout" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="servicesModalLabel">Servicios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="services-container">
                            <?php
                            try {
                                $stmt = $pdo->query("SELECT nameService, description FROM services");
                                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach ($services as $service) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($service['nameService']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } catch (PDOException $e) {
                                echo "Error al obtener los servicios: " . $e->getMessage();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Sección Ubicación -->
        <section id="map">
            <div class="contenedCor_principal">
                <h2><?php echo translate('our_location', 'Ubicación');?></h2>
                <div class="infoMapContainer">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d747.7193510581494!2d2.22627066965649!3d41.441881703534875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4bb60e4593303%3A0x3607ecd8f3fc6ad2!2sCarrer%20d&#39;Echegaray%2C%2018%2C%2008914%20Santa%20Coloma%20de%20Gramenet%2C%20Barcelona!5e0!3m2!1ses!2ses!4v1732925196997!5m2!1ses!2ses" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </section>

        <section id="videoPresentacion">
            <div>
                <h2><?php echo translate('Come visit us', 'Ven a visitar nuestras instalaciones');?></h2>
                <video width="90%" height="80%" controls>
                    <source src="public/Resources/video/vietnam_nail_editado (1).mp4" type="video/mp4">
                </video>
            </div>
        </section>
    </main>

    <!-- Pie de página -->
    <?php include 'setup_files/footer.php'; // Incluir el footer ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
