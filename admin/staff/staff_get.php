<?php
session_start();
require_once '../setup_files/connection.php';

// Verificar si el usuario está autenticado como admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    $staffId = $_GET['id'] ?? '';

    if (empty($staffId)) {
        throw new Exception('ID de personal no válido');
    }

    $stmt = $pdo->prepare("
        SELECT id_staff, name, specialty, schedule, is_active 
        FROM staff 
        WHERE id_staff = ?
    ");

    $stmt->execute([$staffId]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        throw new Exception('Personal no encontrado');
    }

    echo json_encode([
        'success' => true,
        'data' => $staff
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 