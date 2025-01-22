<?php
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['employeeId']) || !isset($data['date']) || !isset($data['time'])) {
        throw new Exception('Datos incompletos');
    }

    $reservationDateTime = date('Y-m-d H:i:s', strtotime($data['date'] . ' ' . $data['time']));

    // Verificar si ya existe una reserva para el empleado en la fecha y hora especificada
    $sql = 'SELECT COUNT(*) AS count FROM reservations 
            WHERE employee_id = :employee_id 
            AND reservationDate = :reservation_date 
            AND status != :status';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':employee_id' => $data['employeeId'],
        ':reservation_date' => $reservationDateTime,
        ':status' => 'cancelled'
    ]);
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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