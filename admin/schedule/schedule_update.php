<?php
session_start();
require_once '../setup_files/connection.php';

// Verificar si el usuario está autenticado como admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    // Validar y recoger los datos del formulario
    $scheduleId = $_POST['id_workSchedule'] ?? '';
    $employeeId = $_POST['id_employee'] ?? '';
    $dayOfWeek = $_POST['dayOfWeek'] ?? '';
    $blockType = $_POST['blockType'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';

    // Validaciones básicas
    if (empty($scheduleId) || empty($employeeId) || empty($dayOfWeek) || empty($blockType) || empty($startTime) || empty($endTime)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Validar que la hora de fin sea posterior a la hora de inicio
    if (strtotime($endTime) <= strtotime($startTime)) {
        throw new Exception('La hora de fin debe ser posterior a la hora de inicio');
    }

    // Actualizar horario
    $stmt = $pdo->prepare("
        UPDATE workSchedules 
        SET dayOfWeek = ?,
            blockType = ?,
            startTime = ?,
            endTime = ?
        WHERE id_workSchedule = ? AND id_employee = ?
    ");

    $stmt->execute([$dayOfWeek, $blockType, $startTime, $endTime, $scheduleId, $employeeId]);

    echo json_encode([
        'success' => true,
        'message' => 'Horario actualizado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 