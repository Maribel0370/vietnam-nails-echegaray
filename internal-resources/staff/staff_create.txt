<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    // Validar campos requeridos
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['phone'])) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Validar formato del teléfono
    if (!preg_match('/^[0-9]{9}$/', $_POST['phone'])) {
        throw new Exception('El formato del teléfono no es válido');
    }

    $sql = "INSERT INTO employees (firstName, lastName, phone, isActive) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        trim($_POST['firstName']),
        trim($_POST['lastName']),
        $_POST['phone'],
        isset($_POST['isActive']) ? 1 : 0
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 