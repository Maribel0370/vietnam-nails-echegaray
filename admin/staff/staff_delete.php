<?php
error_log("=== Inicio de eliminación de empleado ===");
session_start();
require_once '../setup_files/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    error_log("No autorizado");
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    error_log("Datos recibidos: " . print_r($data, true));
    
    $staffId = $data['id'] ?? '';
    error_log("ID de empleado a eliminar: " . $staffId);

    if (empty($staffId)) {
        throw new Exception('ID de empleado no proporcionado');
    }

    $stmt = $pdo->prepare("UPDATE employees SET isActive = 0 WHERE id_employee = ?");
    $stmt->execute([$staffId]);
    error_log("Query ejecutada. Filas afectadas: " . $stmt->rowCount());

    if ($stmt->rowCount() === 0) {
        throw new Exception('No se encontró el empleado especificado');
    }

    echo json_encode(['success' => true]);
    error_log("Empleado desactivado correctamente");

} catch (Exception $e) {
    error_log("Error en staff_delete.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
error_log("=== Fin de eliminación de empleado ==="); 