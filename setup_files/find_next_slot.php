<?php
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['date']) || !isset($data['time'])) {
        throw new Exception('Datos incompletos');
    }

    $startDateTime = new DateTime($data['date'] . ' ' . $data['time']);
    $endDateTime = clone $startDateTime;
    $endDateTime->modify('+7 days'); // Buscar hasta 7 días después

    $excludeIds = isset($data['excludeEmployeeIds']) ? implode(',', array_map('intval', $data['excludeEmployeeIds'])) : '';
    $excludeCondition = $excludeIds ? "AND e.id_employee NOT IN ($excludeIds)" : '';

    // Buscar el próximo horario disponible para cada empleado
    $sql = "SELECT e.id_employee, e.firstName, e.lastName,
           DATE(potential_date) AS date, TIME(potential_date) AS time
           FROM employees e
           CROSS JOIN (
                SELECT DATE_ADD(:start_date1, INTERVAL n HOUR) AS potential_date
                FROM (
                    SELECT @row := @row + 1 AS n
                    FROM (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4) t1,
                         (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4) t2,
                         (SELECT @row := -1) t3                    
                    ) numbers
                    WHERE DATE_ADD(:start_date2, INTERVAL n HOUR) <= :end_date
                ) dates
                LEFT JOIN reservations r ON r.id_employee = e.id_employee
                    AND r.reservationDate = dates.potential_date
                    AND r.status != 'cancelled'
                WHERE e.isActive = 1
                $excludeCondition
                AND r.id_reservation IS NULL
                AND TIME(potential_date) BETWEEN '09:30:00' AND '20:00:00'
                AND DAYOFWEEK(potential_date) NOT IN (1)
                ORDER BY potential_date, e.id_employee
                LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $startDateTimeStr = $startDateTime->format('Y-m-d H:i:s');
    $endDateTimeStr = $endDateTime->format('Y-m-d H:i:s');
    $stmt->execute([
        ':start_date1' => $startDateTimeStr,
        ':start_date2' => $startDateTimeStr,
        ':end_date' => $endDateTimeStr
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode([
            'found' => true,
            'error' => false,
            'date' => $row['date'],
            'time' => $row['time'],
            'employeeId' => $row['id_employee'],
            'employeeName' => trim($row['firstName'] . ' ' . $row['lastName'])
        ]);
    } else {
        echo json_encode([
            'found' => false,
            'error' => false
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'available' => false,
        'error' => true,
        'message' => $e->getMessage()
    ]);
}