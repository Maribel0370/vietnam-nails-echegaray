<?php
require_once '../../setup_files/connection.php';

if (isset($_GET['employee_id'])) {
    $employeeId = $_GET['employee_id'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT ws.*, e.firstName, e.lastName
            FROM workSchedules ws
            JOIN employees e ON ws.id_employee = e.id_employee
            WHERE ws.id_employee = ? AND ws.isActive = 1
            ORDER BY FIELD(ws.dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                     ws.startTime
        ");
        $stmt->execute([$employeeId]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($schedules);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener los horarios: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de empleado no proporcionado']);
} 