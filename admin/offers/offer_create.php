<?php
require_once '../../setup_files/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_POST['title']) || empty($_POST['price']) || empty($_POST['start_date']) || empty($_POST['end_date'])) {
            throw new Exception('Todos los campos obligatorios deben estar completos');
        }

        if (!isset($_POST['services']) || !is_array($_POST['services']) || empty($_POST['services'])) {
            throw new Exception('Debes seleccionar al menos un servicio');
        }

        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price <= 0) {
            throw new Exception('El precio debe ser un número válido mayor que 0');
        }

        $pdo->beginTransaction();
        
        $sql = "INSERT INTO offers (title, description, offer_type, final_price, start_date, end_date, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['offer_type'],
            $_POST['final_price'],
            $_POST['start_date'],
            $_POST['end_date'],
            isset($_POST['is_active']) ? 1 : 0
        ]);
        
        $offerId = $pdo->lastInsertId();
        
        $sqlService = "INSERT INTO offer_services (id_offer, id_service) VALUES (?, ?)";
        $stmtService = $pdo->prepare($sqlService);
        
        foreach ($_POST['services'] as $serviceId) {
            $stmtService->execute([$offerId, $serviceId]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 