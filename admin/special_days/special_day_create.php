<?php
require_once '../../setup_files/connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['date'], $_POST['description'], $_POST['is_open'], $_POST['opening_time'], $_POST['closing_time'])) {
            $date = $_POST['date'];
            $description = $_POST['description'];
            $is_open = $_POST['is_open'];
            $opening_time = $_POST['opening_time'];
            $closing_time = $_POST['closing_time'];

            $sql = "INSERT INTO special_days (date, description, is_open, opening_time, closing_time) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$date, $description, $is_open, $opening_time, $closing_time]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 