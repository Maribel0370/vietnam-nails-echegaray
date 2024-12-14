<?php
// Obtener horarios de la base de datos
$sql = "SELECT * FROM workschedules";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$schedules = $stmt->fetchAll();
?>

<div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
    <h3>Gestión de Horarios</h3>
    
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
                        <td><?= htmlspecialchars($schedule['dayOfWeek']) ?></td>
                        <td><?= htmlspecialchars($schedule['blockType']) ?></td>
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