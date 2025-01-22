<?php
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['customer']) || !isset($data['services'])) {
        throw new Exception('Datos incompletos');
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    try {
        // 1. Procesar datos del cliente
        $customerData = $data['customer'];
        if (!isset($customerData['name']) || !isset($customerData['phone'])) {
            throw new Exception('Datos del cliente incompletos');
        }

        // Validar formato del teléfono (formato español)
        if (!preg_match('/^(?:(?:\+|00)34)?[6789]\d{8}$/', $customerData['phone'])) {
            throw new Exception('Formato de teléfono inválido');
        }

        // Verificar si el cliente existe
        $stmt = $pdo->prepare("SELECT id_customer, fullName FROM customersDetails WHERE phoneNumber = ?");
        $stmt->execute([$customerData['phone']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // Actualizar cliente existente si el nombre ha cambiado
            $customerId = $customer['id_customer'];
            if ($customer['fullName'] !== $customerData['name']) {
                $stmt = $pdo->prepare("UPDATE customersDetails SET fullName = ? WHERE id_customer = ?");
                $stmt->execute([$customerData['name'], $customerId]);
            }
        } else {
            // Crear nuevo cliente
            $stmt = $pdo->prepare("INSERT INTO customersDetails (fullName, phoneNumber) VALUES (?, ?)");
            $stmt->execute([$customerData['name'], $customerData['phone']]);
            $customerId = $pdo->lastInsertId();
        }

        // 2. Procesar las reservas
        $reservationIds = [];
        foreach ($data['services'] as $service) {
            $stmt = $pdo->prepare("
                INSERT INTO reservations (id_customer, id_employee, reservationDate, status)
                VALUES (?, ?, ?, 'pending')
            ");
            $stmt->execute([
                $customerId,
                $service['employeeId'],
                $service['date'] . ' ' . $service['time']
            ]);
            $reservationId = $pdo->lastInsertId();

            // Registrar el servicio reservado
            $stmt = $pdo->prepare("
                INSERT INTO reservation_services (id_reservation, id_service) 
                VALUES (?, ?)
            ");
            $stmt->execute([$reservationId, $service['serviceId']]);
            
            $reservationIds[] = [
                'id' => $reservationId,
                'datetime' => $service['date'] . ' ' . $service['time'],
                'employeeId' => $service['employeeId'],
                'serviceId' => $service['serviceId']
            ];
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Reservas registradas correctamente',
            'reservations' => $reservationIds
        ]);

    } catch (Exception $e) {
        // Revertir la transacción si hay un error
        $pdo->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}