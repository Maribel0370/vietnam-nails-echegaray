<?php
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['employeeId']) || !isset($data['date']) || !isset($data['time'])) {
        throw new Exception('Datos incompletos');
    }

    $database = Database::getInstance();
    $conn = $database->getConnection();

    $reservationDateTime = date(format: 'Y-m-d H:i:s', strtotime($data['date'] . ' ' . $data['time']));

    // Verificar si ya existe una reserva para el empleado en la fecha y hora especificada
    $sql = 'SELECT COUNT(*) AS count FROM reservations
            WHERE employee_id = ?
            AND reservationDate = ?
            AND status != "cancelled"';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $data['employeeId'], $reservationDateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode([
        'available' => ($row['count'] === 0),
        'error' => false
    ]);

} catch (Exception $e) {
    echo json_encode([
        'available' => false,
        'error' => true,
        'message' => $e->getMessage()
    ]);
}