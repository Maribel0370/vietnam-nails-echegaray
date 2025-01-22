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
 <?php
 // Detectar si estamos en la sección de admin
 $isAdmin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
 $basePath = $isAdmin ? '../' : '';
 ?>
    <link rel="icon" href="/public/Resources/img/icons/favicon_vne.png?v=1.1" type="image/x-icon"> <!-- Añadir favicon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="public/Resources/css/style.css?v=1.1"> <!-- Archivo de estilos -->
    <title>Vietnam Nails Echegaray</title> <!-- Updated title -->
</head>
<body>

    <header class="header" id="header">
        <img src="<?php echo $basePath; ?>public/Resources/img/rotulo_transparente.png" alt="Vietnam Nails Echegaray" class="header-logo-rotulo">
        <img src="<?php echo $basePath; ?>public/Resources/img/logo_vne-modified-999.png" alt="Logo Vietnam Nails Echegaray" class="header-logo">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
    

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?php echo $isAdmin ? '../': ''; ?>#"><?php echo translate('home', 'Inicio'); ?></a></li>
                    <!-- Enlace al Modal de Servicios -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#servicesModal"><?php echo translate('services', 'Servicios'); ?></a></li>
                    
                        <!-- Enlace al Modal de Ofertas -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#offersModal"><?php echo translate('offers', 'Ofertas'); ?></a></li>
                    
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
                        <img src="<?php echo $basePath; ?> public/Resources/img/icons/<?php echo $_SESSION['lang']; ?>.png" alt="Idioma" width="20">
                        <?php echo $_SESSION['lang'] === 'es' ? 'Esp' : ($_SESSION['lang'] === 'ca' ? 'Cat' : 'Ing'); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="?lang=es"><img src="<?php echo $basePath; ?>public/Resources/img/icons/es.png" alt="Español" width="20">  Español</a>
                        <a class="dropdown-item" href="?lang=ca"><img src="<?php echo $basePath; ?>public/Resources/img/icons/ca.png" alt="Catalán" width="20">  Catalá</a>
                        <a class="dropdown-item" href="?lang=en"><img src="<?php echo $basePath; ?>public/Resources/img/icons/en.png" alt="Inglés" width="20">  English</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="admin-access">
            <?php if(isset($_SESSION['admin_id'])): ?>
                <!-- Icono de logout cuando está logueado -->
                <a href="/admin/logout.php" title="Cerrar sesión" class="admin-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </a>
            <?php else: ?>
                <!-- Icono de login cuando no está logueado -->
                <a href="admin/login.php" title="Acceso administrador" class="admin-icon" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Modal de Servicios -->
    <div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
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
                            // Asegúrate de que la conexión a la base de datos esté disponible
                            global $pdo;
                            if (!isset($pdo)) {
                                require_once 'init.php';
                            }
                            
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

    <!-- Modal de Ofertas -->
    <div class="modal fade" id="offersModal" tabindex="-1" aria-labelledby="offersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offersModalLabel"><?php echo translate('offers', 'Ofertas'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="offers-container">
                        <?php
                        try {
                            // Consulta para obtener ofertas activas
                            $stmt = $pdo->prepare("
                                SELECT o.*, GROUP_CONCAT(s.nameService SEPARATOR ', ') as services
                                FROM offers o
                                LEFT JOIN offer_services os ON o.id_offer = os.id_offer
                                LEFT JOIN services s ON os.id_service = s.id_service
                                WHERE o.is_active = 1 AND o.end_date >= CURDATE()
                                GROUP BY o.id_offer
                            ");
                            $stmt->execute();
                            $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($offers) > 0) {
                                foreach ($offers as $offer) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card offer-card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($offer['title']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($offer['description']); ?></p>
                                                <p class="card-text">
                                                    <strong>Precio:</strong> <?php echo number_format($offer['final_price'], 2); ?>€<br>
                                                    <strong>Servicios:</strong> <?php echo htmlspecialchars($offer['services']); ?><br>
                                                    <strong>Válido hasta:</strong> <?php echo date('d/m/Y', strtotime($offer['end_date'])); ?><br>
                                                    <strong>Tiempo restante:</strong> 
<div class="offer-timer-container">
    <div class="offer-timer-item">
        <span id="days_<?php echo $offer['id_offer']; ?>">00</span>
        <p>Días</p>
    </div>
    <div class="offer-timer-item">
        <span id="hours_<?php echo $offer['id_offer']; ?>">00</span>
        <p>Horas</p>
    </div>
    <div class="offer-timer-item">
        <span id="minutes_<?php echo $offer['id_offer']; ?>">00</span>
        <p>Minutos</p>
    </div>
    <div class="offer-timer-item">
        <span id="seconds_<?php echo $offer['id_offer']; ?>">00</span>
        <p>Segundos</p>
    </div>
</div>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-12 text-center">
                                    <p class="alert alert-info"><?php echo translate('no_offers_available', 'Lo sentimos, en estos momentos no hay ofertas disponibles.'); ?></p>
                                </div>
                                <?php
                            }
                        } catch (PDOException $e) {
                            echo "Error al obtener las ofertas: " . $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

     

    <!-- Modal de Reservas -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- anterior aviso de reserva 
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel"><?php echo translate('make_reservation', 'Realizar una reserva'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">
                        <strong>¡Estamos trabajando para ti!</strong><br>
                        El sistema de reservas estará disponible muy pronto. Mientras tanto, puedes contactarnos a través de WhatsApp.
                    </p>
                    <a href="https://wa.me/34608268978" target="_blank" class="btn whatsapp-btn d-flex align-items-center justify-content-center">
                    <img src="../public/Resources/img/icons/whatsapp.png" alt="WhatsApp" style="width: 24px; height: 24px; margin-right: 8px;">
                    Contactar por WhatsApp
                    </a>
                </div>
                -->
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel"><?php echo translate('make_reservation', 'Reservar servicios'); ?></h5>
                    <button type="button" id="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reservationForm" novalidate>
                        <!-- Datos personales -->
                        <div class="personal-info">
                            <div class="personal-info-row">
                                <!-- Nombre del cliente -->
                                <div class="form-group">
                                    <label for="name">Nombre completo:</label>
                                    <input type="text" id="name" name="name" placeholder="Tú nombre" required>
                                </div>
                                <!-- Teléfono del cliente -->
                                <div class="form-group">
                                    <label for="phone">Teléfono:</label>
                                    <input type="tel" id="phone" name="phone" placeholder="Tú número móvil" required>
                                </div>
                            </div>
                        </div>
                        <!-- Servicios -->
                        <div id="servicesContainer">
                            <div class="service-group" data-service-id="1">
                                <h5>Servicio 1</h5>
                                <!-- Fecha y hora del servicio -->
                                <div class="date-time-container">
                                    <div class="calendar-wrapper">
                                        <input type="date" id="calendar_1" name="calendar_1" required
                                            lang="es"
                                            data-date-format="DD/MM/YYYY"
                                            style="display: none;">
                                        <div id="calendarContainer_1" class="calendar-container"></div>
                                    </div>
                                    <div class="time-wrapper">
                                        <label>Horarios disponibles:</label>
                                        <div id="timeSelector_1" class="time-selector">
                                            <!-- Aquí se añadirán los horarios disponibles dinámicamente -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Servicios y Empleados -->
                                <div class="service-employee-container">
                                    <div class="form-group">
                                        <label for="service_1">Servicio:</label>
                                        <select id="service_1" name="service_1" required>
                                            <option value="">Selecciona un servicio</option>
                                            <!-- Aquí se añadirán los servicios dinámicamente -->
                                            <?php
                                            $sql = "SELECT id_service, nameService FROM services WHERE isActive = 1";
                                            $stmt = $pdo->query($sql);
                                            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            if($services && count($services) > 0) {
                                                foreach ($services as $row) {
                                                    echo "<option value='" . $row['id_service'] . "'>" . htmlspecialchars($row['nameService']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="employee_1">Empleado:</label>
                                        <select id="employee_1" name="employee_1" required>
                                            <option value="">Seleccione un empleado</option>
                                            <!-- Aquí se añadirán los empleados dinámicamente -->
                                            <?php
                                            $sql = "SELECT id_employee, firstName, lastName FROM employees WHERE isActive = 1";
                                            $stmt = $pdo->query($sql);
                                            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            if($employees && count($employees) > 0) {
                                                foreach ($employees as $row) {
                                                    echo "<option value='" . $row['id_employee'] . "'>" . htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenedor de botones -->
                        <div class="buttons-container">
                            <div class="left-buttons">
                                <button type="button" id="addService">Añadir otro servicio</button>
                                <button type="submit">Confirmar Reserva</button>
                            </div>
                            <div class="right-buttons">
                                <button type="button" id="cancelReservation">Cancelar</button>
                            </div>
                        </div>
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
                        <p><?php echo translate('location_info', 'Nos encontramos en: Calle Echegaray, 18, Badalona, España'); ?></p>
                        <!-- Google Maps Embed -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2994.411775382113!2d2.2322693153855725!3d41.45011347925821!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a499b1b1f0c9f3%3A0x1d7c60e2db5c9db!2sCalle%20Echegaray%2C%2018%2C%20Badalona%2C%20Barcelona%2C%20España!5e0!3m2!1ses!2ses!4v1234567890!5m2!1ses!2ses" 
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
<script>
// Obtener el modal
var modal = document.getElementById("servicesModal");

// Obtener el botón que abre el modal
var btn = document.querySelector("a[href='#'][data-target='#servicesModal']");

// Obtener el elemento <span> que cierra el modal
var span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abrir el modal
btn.onclick = function(e) {
    e.preventDefault();
    modal.style.display = "block";
}

// Cuando el usuario hace clic en <span> (x), cerrar el modal
span.onclick = function() {
    modal.style.display = "none";
}

// Cuando el usuario hace clic fuera del modal, cerrarlo
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<script>
    // Función para iniciar el contador
    document.addEventListener('DOMContentLoaded', function () {
    const timers = document.querySelectorAll('.offer-timer-container');

    timers.forEach(timer => {
        const offerId = timer.querySelector('span').id.split('_')[1];
        const endDate = new Date(<?php echo json_encode($offer['end_date']); ?>);

        function updateCountdown() {
            const now = new Date();
            const timeLeft = endDate - now;

            if (timeLeft <= 0) {
                timer.innerHTML = "Oferta finalizada";
                return;
            }

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            document.getElementById(`days_${offerId}`).textContent = days.toString().padStart(2, '0');
            document.getElementById(`hours_${offerId}`).textContent = hours.toString().padStart(2, '0');
            document.getElementById(`minutes_${offerId}`).textContent = minutes.toString().padStart(2, '0');
            document.getElementById(`seconds_${offerId}`).textContent = seconds.toString().padStart(2, '0');
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    });
});

    // Llamar a la función para cada oferta
    <?php foreach ($offers as $offer): ?>
        startOfferCountdown('<?php echo $offer['end_date']; ?>', 'offerTimer_<?php echo $offer['id_offer']; ?>');
    <?php endforeach; ?>
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="<?php echo $basePath; ?>public/javascript/modalReservas.js"></script>
</body>

</html>

