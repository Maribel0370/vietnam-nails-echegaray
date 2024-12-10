<?php
session_start();
require_once '../setup_files/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $offerId = $_GET['id'] ?? '';

    if (empty($offerId)) {
        throw new Exception('ID de oferta no proporcionado');
    }

    $stmt = $pdo->prepare("
        SELECT o.*, GROUP_CONCAT(os.id_service) as service_ids
        FROM offers o
        LEFT JOIN offer_services os ON o.id_offer = os.id_offer
        WHERE o.id_offer = ?
        GROUP BY o.id_offer
    ");
    
    $stmt->execute([$offerId]);
    $offer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$offer) {
        throw new Exception('Oferta no encontrada');
    }

    echo json_encode([
        'success' => true,
        'data' => $offer
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 