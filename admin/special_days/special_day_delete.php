<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id_special_day = $_POST['id_special_day'];

        $sql = "DELETE FROM special_days WHERE id_special_day = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_special_day]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 