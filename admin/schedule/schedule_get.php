<?php
session_start();
require_once '../setup_files/connection.php';

// Verificar si el usuario está autenticado como admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    $employeeId = $_GET['id_employee'] ?? '';

    if (empty($employeeId)) {
        throw new Exception('ID de empleado no válido');
    }

    $stmt = $pdo->prepare("
        SELECT ws.*, e.firstName, e.lastName 
        FROM workSchedules ws
        JOIN employees e ON ws.id_employee = e.id_employee
        WHERE ws.id_employee = ? AND ws.isActive = 1
        ORDER BY 
            CASE ws.dayOfWeek
                WHEN 'Monday' THEN 1
                WHEN 'Tuesday' THEN 2
                WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4
                WHEN 'Friday' THEN 5
                WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7
            END,
            ws.startTime
    ");

    $stmt->execute([$employeeId]);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'schedules' => $schedules
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 