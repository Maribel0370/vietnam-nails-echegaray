<?php
session_start();
require_once '../setup_files/connection.php';

// Verificar si el usuario está autenticado como admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    // Obtener datos de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    $employeeId = $data['id'] ?? '';

    if (empty($employeeId)) {
        throw new Exception('ID de empleado no válido');
    }

    // Reactivar empleado
    $stmt = $pdo->prepare("
        UPDATE employees 
        SET isActive = 1
        WHERE id_employee = ?
    ");

    $stmt->execute([$employeeId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('No se encontró el empleado especificado');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Personal reactivado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 