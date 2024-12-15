<?php
require_once '../../setup_files/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_POST['id'])) {
            throw new Exception('El ID del empleado es obligatorio');
        }

        $sql = "DELETE FROM employees WHERE id_employee = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 