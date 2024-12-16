<?php
require_once '../../setup_files/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar y agregar el nuevo horario
    $id_employee = $_POST['id_employee'];
    $dayOfWeek = $_POST['dayOfWeek'];
    $blockType = $_POST['blockType'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $isActive = isset($_POST['isActive']) ? 1 : 0;

    // Verificar que la hora de inicio sea menor que la hora de fin
    if ($startTime >= $endTime) {
        echo "La hora de inicio debe ser menor que la hora de fin.";
        exit;
    }

    $sql = "INSERT INTO workschedules (id_employee, dayOfWeek, blockType, startTime, endTime, isActive) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_employee, $dayOfWeek, $blockType, $startTime, $endTime, $isActive]);

    header('Location: ../admin.php'); // Redirigir a la página de administración
}
?> 