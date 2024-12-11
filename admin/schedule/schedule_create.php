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
    $employeeId = $_POST['id_employee'] ?? '';
    $dayOfWeek = $_POST['dayOfWeek'] ?? '';
    $blockType = $_POST['blockType'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';

    // Validaciones básicas
    if (empty($employeeId) || empty($dayOfWeek) || empty($blockType) || empty($startTime) || empty($endTime)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Validar que la hora de fin sea posterior a la hora de inicio
    if (strtotime($endTime) <= strtotime($startTime)) {
        throw new Exception('La hora de fin debe ser posterior a la hora de inicio');
    }

    // Insertar nuevo horario
    $stmt = $pdo->prepare("
        INSERT INTO workSchedules (id_employee, dayOfWeek, blockType, startTime, endTime, isActive) 
        VALUES (?, ?, ?, ?, ?, 1)
    ");

    $stmt->execute([$employeeId, $dayOfWeek, $blockType, $startTime, $endTime]);

    echo json_encode([
        'success' => true,
        'message' => 'Horario añadido correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 