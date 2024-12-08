<?php
// Iniciar la sesión solo si no está iniciada previamente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Incluir el archivo que contiene la función translate
include_once __DIR__ . '/init.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="public/resources/img/icons/favicon_vne.png" type="image/x-icon"> <!-- Añadir favicon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="public/resources/css/styles.css"> <!-- Archivo de estilos -->
    <title>Vietnam Nails Echegaray</title> <!-- Updated title -->
</head>
<body>

    <header class="header" id="header">
        <img src="public/Resources/img/rotulo_transparente.png" alt="Vietnam Nails Echegaray" class="header-logo-rotulo">
        <img src="public/Resources/img/logo_vne-modified-999.png" alt="Vietnam Nails Echegaray" class="header-logo">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
    

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#"><?php echo translate('home', 'Inicio'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="setup_files/services.php"><?php echo translate('services', 'Servicios'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="setup_files/ofertas.php"><?php echo translate('offers', 'Ofertas'); ?></a></li>
                    
                    <!-- Enlace al Modal de Reservas -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#reservationModal"><?php echo translate('reservations', 'Reservas'); ?></a>
                    </li>

                    <!-- Enlace al Modal de Ubicación -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#locationModal"><?php echo translate('location', 'Ubicación'); ?></a>
                    </li>
                </ul>

                <!-- Dropdown de Idioma -->
                <div class="dropdown ml-auto">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                        <img src="public/Resources/img/icons/<?php echo $_SESSION['lang']; ?>.png" alt="Idioma" width="20">
                        <?php echo $_SESSION['lang'] === 'es' ? 'Esp' : ($_SESSION['lang'] === 'ca' ? 'Cat' : 'Ing'); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="?lang=es"><img src="public/Resources/img/icons/es.png" alt="Español" width="20">  Español</a>
                        <a class="dropdown-item" href="?lang=ca"><img src="public/Resources/img/icons/ca.png" alt="Catalán" width="20">  Catalá</a>
                        <a class="dropdown-item" href="?lang=en"><img src="public/Resources/img/icons/en.png" alt="Inglés" width="20">  English</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

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

    <!-- Modal de Ubicación -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-small-custom"> <!-- Aquí colocas la clase personalizada -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel"><?php echo translate('location', 'Nuestra Ubicación'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <p><?php echo translate('location_info', 'Nos encontramos en: Calle Echegaray, 15, Badalona, España'); ?></p>
                        <!-- Google Maps Embed -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2994.411775382113!2d2.2322693153855725!3d41.45011347925821!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a499b1b1f0c9f3%3A0x1d7c60e2db5c9db!2sCalle%20Echegaray%2C%2015%2C%20Badalona%2C%20Barcelona%2C%20España!5e0!3m2!1ses!2ses!4v1234567890!5m2!1ses!2ses" 
                            width="800px" 
                            height="250px" 
                            frameborder="0" 
                            style="border:0;" 
                            allowfullscreen="" 
                            aria-hidden="false" 
                            tabindex="0">
                        </iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo translate('close', 'Cerrar'); ?></button>
                </div>
            </div>
        </div>
    </div>
    
</body>
<script> 
    document.addEventListener("DOMContentLoaded", function() {
        window.onscroll = function() {
            var header = document.getElementById("header");
            var scrollPosition = window.scrollY;
            if (scrollPosition > 50) {
                header.style.backgroundColor = "rgba(206, 205, 205)"; // Change to your desired color
            } else {
                header.style.backgroundColor = "rgba(206, 205, 205, 0.7)"; // Original color
            }
        };
    });
</script>
</html>

