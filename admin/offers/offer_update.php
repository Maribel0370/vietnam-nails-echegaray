<?php
require_once '../../setup_files/connection.php';
header('Content-Type: application/json');

try {
    // Validar datos básicos
    if (!isset($_POST['id_offer'], $_POST['title'], $_POST['description'], $_POST['offer_type'], $_POST['final_price'], $_POST['start_date'], $_POST['end_date'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    // Iniciar transacción
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

    // Actualizar los servicios asociados (eliminar los existentes e insertar nuevos)
    $sqlDelete = "DELETE FROM offer_services WHERE id_offer = ?";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([$_POST['id_offer']]);

    // Insertar los nuevos servicios si se han enviado
    if (isset($_POST['services']) && is_array($_POST['services'])) {
        $sqlInsert = "INSERT INTO offer_services (id_offer, id_service) VALUES (?, ?)";
        $stmtInsert = $pdo->prepare($sqlInsert);

        foreach ($_POST['services'] as $serviceId) {
            $stmtInsert->execute([$_POST['id_offer'], $serviceId]);
        }
    }

    // Confirmar la transacción
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Oferta actualizada correctamente.']);

} catch (PDOException $e) {
    // Si ocurre un error, revertir la transacción y enviar mensaje de error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la oferta: ' . $e->getMessage()]);
}
