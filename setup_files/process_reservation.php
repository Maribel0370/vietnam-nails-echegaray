<?php
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['customer']) ||!isset($data['services'])) {
        throw new Exception('Datos incompletos');
    }

    $database = Database::getInstance();
    $conn = $database->getConnection();

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // 1. Procesar datos del cliente
        $customerData = $data['customer'];
        if (!isset($customerData['name']) || !isset($customerData['phone'])) {
            throw new Exception('Datos del cliente incompletos');
        }

        // Validar formato del teléfono (formato enpañol)
        if (!preg_match('/^(?:(?:\+|00)34)?[6789]\d{8}$/', $customerData['phone'])) {
            throw new Exception('Formato de teléfono inválido');
        }

        // Verificar si el cliente existe
        $stmt = $conn->prepare("SELECT id_customer, fullName FROM customersDetails WHERE phoneNumber = ?");
        $stmt->bind_param('s', $customerData['phone']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar cliente existente si el nombre ha cambiado
            $customer = $result->fetch_assoc();
            $customerId = $customer['id_customer'];

            if ($customer['fullName'] !== $customerData['name']) {
                $stmt = $conn->prepare("UPDATE customersDetails SET fullName = ? WHERE id_customer = ?");
                $stmt->bind_param('si', $customerData['name'], $customerId);
                $stmt->execute();
            }
        } else {
            // Crear nuevo cliente
            $stmt = $conn->prepare("INSERT INTO customersDetails (fullName, phoneNumber) VALUES (?,?)");
            $stmt->bind_param('ss', $customerData['name'], $customerData['phone']);
            $stmt->execute();
            $customerId = $conn->insert_id;
        }

        // 2. Validar y procesar servicios
        $reservationIds = [];
        $usedSlots = [];

        foreach ($data['services'] as $service) {
            if (!isset($service['date']) || !isset($service['time']) ||
                !isset($service['serviceId']) || !isset($service['employeeId'])) {
                throw new Exception('Datos del servicio incompletos');
            }

            $reservationDateTime = $service['date'] . ' ' . $service['time'];
            $slotKey = $service['employeeId'] . '_' . $reservationDateTime;

            // Verificar que el slot no esté duplicado en la misma reserva
            if (isset($usedSlots[$slotKey])) {
                throw new Exception('Horario duplicado para el mismo empleado');
            }
            $usedSlots[$slotKey] = true;

            // Verificar disponibilidad del empleado
            $stmt = $conn->prepare("
                SELECT COUNT(*) AS count 
                FROM reservations
                WHERE id_employee = ?
                AND reservationDate = ?
                AND status != 'cancelled'"
            );
            $stmt->bind_param('is', $service['employeeId'], $reservationDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                throw new Exception('Horario no disponible para el empleado seleccionado');
            }

            // Crear nueva reserva
            $stmt = $conn->prepare("
                INSERT INTO reservations (id_customer, id_employee, reservationDate, status)
                VALUES (?,?,?, 'pending')
            ");
            $stmt->bind_param('iis', $customerId, $service['employeeId'], $reservationDateTime);
            $stmt->execute();
            $reservationIds[] = $conn->insert_id;

            // Registrar el servicio reservado
            $stmt = $conn->prepare("
                INSERT INTO reservation_services (id_reservation, id_service) 
                VALUES (?,?)
            ");
            $stmt->bind_param('ii', $conn->insert_id, $service['serviceId']);
            $stmt->execute();
            
            $reservationIds[] = [
                'id' => $reservationId,
                'datetime' => $reservationDateTime,
                'employeeId' => $service['employeeId'],
                'serviceId' => $service['serviceId']
            ];
        }

        // Confirmar la transacción si todo esta bien
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Reservas registradas correctamente',
            'reservations' => $reservationIds
        ]);
    } catch (Exception $e) {
        // Revertir la transacción si hay un error
        $conn->rollback();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}