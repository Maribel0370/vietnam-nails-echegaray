<?php
// Obtener personal de la base de datos
$sql = "SELECT * FROM employees";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$staff = $stmt->fetchAll();
?>

<div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
    <h3>Gestión de Personal</h3>
    
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
                    <input type="tel" name="phone" class="form-control" pattern="[0-9]{9}" required>
                </div>
            </div>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="isActive" class="form-check-input" id="staffActive" checked>
            <label class="form-check-label" for="staffActive">Activo</label>
        </div>
        <button type="submit" class="btn btn-primary">Añadir Personal</button>
    </form>

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
                        <td><?= date('d/m/Y', strtotime($employee['dataCreated'])) ?></td>
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