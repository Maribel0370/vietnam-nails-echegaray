<?php 
// Incluir la conexión a la base de datos
include('connection.php');

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']));
}

// Obtener los parámetros de la solicitud POST
$date = isset($_POST['date']) ? $_POST['date'] : ''; // Fecha seleccionada
$time = isset($_POST['time']) ? $_POST['time'] : ''; // Hora seleccionada

// Validar que los parámetros no estén vacíos
if (empty($date) || empty($time)) {
    exit(json_encode(['status' => 'error', 'message' => 'Fecha y hora son requeridos']));
}

// Consulta para verificar la disponibilidad del personal
$query = "
    SELECT id, name, available_time
    FROM staff_schedule
    WHERE available_date = :date AND available_time = :time AND available = 1
";
$stmt = $pdo->prepare($query);

// Ejecutar la consulta con los parámetros
$stmt->execute([':date' => $date, ':time' => $time]);

// Verificar si hay personal disponible
$availableStaff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Responder con los resultados
if (count($availableStaff) > 0) {
    // Hay personal disponible
    echo json_encode(['status' => 'success', 'availableStaff' => $availableStaff]);
} else {
    // No hay personal disponible
    echo json_encode(['status' => 'error', 'message' => 'No hay personal disponible en el horario seleccionado.']);
}
?>
