<?php
// Obtener días especiales de la base de datos
$sql = "SELECT * FROM special_days";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$specialDays = $stmt->fetchAll();
?>

<div class="tab-pane fade" id="special-days" role="tabpanel" aria-labelledby="special-days-tab">
    <h3>Días Especiales</h3>
    
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