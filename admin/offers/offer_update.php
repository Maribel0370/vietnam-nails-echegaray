<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    $pdo->beginTransaction();

    // Actualizar la oferta
    $sql = "UPDATE offers SET 
            title = ?,
            description = ?,
            offer_type = ?,
            final_price = ?,
            start_date = ?,
            end_date = ?
            WHERE id_offer = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['offer_type'],
        $_POST['final_price'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['id_offer']
    ]);

    // Actualizar servicios
    // Primero eliminar los existentes
    $sqlDelete = "DELETE FROM offer_services WHERE id_offer = ?";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([$_POST['id_offer']]);

    // Insertar los nuevos servicios
    if (isset($_POST['services']) && is_array($_POST['services'])) {
        $sqlService = "INSERT INTO offer_services (id_offer, id_service) VALUES (?, ?)";
        $stmtService = $pdo->prepare($sqlService);
        
        foreach ($_POST['services'] as $serviceId) {
            $stmtService->execute([$_POST['id_offer'], $serviceId]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 