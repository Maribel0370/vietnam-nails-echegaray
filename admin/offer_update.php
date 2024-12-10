<?php
session_start();
require_once '../setup_files/connection.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $offerId = $_POST['id_offer'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $offer_type = $_POST['offer_type'] ?? '';
    $final_price = $_POST['final_price'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $services = $_POST['services'] ?? [];

    if (empty($offerId) || empty($title) || empty($final_price) || empty($start_date) || empty($end_date)) {
        throw new Exception('Todos los campos obligatorios deben estar completos');
    }

    $pdo->beginTransaction();

    // Actualizar la oferta
    $stmt = $pdo->prepare("
        UPDATE offers 
        SET title = ?, 
            description = ?, 
            offer_type = ?, 
            final_price = ?, 
            start_date = ?, 
            end_date = ?
        WHERE id_offer = ?
    ");
    
    $stmt->execute([$title, $description, $offer_type, $final_price, $start_date, $end_date, $offerId]);

    // Actualizar servicios asociados
    $stmt = $pdo->prepare("DELETE FROM offer_services WHERE id_offer = ?");
    $stmt->execute([$offerId]);

    if (!empty($services)) {
        $stmt = $pdo->prepare("INSERT INTO offer_services (id_offer, id_service) VALUES (?, ?)");
        foreach ($services as $serviceId) {
            $stmt->execute([$offerId, $serviceId]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 