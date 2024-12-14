<?php
require_once '../../setup_files/connection.php';
header('Content-Type: application/json');

try {
    // Verificar que los datos necesarios estén presentes
    if (!isset($_POST['is_active'], $_POST['id_offer'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    // Comprobar si la oferta existe
    $checkSql = "SELECT COUNT(*) FROM offers WHERE id_offer = ?";
    $stmtCheck = $pdo->prepare($checkSql);
    $stmtCheck->execute([$_POST['id_offer']]);
    $count = $stmtCheck->fetchColumn();

    if ($count == 0) {
        echo json_encode(['success' => false, 'message' => 'La oferta no existe.']);
        exit;
    }

    // Validar el valor de is_active (debe ser 0 o 1)
    if ($_POST['is_active'] != 0 && $_POST['is_active'] != 1) {
        echo json_encode(['success' => false, 'message' => 'Valor de is_active no válido.']);
        exit;
    }

    // Actualizar el estado de la oferta
    $sql = "UPDATE offers SET is_active = ? WHERE id_offer = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['is_active'], // Asegúrate de que `is_active` esté recibiendo el valor correcto (0 o 1)
        $_POST['id_offer']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Capturar cualquier error y mostrarlo
    echo json_encode(['success' => false, 'message' => 'Error al actualizar estado: ' . $e->getMessage()]);
}
?>
