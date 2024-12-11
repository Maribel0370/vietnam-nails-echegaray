<?php
session_start();
require_once '../setup_files/connection.php';
require_once '../setup_files/init.php';

// Añadir la configuración de idioma y función de traducción
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

$langFile = "../setup_files/languages/{$_SESSION['lang']}.php";
if (!file_exists($langFile)) {
    $langFile = "../setup_files/languages/es.php";
}
$lang = file_exists($langFile) ? include $langFile : [];

function translate($key, $default = '') {
    global $lang;
    return $lang[$key] ?? $default;
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Después de la verificación de autenticación y antes del manejo de ofertas
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            {$_SESSION['error']}
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    unset($_SESSION['error']);
}
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            {$_SESSION['message']}
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    unset($_SESSION['message']);
}

// Código para manejar las ofertas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_offer':
                try {
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
                    
                    if (isset($_POST['services']) && is_array($_POST['services'])) {
                        $sqlService = "INSERT INTO offer_services (id_offer, id_service) VALUES (?, ?)";
                        $stmtService = $pdo->prepare($sqlService);
                        
                        foreach ($_POST['services'] as $serviceId) {
                            $stmtService->execute([$offerId, $serviceId]);
                        }
                    }
                    
                    $pdo->commit();
                    
                    // En lugar de redirigir, devolvemos JSON
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                    
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }
                break;
        }
    }
}

