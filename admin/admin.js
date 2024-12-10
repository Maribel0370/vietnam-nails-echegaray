document.addEventListener('DOMContentLoaded', function() {
    // Función para validar horarios
    function validateSchedule(startTime, endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        return start < end;
    }

    // Modificar horario
    document.querySelectorAll('.modify-schedule').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const cells = row.cells;
            
            // Guardar el contenido original para el botón cancelar
            const originalContent = row.innerHTML;
            
            // Convertir celdas a campos editables
            cells[0].innerHTML = document.getElementById('new_employee').outerHTML.replace('new_employee', 'edit_employee');
            cells[1].innerHTML = document.getElementById('new_day').outerHTML.replace('new_day', 'edit_day');
            cells[2].innerHTML = document.getElementById('new_blockType').outerHTML.replace('new_blockType', 'edit_blockType');
            cells[3].innerHTML = `<input type="time" class="form-control" id="edit_startTime" value="${cells[3].textContent.trim()}">`;
            cells[4].innerHTML = `<input type="time" class="form-control" id="edit_endTime" value="${cells[4].textContent.trim()}">`;
            
            // Cambiar botones
            cells[5].innerHTML = `
                <button class="btn btn-success btn-sm save-modified" data-id="${this.dataset.id}">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <button class="btn btn-secondary btn-sm cancel-modify">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            `;

            // Seleccionar los valores actuales
            row.querySelector('#edit_employee').value = this.dataset.employee;
            row.querySelector('#edit_day').value = this.dataset.day;
            row.querySelector('#edit_blockType').value = this.dataset.block;
        });
    });

    // Guardar modificación
    document.addEventListener('click', function(e) {
        if (e.target.closest('.save-modified')) {
            const button = e.target.closest('.save-modified');
            const row = button.closest('tr');
            const scheduleId = button.dataset.id;

            const formData = new FormData();
            formData.append('id_workSchedule', scheduleId);
            formData.append('id_employee', row.querySelector('#edit_employee').value);
            formData.append('dayOfWeek', row.querySelector('#edit_day').value);
            formData.append('blockType', row.querySelector('#edit_blockType').value);
            formData.append('startTime', row.querySelector('#edit_startTime').value);
            formData.append('endTime', row.querySelector('#edit_endTime').value);

            // Validar horarios
            if (!validateSchedule(formData.get('startTime'), formData.get('endTime'))) {
                alert('La hora de fin debe ser posterior a la hora de inicio');
                return;
            }

            fetch('schedule_update.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el horario');
            });
        }
    });

    // Eliminar horario
    document.querySelectorAll('.delete-schedule').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
                const scheduleId = this.dataset.id;
                
                fetch('schedule_delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: scheduleId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el horario');
                });
            }
        });
    });

    // Guardar nuevo horario
    document.getElementById('saveNewSchedule')?.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('id_employee', document.getElementById('new_employee').value);
        formData.append('dayOfWeek', document.getElementById('new_day').value);
        formData.append('blockType', document.getElementById('new_blockType').value);
        formData.append('startTime', document.getElementById('new_startTime').value);
        formData.append('endTime', document.getElementById('new_endTime').value);

        // Validar campos requeridos
        if (!formData.get('id_employee') || !formData.get('startTime') || !formData.get('endTime')) {
            alert('Por favor, complete todos los campos requeridos');
            return;
        }

        // Validar horarios
        if (!validateSchedule(formData.get('startTime'), formData.get('endTime'))) {
            alert('La hora de fin debe ser posterior a la hora de inicio');
            return;
        }

        fetch('schedule_create.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el horario');
        });
    });
});