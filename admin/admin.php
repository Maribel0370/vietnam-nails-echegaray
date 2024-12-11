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
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- CSS común del sitio -->
    <link rel="stylesheet" href="../public/Resources/css/style.css">
    
    <!-- CSS específico para admin -->
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <main class="container mt-4">
        <h1 class="text-center mb-4"><?php echo translate('admin_panel', 'Panel de Administración'); ?></h1>

        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="offers-tab" data-toggle="tab" href="#offers" role="tab">
                    <?php echo translate('offers_management', 'Gestión de Ofertas'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="staff-tab" data-toggle="tab" href="#staff" role="tab">
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
            <div class="tab-pane fade" id="offers" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Gestión de Ofertas</h3>
                        
                        <div class="table-responsive">
                            <table class="table" id="offersTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Tipo</th>
                                        <th>Servicios</th>
                                        <th>Precio</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila para añadir nueva oferta -->
                                    <tr>
                                        <td><input type="text" class="form-control" id="new_title"></td>
                                        <td><textarea class="form-control" id="new_description"></textarea></td>
                                        <td>
                                            <select class="form-control" id="new_offer_type">
                                                <option value="weekly">Semanal</option>
                                                <option value="monthly">Mensual</option>
                                                <option value="blackfriday">Black Friday</option>
                                                <option value="special">Especial</option>
                                            </select>
                                        </td>
                                        <td>
                                            <?php
                                            $stmt = $pdo->query("SELECT id_service, nameService FROM services WHERE isActive = 1");
                                            while ($service = $stmt->fetch()) {
                                                echo "<div class='custom-control custom-checkbox'>";
                                                echo "<input type='checkbox' class='custom-control-input' 
                                                      id='new_service_{$service['id_service']}' 
                                                      name='new_services[]' value='{$service['id_service']}'>";
                                                echo "<label class='custom-control-label' for='new_service_{$service['id_service']}'>" . 
                                                     htmlspecialchars($service['nameService']) . "</label>";
                                                echo "</div>";
                                            }
                                            ?>
                                        </td>
                                        <td><input type="number" step="0.01" class="form-control" id="new_price"></td>
                                        <td><input type="date" class="form-control" id="new_start_date"></td>
                                        <td><input type="date" class="form-control" id="new_end_date"></td>
                                        <td>
                                            <button class="btn btn-success btn-sm" id="saveNewOffer">
                                                <i class="fas fa-save"></i> Guardar
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Ofertas existentes -->
                                    <?php
                                    try {
                                        $stmt = $pdo->query("
                                            SELECT o.*, GROUP_CONCAT(s.nameService SEPARATOR ', ') as services
                                            FROM offers o
                                            LEFT JOIN offer_services os ON o.id_offer = os.id_offer
                                            LEFT JOIN services s ON os.id_service = s.id_service
                                            GROUP BY o.id_offer
                                            ORDER BY o.is_active DESC, o.end_date DESC
                                        ");
                                        
                                        while ($offer = $stmt->fetch()) {
                                            echo "<tr" . ($offer['is_active'] ? "" : " class='table-secondary'") . ">";
                                            echo "<td>" . htmlspecialchars($offer['title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($offer['description']) . "</td>";
                                            echo "<td>" . htmlspecialchars($offer['offer_type']) . "</td>";
                                            echo "<td>" . htmlspecialchars($offer['services']) . "</td>";
                                            echo "<td>" . number_format($offer['final_price'], 2) . "€</td>";
                                            echo "<td>" . date('d/m/Y', strtotime($offer['start_date'])) . "</td>";
                                            echo "<td>" . date('d/m/Y', strtotime($offer['end_date'])) . "</td>";
                                            echo "<td>";
                                            if ($offer['is_active']) {
                                                echo "<button class='btn btn-warning btn-sm modify-offer' 
                                                        data-id='{$offer['id_offer']}'
                                                        data-title='" . htmlspecialchars($offer['title']) . "'
                                                        data-description='" . htmlspecialchars($offer['description']) . "'
                                                        data-type='{$offer['offer_type']}'
                                                        data-price='{$offer['final_price']}'
                                                        data-start='" . date('Y-m-d', strtotime($offer['start_date'])) . "'
                                                        data-end='" . date('Y-m-d', strtotime($offer['end_date'])) . "'
                                                        data-services='" . htmlspecialchars($offer['services']) . "'>
                                                        <i class='fas fa-edit'></i> Modificar
                                                    </button>
                                                    <button class='btn btn-danger btn-sm delete-offer' 
                                                            data-id='{$offer['id_offer']}'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>";
                                            } else {
                                                echo "<button class='btn btn-success btn-sm reactivate-offer' 
                                                        data-id='{$offer['id_offer']}'>
                                                        <i class='fas fa-redo'></i> Reactivar
                                                    </button>";
                                            }
                                            echo "</td></tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="8" class="text-danger">Error al cargar las ofertas</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Personal -->
            <div class="tab-pane fade show active" id="staff" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Gestión de Personal</h3>
                        
                        <div class="table-responsive">
                            <table class="table" id="staffTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila para añadir nuevo personal -->
                                    <tr>
                                        <td><input type="text" class="form-control" id="new_firstName"></td>
                                        <td><input type="text" class="form-control" id="new_lastName"></td>
                                        <td><input type="tel" class="form-control" id="new_phone" pattern="[0-9]{9}"></td>
                                        <td>
                                            <select class="form-control" id="new_status">
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm" id="saveNewStaff">
                                                <i class="fas fa-save"></i> Guardar
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Personal existente -->
                                    <?php
                                    try {
                                        // Prueba de conexión y consulta directa
                                        $query = "SELECT * FROM employees";
                                        $result = $pdo->query($query);
                                        
                                        if ($result === false) {
                                            echo "<tr><td colspan='5'>Error en la consulta</td></tr>";
                                            var_dump($pdo->errorInfo());
                                        } else {
                                            $employees = $result->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            if (empty($employees)) {
                                                echo "<tr><td colspan='5'>No hay empleados registrados</td></tr>";
                                            } else {
                                                foreach ($employees as $employee) {
                                                    echo "<tr>";
                                                    echo "<td>" . $employee['firstName'] . "</td>";
                                                    echo "<td>" . $employee['lastName'] . "</td>";
                                                    echo "<td>" . $employee['phone'] . "</td>";
                                                    echo "<td>" . ($employee['isActive'] ? 'Activo' : 'Inactivo') . "</td>";
                                                    if ($employee['isActive']) {
                                                        echo "<td>
                                                                <button class='btn btn-warning btn-sm modify-staff' 
                                                                        data-id='{$employee['id_employee']}'
                                                                        data-firstname='" . htmlspecialchars($employee['firstName']) . "'
                                                                        data-lastname='" . htmlspecialchars($employee['lastName']) . "'
                                                                        data-phone='{$employee['phone']}'
                                                                        data-active='{$employee['isActive']}'>
                                                                    <i class='fas fa-edit'></i> Editar
                                                                </button>
                                                                <button class='btn btn-danger btn-sm delete-staff' 
                                                                        data-id='{$employee['id_employee']}'>
                                                                    <i class='fas fa-trash'></i> Eliminar
                                                                </button>
                                                              </td>";
                                                    } else {
                                                        echo "<td>
                                                                <button class='btn btn-success btn-sm reactivate-staff' 
                                                                        data-id='{$employee['id_employee']}'>
                                                                    <i class='fas fa-redo'></i> Reactivar
                                                                </button>
                                                              </td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
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
                        
                        <div class="form-group mb-3">
                            <select class="form-control" id="employee_filter">
                                <option value="">Seleccionar empleado...</option>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT id_employee, firstName, lastName FROM employees WHERE isActive = 1 ORDER BY firstName");
                                    while ($employee = $stmt->fetch()) {
                                        echo "<option value='" . $employee['id_employee'] . "'>" . 
                                             htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) . 
                                             "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<option value=''>Error al cargar empleados</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table" id="scheduleTable">
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
                                                try {
                                                    $stmt = $pdo->query("SELECT id_employee, firstName, lastName FROM employees WHERE isActive = 1");
                                                    while ($employee = $stmt->fetch()) {
                                                        echo "<option value='" . $employee['id_employee'] . "'>" . 
                                                             htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) . 
                                                             "</option>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "<option value=''>Error al cargar empleados</option>";
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Días Especiales -->
            <div class="tab-pane fade" id="special-days" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Gestión de Días Especiales</h3>
                        
                        <div class="table-responsive">
                            <table class="table" id="specialDaysTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Hora Apertura</th>
                                        <th>Hora Cierre</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila para añadir nuevo día especial -->
                                    <tr>
                                        <td><input type="date" class="form-control" id="new_special_date"></td>
                                        <td><input type="text" class="form-control" id="new_special_description"></td>
                                        <td><input type="time" class="form-control" id="new_special_opening"></td>
                                        <td><input type="time" class="form-control" id="new_special_closing"></td>
                                        <td>
                                            <select class="form-control" id="new_special_status">
                                                <option value="1">Abierto</option>
                                                <option value="0">Cerrado</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm" id="saveNewSpecialDay">
                                                <i class="fas fa-save"></i> Guardar
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Días especiales existentes -->
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM special_days ORDER BY date DESC");
                                        $specialDays = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        if (empty($specialDays)) {
                                            echo '<tr><td colspan="6" class="text-center">No hay días especiales registrados</td></tr>';
                                        } else {
                                            foreach ($specialDays as $day) {
                                                echo "<tr>";
                                                echo "<td>" . date('d/m/Y', strtotime($day['date'])) . "</td>";
                                                echo "<td>" . htmlspecialchars($day['description']) . "</td>";
                                                echo "<td>" . substr($day['opening_time'], 0, 5) . "</td>";
                                                echo "<td>" . substr($day['closing_time'], 0, 5) . "</td>";
                                                echo "<td>" . ($day['is_open'] ? 'Abierto' : 'Cerrado') . "</td>";
                                                echo "<td>
                                                        <button class='btn btn-warning btn-sm modify-special-day' 
                                                                data-id='{$day['id_special_day']}'
                                                                data-date='{$day['date']}'
                                                                data-description='" . htmlspecialchars($day['description']) . "'
                                                                data-opening='{$day['opening_time']}'
                                                                data-closing='{$day['closing_time']}'
                                                                data-status='{$day['is_open']}'>
                                                            <i class='fas fa-edit'></i> Modificar
                                                        </button>
                                                        <button class='btn btn-danger btn-sm delete-special-day' 
                                                                data-id='{$day['id_special_day']}'>
                                                            <i class='fas fa-trash'></i> Eliminar
                                                        </button>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6" class="text-danger">Error al cargar los días especiales: ' . $e->getMessage() . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

    <!-- Modal para añadir ofertas -->
    <div class="modal fade" id="addOfferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Oferta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addOfferForm">
                        <div class="form-group">
                            <label for="title">Título</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="offer_type">Tipo de Oferta</label>
                            <select class="form-control" id="offer_type" name="offer_type" required>
                                <option value="weekly">Semanal</option>
                                <option value="monthly">Mensual</option>
                                <option value="blackfriday">Black Friday</option>
                                <option value="special">Especial</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="final_price">Precio Final (€)</label>
                            <input type="number" step="0.01" class="form-control" id="final_price" name="final_price" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label>Servicios Incluidos</label>
                            <?php
                            $stmt = $pdo->query("SELECT id_service, nameService FROM services WHERE isActive = 1");
                            while ($service = $stmt->fetch()) {
                                echo "<div class='custom-control custom-checkbox'>";
                                echo "<input type='checkbox' class='custom-control-input' id='service_{$service['id_service']}' 
                                      name='services[]' value='{$service['id_service']}'>";
                                echo "<label class='custom-control-label' for='service_{$service['id_service']}'>" . 
                                     htmlspecialchars($service['nameService']) . "</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar ofertas -->
    <div class="modal fade" id="editOfferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Oferta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editOfferForm">
                        <input type="hidden" id="edit_offer_id" name="id_offer">
                        <div class="form-group">
                            <label for="edit_offer_title">Título</label>
                            <input type="text" class="form-control" id="edit_offer_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_offer_description">Descripción</label>
                            <textarea class="form-control" id="edit_offer_description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_offer_type">Tipo de Oferta</label>
                            <select class="form-control" id="edit_offer_type" name="offer_type" required>
                                <option value="weekly">Semanal</option>
                                <option value="monthly">Mensual</option>
                                <option value="blackfriday">Black Friday</option>
                                <option value="special">Especial</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_offer_price">Precio Final (€)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_offer_price" name="final_price" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_offer_start_date">Fecha Inicio</label>
                            <input type="date" class="form-control" id="edit_offer_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_offer_end_date">Fecha Fin</label>
                            <input type="date" class="form-control" id="edit_offer_end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label>Servicios Incluidos</label>
                            <?php
                            $stmt = $pdo->query("SELECT id_service, nameService FROM services WHERE isActive = 1");
                            while ($service = $stmt->fetch()) {
                                echo "<div class='custom-control custom-checkbox'>";
                                echo "<input type='checkbox' class='custom-control-input' id='edit_service_{$service['id_service']}' 
                                      name='services[]' value='{$service['id_service']}'>";
                                echo "<label class='custom-control-label' for='edit_service_{$service['id_service']}'>" . 
                                     htmlspecialchars($service['nameService']) . "</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
