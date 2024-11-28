<?php
session_start();
require_once '../../connection.php';  // Subir dos niveles para acceder a la raíz

// Cargar archivo de traducción
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'es'; // Por defecto español
require_once '../../languages/' . $lang . '.php'; // Cargar las traducciones

// Función para traducir las palabras
function translate($key, $default = '') {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $default;
}

// Procesar reserva
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $service_id = $_POST['service'];
    $staff_id = $_POST['staff'] ?? null;
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];

    // Validación de campos
    if (empty($name) || empty($phone) || empty($service_id) || empty($reservation_date) || empty($reservation_time)) {
        $errorMessage = translate('error_fields_required', 'Todos los campos son obligatorios.');
    } else {
        // Verificar disponibilidad del personal
        if ($staff_id) {
            $stmt = $pdo->prepare("SELECT * FROM reservations WHERE staff_id = :staff_id AND reservation_date = :reservation_date AND reservation_time = :reservation_time");
            $stmt->execute(['staff_id' => $staff_id, 'reservation_date' => $reservation_date, 'reservation_time' => $reservation_time]);

            if ($stmt->rowCount() > 0) {
                $errorMessage = translate('error_staff_unavailable', 'El personal seleccionado no está disponible en esta hora.');
            }
        }

        // Si no hay errores, guardar la reserva
        if (empty($errorMessage)) {
            $stmt = $pdo->prepare("INSERT INTO reservations (name, phone, service_id, staff_id, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $service_id, $staff_id, $reservation_date, $reservation_time]);

            $successMessage = translate('reservation_success', 'Reserva realizada con éxito.');
        }
    }
}
?>

<!-- Mostrar mensajes de éxito o error -->
<?php if (!empty($errorMessage)): ?>
    <div class="error"><?php echo $errorMessage; ?></div>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
    <div class="success"><?php echo $successMessage; ?></div>
<?php endif; ?>
