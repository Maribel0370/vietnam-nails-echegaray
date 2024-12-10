<?php
session_start();
require_once '../setup_files/connection.php';

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Incluir archivos de idioma y configuración
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

include '../setup_files/header.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo translate('admin_panel', 'Panel de Administración'); ?></title>
    <link rel="stylesheet" href="../public/Resources/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <main class="container mt-4">
        <h1 class="text-center mb-4"><?php echo translate('admin_panel', 'Panel de Administración'); ?></h1>

        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="offers-tab" data-toggle="tab" href="#offers" role="tab">
                    <?php echo translate('offers_management', 'Gestión de Ofertas'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="staff-tab" data-toggle="tab" href="#staff" role="tab">
                    <?php echo translate('staff_management', 'Gestión de Personal'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab">
                    <?php echo translate('schedule_management', 'Gestión de Horarios'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="special-days-tab" data-toggle="tab" href="#special-days" role="tab">
                    <?php echo translate('special_days', 'Días Especiales'); ?>
                </a>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="adminTabContent">
            <!-- Pestaña de Ofertas -->
            <div class="tab-pane fade show active" id="offers" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3><?php echo translate('current_offers', 'Ofertas Actuales'); ?></h3>
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addOfferModal">
                            <?php echo translate('add_offer', 'Añadir Oferta'); ?>
                        </button>
                        
                        <!-- Tabla de ofertas -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Fecha Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM offers WHERE is_active = 1");
                                    while ($offer = $stmt->fetch()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($offer['title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($offer['description']) . "</td>";
                                        echo "<td>" . number_format($offer['final_price'], 2) . "€</td>";
                                        echo "<td>" . date('d/m/Y', strtotime($offer['end_date'])) . "</td>";
                                        echo "<td>
                                                <button class='btn btn-sm btn-warning edit-offer' data-id='{$offer['id_offer']}'>Editar</button>
                                                <button class='btn btn-sm btn-danger delete-offer' data-id='{$offer['id_offer']}'>Eliminar</button>
                                              </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Personal -->
            <div class="tab-pane fade" id="staff" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Gestión de Personal</h3>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#staffModal">
                                <i class="fas fa-plus"></i> Añadir Personal
                            </button>
                        </div>
                        
                        <!-- Tabla de personal -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Especialidad</th>
                                        <th>Horario</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        // Añadir debug
                                        error_log("Intentando obtener personal de la base de datos");
                                        
                                        // Modificar la consulta para ver todos los registros inicialmente
                                        $stmt = $pdo->query("SELECT * FROM staff");
                                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        // Debug para ver cuántos registros se encontraron
                                        error_log("Registros encontrados: " . count($results));

                                        if (count($results) > 0) {
                                            foreach ($results as $staff) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($staff['name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($staff['specialty'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($staff['schedule'] ?? ''); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo ($staff['is_active'] ?? 0) ? 'success' : 'danger'; ?>">
                                                            <?php echo ($staff['is_active'] ?? 0) ? 'Activo' : 'Inactivo'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning edit-staff" 
                                                                data-id="<?php echo $staff['id_staff'] ?? ''; ?>"
                                                                data-name="<?php echo htmlspecialchars($staff['name'] ?? ''); ?>"
                                                                data-specialty="<?php echo htmlspecialchars($staff['specialty'] ?? ''); ?>"
                                                                data-schedule="<?php echo htmlspecialchars($staff['schedule'] ?? ''); ?>">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-staff" 
                                                                data-id="<?php echo $staff['id_staff'] ?? ''; ?>">
                                                            <i class="fas fa-trash"></i> Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No hay personal registrado</td>
                                            </tr>
                                            <?php
                                        }
                                    } catch (PDOException $e) {
                                        error_log("Error en la consulta de personal: " . $e->getMessage());
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-danger">
                                                Error al cargar los datos del personal
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Gestión de Horarios -->
            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Gestión de Horarios</h3>
                        
                        <div class="table-responsive mt-3">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Día</th>
                                        <th>Tipo</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila de añadir al principio -->
                                    <tr>
                                        <td>
                                            <select class="form-control" id="new_employee">
                                                <option value="">Seleccionar empleado...</option>
                                                <?php
                                                $stmt = $pdo->query("SELECT id_employee, firstName, lastName FROM employees WHERE isActive = 1");
                                                while ($employee = $stmt->fetch()) {
                                                    echo "<option value='" . $employee['id_employee'] . "'>" . 
                                                         htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) . 
                                                         "</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" id="new_day">
                                                <option value="Monday">Lunes</option>
                                                <option value="Tuesday">Martes</option>
                                                <option value="Wednesday">Miércoles</option>
                                                <option value="Thursday">Jueves</option>
                                                <option value="Friday">Viernes</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" id="new_blockType">
                                                <option value="Morning">Mañana</option>
                                                <option value="Afternoon">Tarde</option>
                                                <option value="Full Day">Jornada Completa</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="new_startTime">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="new_endTime">
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm" id="saveNewSchedule">
                                                <i class="fas fa-save"></i> Guardar
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Horarios existentes -->
                                    <?php
                                    try {
                                        $query = "SELECT ws.*, e.firstName, e.lastName 
                                                 FROM workSchedules ws 
                                                 JOIN employees e ON ws.id_employee = e.id_employee 
                                                 WHERE ws.isActive = 1 
                                                 ORDER BY e.firstName";
                                        $stmt = $pdo->query($query);
                                        
                                        while ($schedule = $stmt->fetch()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($schedule['firstName'] . ' ' . $schedule['lastName']) . "</td>";
                                            echo "<td>" . $schedule['dayOfWeek'] . "</td>";
                                            echo "<td>" . $schedule['blockType'] . "</td>";
                                            echo "<td>" . substr($schedule['startTime'], 0, 5) . "</td>";
                                            echo "<td>" . substr($schedule['endTime'], 0, 5) . "</td>";
                                            echo "<td>
                                                    <button class='btn btn-warning btn-sm modify-schedule' data-id='" . $schedule['id_workSchedule'] . "'>
                                                        <i class='fas fa-edit'></i> Modificar
                                                    </button>
                                                    <button class='btn btn-danger btn-sm delete-schedule' data-id='" . $schedule['id_workSchedule'] . "'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6" class="text-danger">Error al cargar los horarios</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Días Especiales -->
            <div class="tab-pane fade" id="special-days" role="tabpanel">
                <!-- Contenido de días especiales aquí -->
            </div>
        </div>
    </main>

    <?php include '../setup_files/footer.php'; ?>

    <!-- Scripts necesarios -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="admin.js"></script>
    <script>
    $(document).ready(function(){
        $('#adminTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
    </script>
</body>
</html>
