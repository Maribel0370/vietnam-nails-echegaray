<?php
require_once '../../setup_files/connection.php';
header('Content-Type: application/json');

try {
    $pdo->beginTransaction();

    // Primero eliminar los servicios asociados
    $sqlServices = "DELETE FROM offer_services WHERE id_offer = ?";
    $stmtServices = $pdo->prepare($sqlServices);
    $stmtServices->execute([$_POST['id_offer']]);

    // Luego eliminar la oferta
    $sqlOffer = "DELETE FROM offers WHERE id_offer = ?";
    $stmtOffer = $pdo->prepare($sqlOffer);
    $stmtOffer->execute([$_POST['id_offer']]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 