// Obtener todas las ofertas con sus servicios asociados
$offers = $pdo->query("
    SELECT o.id_offer, 
           o.title,
           o.description,
           o.final_price,
           o.start_date,
           o.end_date,
           o.is_active,
           o.offer_type,
           GROUP_CONCAT(DISTINCT s.nameService SEPARATOR ', ') as services,
           GROUP_CONCAT(DISTINCT s.id_service) as service_ids
    FROM offers o
    LEFT JOIN offer_services os ON o.id_offer = os.id_offer
    LEFT JOIN services s ON os.id_service = s.id_service
    GROUP BY o.id_offer, o.title, o.description, o.final_price, o.start_date, o.end_date, o.is_active, o.offer_type
    ORDER BY o.start_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Obtener los servicios para el formulario y la tabla
$services = $pdo->query("SELECT * FROM services WHERE isActive = 1")->fetchAll();

// Obtener todo el personal
$staff = $pdo->query("
    SELECT id_employee, 
           firstName, 
           lastName, 
           phone, 
           isActive, 
           DATE_FORMAT(dataCreated, '%Y-%m-%d') as dataCreated 
    FROM employees 
    ORDER BY firstName ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Debug para ver qué contiene $staff
echo "<!-- Debug: ";
var_dump($staff);
echo " -->";

try {
    $staff = $pdo->query("
        SELECT id_employee, 
               firstName, 
               lastName, 
               phone, 
               isActive, 
               DATE_FORMAT(dataCreated, '%Y-%m-%d') as dataCreated 
        FROM employees 
        ORDER BY firstName ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<!-- Error en la consulta: " . $e->getMessage() . " -->";
    $staff = [];
}

$specialDays = []; // Inicializa como array vacío

try {
    $stmt = $pdo->prepare("SELECT * FROM special_days WHERE isActive = 1");
    $stmt->execute();
    $specialDays = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener días especiales: " . $e->getMessage());
    $specialDays = []; // En caso de error, mantener como array vacío
}

// Después de la consulta de $staff y antes del HTML
try {
    $schedules = $pdo->query("
        SELECT ws.*, 
               CONCAT(e.firstName, ' ', e.lastName) as employeeName
        FROM work_schedules ws
        JOIN employees e ON ws.id_employee = e.id_employee
        WHERE ws.isActive = 1
        ORDER BY ws.dayOfWeek ASC, ws.startTime ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener horarios: " . $e->getMessage());
    $schedules = [];
}

// Definir arrays para traducción de días y tipos de jornada
$diasSemana = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];

$tiposJornada = [
    'Full Day' => 'Jornada Completa',
    'Morning' => 'Mañana',
    'Afternoon' => 'Tarde'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Vietnam Nails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/Resources/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../setup_files/header.php'; ?>

    <div class="container admin-container">
        <h2 class="mb-4">Panel de Administración</h2>

        <!-- Sistema de pestañas -->
        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers" type="button" role="tab" aria-controls="offers" aria-selected="false">
                    Ofertas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab" aria-controls="staff" aria-selected="true">
                    Gestión Personal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                    Gestión Horarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="special-days-tab" data-bs-toggle="tab" data-bs-target="#special-days" type="button" role="tab">
                    Días Especiales
                </button>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="adminTabsContent">
            <!-- Pestaña de Ofertas -->
            <div class="tab-pane fade" id="offers" role="tabpanel" aria-labelledby="offers-tab">
                <div class="offers-content">
                    <h3>Gestión de Ofertas</h3>
                    <!-- Formulario para añadir nueva oferta -->
                    <form method="POST" class="mb-4" id="addOfferForm">
                        <input type="hidden" name="action" value="add_offer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Título</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tipo de oferta</label>
                                    <select name="offer_type" class="form-control" required>
                                        <option value="Seman">Semanal</option>
                                        <option value="Mens">Mensual</option>
                                        <option value="Temp">Temporal</option>
                                        <option value="Temp">Black Friday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Descripción</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Servicios incluidos</label>
                            <div class="services-list">
                                <?php
                                $services = $pdo->query("SELECT * FROM services WHERE isActive = 1")->fetchAll();
                                foreach ($services as $service): ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="services[]" class="form-check-input" 
                                               value="<?= $service['id_service'] ?>">
                                        <label class="form-check-label">
                                            <?= htmlspecialchars($service['nameService']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Precio final (€)</label>
                                    <input type="number" step="0.01" name="final_price" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Fecha inicio</label>
                                    <input type="datetime-local" name="start_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Fecha fin</label>
                                    <input type="datetime-local" name="end_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="offerActive" checked>
                            <label class="form-check-label" for="offerActive">Activa</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Oferta</button>
                    </form>

                    <!-- Lista de ofertas -->
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Servicios</th>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offers as $offer): ?>
                                <tr>
                                    <td><?= htmlspecialchars($offer['title']) ?></td>
                                    <td><?= htmlspecialchars($offer['description']) ?></td>
                                    <td><?= htmlspecialchars($offer['services']) ?></td>
                                    <td>
                                        <?php
                                        $tipo = '';
                                        switch($offer['offer_type']) {
                                            case 'Seman': $tipo = 'Semanal'; break;
                                            case 'Mens': $tipo = 'Mensual'; break;
                                            case 'Temp': $tipo = 'Temporal'; break;
                                        }
                                        echo $tipo;
                                        ?>
                                    </td>
                                    <td><?= number_format($offer['final_price'], 2) ?>€</td>
                                    <td><?= date('d/m/Y', strtotime($offer['start_date'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($offer['end_date'])) ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input toggle-offer-status" 
                                                   data-id="<?= $offer['id_offer'] ?>" 
                                                   <?= $offer['is_active'] ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-primary edit-offer" data-id="<?= $offer['id_offer'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-offer" data-id="<?= $offer['id_offer'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Gestión Personal -->
            <div class="tab-pane fade show active" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                <div class="staff-content">
                    <h3>Gestión de Personal</h3>
                    
                    <!-- Formulario para añadir nuevo personal -->
                    <form method="POST" id="addStaffForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Nombre *</label>
                                    <input type="text" name="firstName" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Apellidos *</label>
                                    <input type="text" name="lastName" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Teléfono *</label>
                                    <input type="tel" name="phone" class="form-control" pattern="[0-9]{9}" 
                                           title="Introduce un número de teléfono válido de 9 dígitos" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="isActive" class="form-check-input" id="staffActive" checked>
                            <label class="form-check-label" for="staffActive">Activo</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir Personal</button>
                    </form>

                    <!-- Lista de personal -->
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Alta</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($staff)): ?>
                                    <?php foreach ($staff as $employee): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($employee['firstName']) ?></td>
                                        <td><?= htmlspecialchars($employee['lastName']) ?></td>
                                        <td><?= htmlspecialchars($employee['phone']) ?></td>
                                        <td><?= isset($employee['dataCreated']) && !empty($employee['dataCreated']) ? 
                                                date('d/m/Y', strtotime($employee['dataCreated'])) : 'N/A' ?></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input toggle-staff-status" 
                                                       data-id="<?= $employee['id_employee'] ?>" 
                                                       <?= $employee['isActive'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary edit-staff" data-id="<?= $employee['id_employee'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-staff" data-id="<?= $employee['id_employee'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay personal registrado</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Gestión Horarios -->
            <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                <div class="schedule-content">
                    <h3>Gestión de Horarios</h3>
                    
                    <!-- Formulario para añadir nuevo horario -->
                    <form method="POST" id="addScheduleForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Empleado *</label>
                                    <select name="id_employee" class="form-control" required>
                                        <?php foreach ($staff as $employee): ?>
                                            <option value="<?= $employee['id_employee'] ?>">
                                                <?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Día de la semana *</label>
                                    <select name="dayOfWeek" class="form-control" required>
                                        <option value="Monday">Lunes</option>
                                        <option value="Tuesday">Martes</option>
                                        <option value="Wednesday">Miércoles</option>
                                        <option value="Thursday">Jueves</option>
                                        <option value="Friday">Viernes</option>
                                        <option value="Saturday">Sábado</option>
                                        <option value="Sunday">Domingo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Tipo de Jornada *</label>
                                    <select name="blockType" class="form-control" required>
                                        <option value="Full Day">Jornada Completa</option>
                                        <option value="Morning">Mañana</option>
                                        <option value="Afternoon">Tarde</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Hora inicio *</label>
                                    <input type="time" name="startTime" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Hora fin *</label>
                                    <input type="time" name="endTime" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="isActive" class="form-check-input" id="scheduleActive" checked>
                            <label class="form-check-label" for="scheduleActive">Activo</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir Horario</button>
                    </form>

                    <!-- Lista de horarios -->
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Empleado</th>
                                    <th>Día</th>
                                    <th>Tipo</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($schedules)): ?>
                                    <?php foreach ($schedules as $schedule): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($schedule['employeeName']) ?></td>
                                        <td><?= $diasSemana[$schedule['dayOfWeek']] ?></td>
                                        <td><?= $tiposJornada[$schedule['blockType']] ?></td>
                                        <td><?= date('H:i', strtotime($schedule['startTime'])) ?></td>
                                        <td><?= date('H:i', strtotime($schedule['endTime'])) ?></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input toggle-schedule-status" 
                                                       data-id="<?= $schedule['id_workSchedule'] ?>" 
                                                       <?= $schedule['isActive'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary edit-schedule" 
                                                        data-id="<?= $schedule['id_workSchedule'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-schedule" 
                                                        data-id="<?= $schedule['id_workSchedule'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay horarios registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Días Especiales -->
            <div class="tab-pane fade" id="special-days" role="tabpanel" aria-labelledby="special-days-tab">
                <div class="special-days-content">
                    <h3>Días Especiales</h3>
                    
                    <!-- Formulario para añadir día especial -->
                    <form method="POST" id="addSpecialDayForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Fecha *</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Descripción *</label>
                                    <input type="text" name="description" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Estado *</label>
                                    <select name="is_open" class="form-control" required>
                                        <option value="1">Abierto</option>
                                        <option value="0">Cerrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="hoursSection">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Hora apertura *</label>
                                    <input type="time" name="opening_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Hora cierre *</label>
                                    <input type="time" name="closing_time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir Día Especial</button>
                    </form>

                    <!-- Lista de días especiales -->
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Hora Apertura</th>
                                    <th>Hora Cierre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($specialDays)): ?>
                                    <?php foreach ($specialDays as $day): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($day['date'])) ?></td>
                                        <td><?= htmlspecialchars($day['description']) ?></td>
                                        <td><?= $day['is_open'] ? 'Abierto' : 'Cerrado' ?></td>
                                        <td><?= $day['is_open'] ? date('H:i', strtotime($day['opening_time'])) : '-' ?></td>
                                        <td><?= $day['is_open'] ? date('H:i', strtotime($day['closing_time'])) : '-' ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary edit-special-day" 
                                                        data-id="<?= $day['id_special_day'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-special-day" 
                                                        data-id="<?= $day['id_special_day'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No hay días especiales registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../setup_files/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>

