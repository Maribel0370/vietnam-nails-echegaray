<?php
require_once '../../setup_files/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validación de campos obligatorios
        if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['phone'])) {
            throw new Exception('Todos los campos obligatorios deben estar completos');
        }

        // Validación del teléfono
        if (!preg_match('/^[0-9]{9}$/', $_POST['phone'])) {
            throw new Exception('El teléfono debe ser un número válido de 9 dígitos');
        }

        $pdo->beginTransaction();
        
        $sql = "INSERT INTO employees (firstName, lastName, phone, isActive) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['firstName'],
            $_POST['lastName'],
            $_POST['phone'],
            isset($_POST['isActive']) ? 1 : 0
        ]);
        
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 