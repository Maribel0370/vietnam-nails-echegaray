<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    $sql = "UPDATE offers SET is_active = ? WHERE id_offer = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['is_active'],
        $_POST['id_offer']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 