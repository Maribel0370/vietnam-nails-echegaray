<?php
session_start();
require_once '../setup_files/connection.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $isActive = $_POST['isActive'] ?? '1';

    if (empty($firstName) || empty($lastName) || empty($phone)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Validar formato del teléfono
    if (!preg_match("/^[0-9]{9}$/", $phone)) {
        throw new Exception('El número de teléfono debe tener 9 dígitos');
    }

    $stmt = $pdo->prepare("
        INSERT INTO employees (firstName, lastName, phone, isActive) 
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([$firstName, $lastName, $phone, $isActive]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 