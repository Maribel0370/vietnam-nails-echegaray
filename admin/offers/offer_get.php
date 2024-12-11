<?php
require_once '../../setup_files/db_connection.php';
header('Content-Type: application/json');

try {
    if (isset($_GET['action']) && $_GET['action'] === 'get_services') {
        $stmt = $pdo->query("SELECT id_service, nameService FROM services WHERE isActive = 1");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($services);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} 