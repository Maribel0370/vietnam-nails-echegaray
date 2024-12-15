<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    $sql = "UPDATE employees SET 
            firstName = ?,
            lastName = ?,
            phone = ?
            WHERE id_employee = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['phone'],
        $_POST['id_employee']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 