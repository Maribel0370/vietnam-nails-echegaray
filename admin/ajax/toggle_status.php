<?php
require_once '../../setup_files/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id = $_POST['id'];
        $type = $_POST['type'];
        $isActive = $_POST['is_active'];
        
        switch ($type) {
            case 'offer':
                $sql = "UPDATE offers SET is_active = ? WHERE id_offer = ?";
                break;
            case 'employee':
                $sql = "UPDATE employees SET isActive = ? WHERE id_employee = ?";
                break;
            case 'schedule':
                $sql = "UPDATE workSchedules SET isActive = ? WHERE id_workSchedule = ?";
                break;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$isActive, $id]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 