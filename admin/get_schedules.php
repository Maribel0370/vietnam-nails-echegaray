<?php
session_start();
require_once '../setup_files/connection.php';

// Debug
error_log("Solicitud recibida en get_schedules.php");
error_log("SESSION: " . print_r($_SESSION, true));
error_log("GET: " . print_r($_GET, true));

header('Content-Type: application/json');

// Verificar si el usuario está autenticado como admin
if (!isset($_SESSION['admin_id'])) {
    error_log("Usuario no autorizado");
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    error_log("ID de empleado no proporcionado");
    echo json_encode(['error' => 'ID de empleado no proporcionado']);
    exit();
}

try {
    error_log("Intentando obtener horarios para empleado ID: " . $_GET['employee_id']);
    
    $stmt = $pdo->prepare("
        SELECT ws.*, e.firstName, e.lastName 
        FROM workSchedules ws 
        JOIN employees e ON ws.id_employee = e.id_employee 
        WHERE ws.isActive = 1 
        AND ws.id_employee = ?
        ORDER BY FIELD(ws.dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        ws.startTime
    ");
    
    $stmt->execute([$_GET['employee_id']]);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Query ejecutada. Resultados: " . print_r($schedules, true));
    
    echo json_encode($schedules);
    
} catch (PDOException $e) {
    error_log("Error en get_schedules.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode(['error' => 'Error al cargar los horarios: ' . $e->getMessage()]);
} 