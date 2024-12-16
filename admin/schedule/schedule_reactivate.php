<?php
require_once '../../setup_files/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $sql = "UPDATE schedules SET isActive = 1 WHERE id_schedule = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header('Location: schedule.php');
}
?> 