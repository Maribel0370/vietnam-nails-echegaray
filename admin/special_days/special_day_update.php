<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id_special_day = $_POST['id_special_day'];
        $date = $_POST['date'];
        $description = $_POST['description'];
        $is_open = $_POST['is_open'];
        $opening_time = $_POST['opening_time'];
        $closing_time = $_POST['closing_time'];

        $sql = "UPDATE special_days SET date = ?, description = ?, is_open = ?, opening_time = ?, closing_time = ? WHERE id_special_day = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $description, $is_open, $opening_time, $closing_time, $id_special_day]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 