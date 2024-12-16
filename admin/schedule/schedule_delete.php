<?php
require_once '../../setup_files/connection.php';
header('Content-Type: application/json');

try {
    // Verificar si el ID fue enviado
    if (!isset($_POST['id_workSchedule']) || empty($_POST['id_workSchedule'])) {
        throw new Exception('No se recibió el ID del horario.');
    }

    // Obtener el ID del horario a eliminar
    $id = $_POST['id_workSchedule'];

    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar el horario de la base de datos
    $sql = "DELETE FROM workschedules WHERE id_workSchedule = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Confirmar que la eliminación fue exitosa
    if ($stmt->rowCount() > 0) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Horario eliminado correctamente.']);
    } else {
        throw new Exception('No se pudo eliminar el horario con el ID ' . $id);
    }

} catch (PDOException $e) {
    // En caso de error, deshacer la transacción
    $pdo->rollBack();
    file_put_contents('error_log.txt', $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el horario: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Para otros errores generados por la validación
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
