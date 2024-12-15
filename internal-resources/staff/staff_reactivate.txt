<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    $sql = "UPDATE employees SET isActive = ? WHERE id_employee = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['isActive'],
        $_POST['id_employee']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 