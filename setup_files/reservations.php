<?php
// Conexion a la base de datos
require_once 'setup_files/connection.php'; // Asegúrate de que la ruta es correcta

// Definir horarios del staff
$staff_schedule = [
    'Georgina' => [
        'monday' => ['10:00-13:00', '15:30-20:15'],
        'tuesday' => ['10:00-13:00', '15:30-20:15'],
        'wednesday' => ['10:00-13:00', '15:30-20:15'],
        'thursday' => ['10:00-13:00', '15:30-20:15'],
        'friday' => ['10:00-13:00', '15:30-20:15']
    ],
    'Yulia' => [
        'monday' => ['09:30-16:00'],
        'tuesday' => ['09:30-16:00'],
        'wednesday' => ['09:30-16:00'],
        'thursday' => ['09:30-19:30'],
        'friday' => ['09:30-16:00']
    ],
    'Heip' => [
        'monday' => ['09:30-20:00'],
        'tuesday' => ['09:30-20:00'],
        'wednesday' => ['09:30-20:00'],
        'thursday' => ['09:30-20:00'],
        'friday' => ['09:30-20:00']
    ],
    'Sin Preferencia' => [
        'monday' => ['09:30-20:00'],
        'tuesday' => ['09:30-20:00'],
        'wednesday' => ['09:30-20:00'],
        'thursday' => ['09:30-20:00'],
        'friday' => ['09:30-20:00']
    ],
];

// Fechas excepcionales
$special_dates = ['2024-12-14', '2024-12-21', '2024-12-28', '2025-01-04'];
$special_hours = '09:30-14:30';

// Función para verificar si una fecha es especial
function is_special_date($date) {
    global $special_dates;
    return in_array($date, $special_dates);
}

// Función para obtener las horas disponibles según el staff y la fecha
function get_available_hours($staff, $date) {
    global $staff_schedule, $special_hours;

    $day_of_week = strtolower(date('l', strtotime($date))); // Obtiene el día de la semana
    if (is_special_date($date)) {
        return explode('-', $special_hours);
    } elseif (isset($staff_schedule[$staff][$day_of_week])) {
        return $staff_schedule[$staff][$day_of_week];
    }
    return [];
}

// Obtener las horas disponibles para el personal seleccionado y la fecha seleccionada
$available_hours = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff = $_POST['staff'] ?? '';
    $date = $_POST['date'] ?? '';
    
    if ($staff && $date) {
        $available_hours = get_available_hours($staff, $date);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
    <link rel="stylesheet" href="../styles/reservations.css">
</head>
<body>
    <div class="reservation-container">
        <h1>Reservar Cita</h1>
        <form action="reservations.php" method="POST">
            <label for="service">Servicio:</label>
            <select name="service" id="service" required>
                <option value="gel">Uñas gel</option>
                <option value="semi-capa">Semi Capa</option>
                <option value="semi-permanente">Semi permanente</option>
                <option value="manicura">Manicura</option>
                <option value="pedicura">Pedicura</option>
                <option value="masajes">Masajes</option>
            </select>

            <label for="staff">Elige a tu esteticista:</label>
            <select name="staff" id="staff" required>
                <option value="Georgina">Georgina</option>
                <option value="Yulia">Yulia</option>
                <option value="Heip">Heip</option>
                <option value="Sin_Preferencia">Sin Preferencia</option>
            </select>

            <label for="date">Fecha:</label>
            <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" required>

            <label for="time">Hora:</label>
            <select name="time" id="time" required>
                <?php
                if (!empty($available_hours)) {
                    foreach ($available_hours as $time) {
                        echo "<option value=\"$time\">$time</option>";
                    }
                } else {
                    echo "<option value=\"\">Selecciona una fecha y un esteticista para ver las horas disponibles</option>";
                }
                ?>
            </select>

            <label for="customer_name">Nombre del Cliente:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="phone_number">Número de Teléfono:</label>
            <input type="tel" id="phone_number" name="phone_number" required>

            <button type="submit">Reservar</button>
        </form>
    </div>
</body>
</html>
