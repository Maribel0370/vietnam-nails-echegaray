<?php
require_once '../../setup_files/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar y actualizar el horario
    $id = $_POST['id'];
    $employee = $_POST['employee'];
    $day = $_POST['day'];
    $type = $_POST['type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $isActive = isset($_POST['isActive']) ? 1 : 0;

    $sql = "UPDATE schedules SET employee = ?, day = ?, type = ?, start_time = ?, end_time = ?, isActive = ? WHERE id_schedule = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employee, $day, $type, $start_time, $end_time, $isActive, $id]);

    header('Location: schedule.php');
}

// Obtener el horario para editar
$id = $_GET['id'];
$sql = "SELECT * FROM schedules WHERE id_schedule = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Horario</title>
</head>
<body>
    <h3>Actualizar Horario</h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $schedule['id_schedule'] ?>">
        <label>Empleado *</label>
        <input type="text" name="employee" value="<?= htmlspecialchars($schedule['employee']) ?>" required>
        <label>Día *</label>
        <input type="text" name="day" value="<?= htmlspecialchars($schedule['day']) ?>" required>
        <label>Tipo *</label>
        <input type="text" name="type" value="<?= htmlspecialchars($schedule['type']) ?>" required>
        <label>Hora Inicio *</label>
        <input type="time" name="start_time" value="<?= htmlspecialchars($schedule['start_time']) ?>" required>
        <label>Hora Fin *</label>
        <input type="time" name="end_time" value="<?= htmlspecialchars($schedule['end_time']) ?>" required>
        <label>Activo</label>
        <input type="checkbox" name="isActive" <?= $schedule['isActive'] ? 'checked' : '' ?>>
        <button type="submit">Actualizar Horario</button>
    </form>
</body>
</html> 