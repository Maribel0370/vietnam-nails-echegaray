<?php
session_start();
require_once '../setup_files/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $offerId = $data['id'] ?? '';

    if (empty($offerId)) {
        throw new Exception('ID de oferta no proporcionado');
    }

    // Reactivar la oferta
    $stmt = $pdo->prepare("UPDATE offers SET is_active = 1 WHERE id_offer = ?");
    $stmt->execute([$offerId]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 