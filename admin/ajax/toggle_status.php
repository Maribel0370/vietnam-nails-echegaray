<?php
require_once '../../setup_files/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id = $_POST['id'];
        $type = $_POST['type'];
        $isActive = $_POST['is_active'];

        // Validar que se haya proporcionado un ID y un tipo
        if (empty($id) || empty($type)) {
            throw new Exception('El ID y el tipo son obligatorios');
        }

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
            case 'special_day':
                $sql = "UPDATE special_days SET isActive = ? WHERE id_special_day = ?";
                break;
            case 'service':
                $sql = "UPDATE services SET isActive = ? WHERE id_service = ?";
                break;
            case 'reservation':
                $sql = "UPDATE reservations SET isActive = ? WHERE id_reservation = ?";
                break;
            default:
                throw new Exception('Tipo no válido');
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$isActive, $id]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $isActive = $_POST['isActive'];

    try {
        $sql = "UPDATE workschedules SET isActive = ? WHERE id_workSchedule = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$isActive, $id]);

        echo json_encode(['success' => true]); // Respuesta exitosa en formato JSON
    } catch (PDOException $e) {
        error_log("Error al cambiar el estado del horario: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]); // Respuesta de error
    }
}
