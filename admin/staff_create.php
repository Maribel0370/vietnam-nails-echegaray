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
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $isActive = isset($_POST['isActive']) ? 1 : 0;

    // Validaciones básicas
    if (empty($firstName) || empty($lastName) || empty($phone)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Validar formato del teléfono (9 dígitos)
    if (!preg_match("/^[0-9]{9}$/", $phone)) {
        throw new Exception('El número de teléfono debe tener 9 dígitos');
    }

    // Insertar nuevo empleado
    $stmt = $pdo->prepare("
        INSERT INTO employees (firstName, lastName, phone, isActive) 
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([$firstName, $lastName, $phone, $isActive]);

    echo json_encode([
        'success' => true,
        'message' => 'Personal añadido correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 