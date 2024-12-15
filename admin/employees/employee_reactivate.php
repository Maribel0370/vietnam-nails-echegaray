<?php
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../setup_files/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_POST['id'])) {
            throw new Exception('El ID del empleado es obligatorio');
        }

        // Reactivar empleado
        $sql = "UPDATE employees SET isActive = 1 WHERE id_employee = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 