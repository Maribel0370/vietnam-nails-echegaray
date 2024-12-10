// Variables y funciones globales
let originalContent = '';

// Funciones auxiliares para traducir días y tipos
function traducirDia(day) {
    const dias = {
        'Monday': 'Lunes',
        'Tuesday': 'Martes',
        'Wednesday': 'Miércoles',
        'Thursday': 'Jueves',
        'Friday': 'Viernes',
        'Saturday': 'Sábado',
        'Sunday': 'Domingo'
    };
    return dias[day] || day;
}

function traducirTipo(type) {
    const tipos = {
        'Morning': 'Mañana',
        'Afternoon': 'Tarde',
        'Full Day': 'Jornada Completa'
    };
    return tipos[type] || type;
}

function handleModifySchedule() {
    const row = this.closest('tr');
    const cells = row.getElementsByTagName('td');
    
    // Guardar el contenido original para el botón cancelar
    originalContent = row.innerHTML;
    
    // Obtener los valores actuales
    const currentEmployee = cells[0].textContent.trim();
    const currentDay = cells[1].textContent.trim();
    const currentBlock = cells[2].textContent.trim();
    const currentStartTime = cells[3].textContent.trim();
    const currentEndTime = cells[4].textContent.trim();
    
    // Convertir celdas a campos editables
    cells[0].innerHTML = document.getElementById('new_employee').outerHTML.replace('new_employee', 'edit_employee');
    cells[1].innerHTML = document.getElementById('new_day').outerHTML.replace('new_day', 'edit_day');
    cells[2].innerHTML = document.getElementById('new_blockType').outerHTML.replace('new_blockType', 'edit_blockType');
    cells[3].innerHTML = `<input type="time" class="form-control" id="edit_startTime" value="${currentStartTime}">`;
    cells[4].innerHTML = `<input type="time" class="form-control" id="edit_endTime" value="${currentEndTime}">`;
    
    // Cambiar botones
    cells[5].innerHTML = `
        <button class="btn btn-success btn-sm save-modified" data-id="${this.dataset.id}">
            <i class="fas fa-save"></i> Guardar
        </button>
        <button class="btn btn-secondary btn-sm cancel-modify">
            <i class="fas fa-times"></i> Cancelar
        </button>
    `;

    // Seleccionar los valores actuales en los selectores
    const editEmployee = row.querySelector('#edit_employee');
    const editDay = row.querySelector('#edit_day');
    const editBlock = row.querySelector('#edit_blockType');

    // Buscar y seleccionar las opciones correspondientes
    Array.from(editEmployee.options).forEach(option => {
        if (option.text === currentEmployee) {
            editEmployee.value = option.value;
        }
    });

    editDay.value = currentDay;
    editBlock.value = currentBlock;
}

function handleDeleteSchedule() {
    if (confirm('¿Está seguro de que desea eliminar este horario?')) {
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
}

// Funciones para gestión de ofertas
function handleModifyOffer() {
    const row = this.closest('tr');
    const cells = row.getElementsByTagName('td');
    
    // Guardar el contenido original para el botón cancelar
    originalContent = row.innerHTML;
    
    // Obtener los valores actuales
    const currentTitle = cells[0].textContent.trim();
    const currentDescription = cells[1].textContent.trim();
    const currentType = cells[2].textContent.trim();
    const currentServices = cells[3].textContent.trim();
    const currentPrice = cells[4].textContent.trim().replace('€', '');
    const currentStartDate = cells[5].textContent.trim();
    const currentEndDate = cells[6].textContent.trim();
    
    // Convertir celdas a campos editables
    cells[0].innerHTML = `<input type="text" class="form-control" id="edit_title" value="${currentTitle}">`;
    cells[1].innerHTML = `<textarea class="form-control" id="edit_description">${currentDescription}</textarea>`;
    cells[2].innerHTML = document.getElementById('new_offer_type').outerHTML.replace('new_offer_type', 'edit_offer_type');
    cells[3].innerHTML = document.getElementById('new_services').outerHTML.replace('new_services', 'edit_services');
    cells[4].innerHTML = `<input type="number" step="0.01" class="form-control" id="edit_price" value="${currentPrice}">`;
    cells[5].innerHTML = `<input type="date" class="form-control" id="edit_start_date" value="${formatDateForInput(currentStartDate)}">`;
    cells[6].innerHTML = `<input type="date" class="form-control" id="edit_end_date" value="${formatDateForInput(currentEndDate)}">`;
    
    // Cambiar botones
    cells[7].innerHTML = `
        <button class="btn btn-success btn-sm save-modified-offer" data-id="${this.dataset.id}">
            <i class="fas fa-save"></i> Guardar
        </button>
        <button class="btn btn-secondary btn-sm cancel-modify">
            <i class="fas fa-times"></i> Cancelar
        </button>
    `;

    // Seleccionar los valores actuales
    const editType = row.querySelector('#edit_offer_type');
    editType.value = this.dataset.type;

    // Marcar los servicios seleccionados
    if (this.dataset.services) {
        const serviceIds = this.dataset.services.split(',');
        const checkboxes = row.querySelectorAll('input[name="edit_services[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = serviceIds.includes(checkbox.value);
        });
    }
}

// Función auxiliar para formatear la fecha de dd/mm/yyyy a yyyy-mm-dd
function formatDateForInput(dateStr) {
    const parts = dateStr.split('/');
    return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
}

// Event listener para guardar oferta modificada
document.addEventListener('click', function(e) {
    if (e.target.closest('.save-modified-offer')) {
        const button = e.target.closest('.save-modified-offer');
        const row = button.closest('tr');
        const offerId = button.dataset.id;

        const formData = new FormData();
        formData.append('id_offer', offerId);
        formData.append('title', row.querySelector('#edit_title').value);
        formData.append('description', row.querySelector('#edit_description').value);
        formData.append('offer_type', row.querySelector('#edit_offer_type').value);
        formData.append('final_price', row.querySelector('#edit_price').value);
        formData.append('start_date', row.querySelector('#edit_start_date').value);
        formData.append('end_date', row.querySelector('#edit_end_date').value);

        // Recoger servicios seleccionados
        const selectedServices = [];
        row.querySelectorAll('input[name="edit_services[]"]:checked').forEach(checkbox => {
            selectedServices.push(checkbox.value);
        });
        formData.append('services', selectedServices);

        fetch('offer_update.php', {
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
            alert('Error al actualizar la oferta');
        });
    }
});

function handleDeleteOffer() {
    if (confirm('¿Est�� seguro de que desea eliminar esta oferta?')) {
        const offerId = this.dataset.id;
        
        fetch('offer_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: offerId })
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
            alert('Error al eliminar la oferta');
        });
    }
}

// El resto del código dentro de DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Cargado');
    
    // Debug para personal
    const staffButtons = document.querySelectorAll('.modify-staff, .delete-staff, .reactivate-staff');
    console.log('Botones de personal encontrados:', staffButtons.length);
    
    // Debug para el botón de guardar nuevo personal
    const saveNewStaffButton = document.getElementById('saveNewStaff');
    console.log('Botón saveNewStaff:', saveNewStaffButton);

    // Función para validar horarios
    function validateSchedule(startTime, endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        return start < end;
    }

    // Función para cargar horarios por empleado
    function loadSchedulesByEmployee(employeeId) {
        fetch(`get_schedules.php?employee_id=${employeeId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.querySelector('#scheduleTable tbody');
                const firstRow = tbody.firstElementChild;
                tbody.innerHTML = '';
                tbody.appendChild(firstRow);

                if (data.error) {
                    const errorRow = document.createElement('tr');
                    errorRow.innerHTML = `
                        <td colspan="6" class="text-center text-danger">
                            ${data.error}
                        </td>
                    `;
                    tbody.appendChild(errorRow);
                    return;
                }

                if (Array.isArray(data) && data.length === 0) {
                    const messageRow = document.createElement('tr');
                    messageRow.innerHTML = `
                        <td colspan="6" class="text-center">
                            No hay horarios registrados para este empleado
                        </td>
                    `;
                    tbody.appendChild(messageRow);
                    return;
                }

                data.forEach(schedule => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${schedule.firstName} ${schedule.lastName}</td>
                        <td>${traducirDia(schedule.dayOfWeek)}</td>
                        <td>${traducirTipo(schedule.blockType)}</td>
                        <td>${schedule.startTime.substr(0, 5)}</td>
                        <td>${schedule.endTime.substr(0, 5)}</td>
                        <td>
                            <button class='btn btn-warning btn-sm modify-schedule' 
                                    data-id='${schedule.id_workSchedule}'
                                    data-employee='${schedule.id_employee}'
                                    data-day='${schedule.dayOfWeek}'
                                    data-block='${schedule.blockType}'>
                                <i class='fas fa-edit'></i> Modificar
                            </button>
                            <button class='btn btn-danger btn-sm delete-schedule' 
                                    data-id='${schedule.id_workSchedule}'>
                                <i class='fas fa-trash'></i> Eliminar
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                addScheduleEventListeners();
            })
            .catch(error => {
                console.error('Error:', error);
                const tbody = document.querySelector('#scheduleTable tbody');
                const firstRow = tbody.firstElementChild;
                tbody.innerHTML = '';
                tbody.appendChild(firstRow);
                
                const errorRow = document.createElement('tr');
                errorRow.innerHTML = `
                    <td colspan="6" class="text-center text-danger">
                        Error al cargar los horarios: ${error.message}
                    </td>
                `;
                tbody.appendChild(errorRow);
            });
    }

    // Función para añadir event listeners a los botones
    function addScheduleEventListeners() {
        document.querySelectorAll('.modify-schedule').forEach(button => {
            button.addEventListener('click', handleModifySchedule);
        });

        document.querySelectorAll('.delete-schedule').forEach(button => {
            button.addEventListener('click', handleDeleteSchedule);
        });
    }

    // Event listener para el selector de empleado
    document.getElementById('employee_filter')?.addEventListener('change', function() {
        const employeeId = this.value;
        console.log('Empleado seleccionado:', employeeId); // Debug
        
        if (employeeId) {
            // Modificar la ruta para asegurarnos que es correcta
            fetch(`./get_schedules.php?employee_id=${employeeId}`)
                .then(response => {
                    console.log('Response:', response); // Debug
                    return response.json();
                })
                .then(data => {
                    console.log('Data recibida:', data); // Debug
                    const tbody = document.querySelector('#scheduleTable tbody');
                    const firstRow = tbody.firstElementChild;
                    tbody.innerHTML = '';
                    tbody.appendChild(firstRow);

                    if (data.error) {
                        const errorRow = document.createElement('tr');
                        errorRow.innerHTML = `
                            <td colspan="6" class="text-center text-danger">
                                ${data.error}
                            </td>
                        `;
                        tbody.appendChild(errorRow);
                        return;
                    }

                    if (Array.isArray(data) && data.length === 0) {
                        const messageRow = document.createElement('tr');
                        messageRow.innerHTML = `
                            <td colspan="6" class="text-center">
                                No hay horarios registrados para este empleado
                            </td>
                        `;
                        tbody.appendChild(messageRow);
                        return;
                    }

                    data.forEach(schedule => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${schedule.firstName} ${schedule.lastName}</td>
                            <td>${traducirDia(schedule.dayOfWeek)}</td>
                            <td>${traducirTipo(schedule.blockType)}</td>
                            <td>${schedule.startTime.substr(0, 5)}</td>
                            <td>${schedule.endTime.substr(0, 5)}</td>
                            <td>
                                <button class='btn btn-warning btn-sm modify-schedule' 
                                        data-id='${schedule.id_workSchedule}'
                                        data-employee='${schedule.id_employee}'
                                        data-day='${schedule.dayOfWeek}'
                                        data-block='${schedule.blockType}'>
                                    <i class='fas fa-edit'></i> Modificar
                                </button>
                                <button class='btn btn-danger btn-sm delete-schedule' 
                                        data-id='${schedule.id_workSchedule}'>
                                    <i class='fas fa-trash'></i> Eliminar
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    addScheduleEventListeners();
                })
                .catch(error => {
                    console.error('Error completo:', error); // Debug más detallado
                    const tbody = document.querySelector('#scheduleTable tbody');
                    const firstRow = tbody.firstElementChild;
                    tbody.innerHTML = '';
                    tbody.appendChild(firstRow);
                    
                    const errorRow = document.createElement('tr');
                    errorRow.innerHTML = `
                        <td colspan="6" class="text-center text-danger">
                            Error al cargar los horarios: ${error.message}
                        </td>
                    `;
                    tbody.appendChild(errorRow);
                });
        } else {
            showEmptyMessage();
        }
    });

    // Función para mostrar mensaje cuando no hay empleado seleccionado
    function showEmptyMessage() {
        const tbody = document.querySelector('#scheduleTable tbody');
        const firstRow = tbody.firstElementChild;
        tbody.innerHTML = '';
        tbody.appendChild(firstRow);
        
        const messageRow = document.createElement('tr');
        messageRow.innerHTML = `
            <td colspan="6" class="text-center">
                Por favor, seleccione un empleado para ver sus horarios
            </td>
        `;
        tbody.appendChild(messageRow);
    }

    // Modificar horario
    document.querySelectorAll('.modify-schedule').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const cells = row.getElementsByTagName('td');
            
            // Guardar el contenido original para el botón cancelar
            originalContent = row.innerHTML;
            
            // Obtener los valores actuales
            const currentEmployee = cells[0].textContent.trim();
            const currentDay = cells[1].textContent.trim();
            const currentBlock = cells[2].textContent.trim();
            const currentStartTime = cells[3].textContent.trim();
            const currentEndTime = cells[4].textContent.trim();
            
            // Convertir celdas a campos editables
            cells[0].innerHTML = document.getElementById('new_employee').outerHTML.replace('new_employee', 'edit_employee');
            cells[1].innerHTML = document.getElementById('new_day').outerHTML.replace('new_day', 'edit_day');
            cells[2].innerHTML = document.getElementById('new_blockType').outerHTML.replace('new_blockType', 'edit_blockType');
            cells[3].innerHTML = `<input type="time" class="form-control" id="edit_startTime" value="${currentStartTime}">`;
            cells[4].innerHTML = `<input type="time" class="form-control" id="edit_endTime" value="${currentEndTime}">`;
            
            // Cambiar botones
            cells[5].innerHTML = `
                <button class="btn btn-success btn-sm save-modified" data-id="${this.dataset.id}">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <button class="btn btn-secondary btn-sm cancel-modify">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            `;

            // Seleccionar los valores actuales en los selectores
            const editEmployee = row.querySelector('#edit_employee');
            const editDay = row.querySelector('#edit_day');
            const editBlock = row.querySelector('#edit_blockType');

            // Buscar y seleccionar las opciones correspondientes
            Array.from(editEmployee.options).forEach(option => {
                if (option.text === currentEmployee) {
                    editEmployee.value = option.value;
                }
            });

            editDay.value = currentDay;
            editBlock.value = currentBlock;
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

    // Cancelar modificación de horarios
    document.addEventListener('click', function(e) {
        if (e.target.closest('.cancel-modify')) {
            const row = e.target.closest('tr');
            
            // Restaurar la fila a su estado original
            row.innerHTML = originalContent;
        }
    });

    // Event listeners para ofertas
    document.querySelectorAll('.modify-offer').forEach(button => {
        button.addEventListener('click', handleModifyOffer);
    });

    document.querySelectorAll('.delete-offer').forEach(button => {
        button.addEventListener('click', handleDeleteOffer);
    });

    document.querySelectorAll('.reactivate-offer').forEach(button => {
        button.addEventListener('click', handleReactivateOffer);
    });

    // Manejar el formulario de añadir oferta
    document.getElementById('addOfferForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('offer_create.php', {
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
            alert('Error al guardar la oferta');
        });
    });

    // Manejar el formulario de editar oferta
    document.getElementById('editOfferForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('offer_update.php', {
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
            alert('Error al actualizar la oferta');
        });
    });

    // Funciones para gestión de personal
    function handleModifyStaff() {
        const row = this.closest('tr');
        const cells = row.getElementsByTagName('td');
        
        // Guardar el contenido original para el botón cancelar
        originalContent = row.innerHTML;
        
        // Obtener los valores actuales
        const currentFirstName = cells[0].textContent.trim();
        const currentLastName = cells[1].textContent.trim();
        const currentPhone = cells[2].textContent.trim();
        const currentStatus = cells[3].textContent.trim();
        
        // Convertir celdas a campos editables
        cells[0].innerHTML = `<input type="text" class="form-control" id="edit_firstName" value="${currentFirstName}">`;
        cells[1].innerHTML = `<input type="text" class="form-control" id="edit_lastName" value="${currentLastName}">`;
        cells[2].innerHTML = `<input type="tel" class="form-control" id="edit_phone" value="${currentPhone}" pattern="[0-9]{9}">`;
        cells[3].innerHTML = `
            <select class="form-control" id="edit_status">
                <option value="1" ${currentStatus === 'Activo' ? 'selected' : ''}>Activo</option>
                <option value="0" ${currentStatus === 'Inactivo' ? 'selected' : ''}>Inactivo</option>
            </select>
        `;
        
        // Cambiar botones
        cells[4].innerHTML = `
            <button class="btn btn-success btn-sm save-modified-staff" data-id="${this.dataset.id}">
                <i class="fas fa-save"></i> Guardar
            </button>
            <button class="btn btn-secondary btn-sm cancel-modify">
                <i class="fas fa-times"></i> Cancelar
            </button>
        `;
    }

    function handleDeleteStaff() {
        if (confirm('¿Está seguro de que desea eliminar este empleado?')) {
            const staffId = this.dataset.id;
            console.log('Intentando eliminar empleado:', staffId); // Debug

            fetch('staff_delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: staffId })
            })
            .then(response => {
                console.log('Respuesta recibida:', response); // Debug
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data); // Debug
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                alert('Error al eliminar el empleado');
            });
        }
    }

    function handleReactivateStaff() {
        if (confirm('¿Está seguro de que desea reactivar este empleado?')) {
            const staffId = this.dataset.id;
            
            fetch('staff_reactivate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: staffId })
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
                alert('Error al reactivar el empleado');
            });
        }
    }

    // Event listeners para personal
    document.querySelectorAll('.modify-staff').forEach(button => {
        button.addEventListener('click', handleModifyStaff);
    });

    document.querySelectorAll('.delete-staff').forEach(button => {
        console.log('Añadiendo event listener a botón delete-staff'); // Debug
        button.addEventListener('click', handleDeleteStaff);
    });

    document.querySelectorAll('.reactivate-staff').forEach(button => {
        button.addEventListener('click', handleReactivateStaff);
    });

    // Guardar nuevo personal
    document.getElementById('saveNewStaff')?.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('firstName', document.getElementById('new_firstName').value);
        formData.append('lastName', document.getElementById('new_lastName').value);
        formData.append('phone', document.getElementById('new_phone').value);
        formData.append('isActive', document.getElementById('new_status').value);

        // Validar campos requeridos
        if (!formData.get('firstName') || !formData.get('lastName') || !formData.get('phone')) {
            alert('Por favor, complete todos los campos requeridos');
            return;
        }

        // Validar formato del teléfono
        if (!formData.get('phone').match(/^[0-9]{9}$/)) {
            alert('El número de teléfono debe tener 9 dígitos');
            return;
        }

        fetch('staff_create.php', {
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
            alert('Error al guardar el empleado');
        });
    });

    // Event listener para guardar personal modificado
    document.addEventListener('click', function(e) {
        if (e.target.closest('.save-modified-staff')) {
            const button = e.target.closest('.save-modified-staff');
            const row = button.closest('tr');
            const staffId = button.dataset.id;

            const formData = new FormData();
            formData.append('id_employee', staffId);
            formData.append('firstName', row.querySelector('#edit_firstName').value);
            formData.append('lastName', row.querySelector('#edit_lastName').value);
            formData.append('phone', row.querySelector('#edit_phone').value);
            formData.append('isActive', row.querySelector('#edit_status').value);

            // Validar formato del teléfono
            if (!formData.get('phone').match(/^[0-9]{9}$/)) {
                alert('El número de teléfono debe tener 9 dígitos');
                return;
            }

            fetch('staff_update.php', {
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
                alert('Error al actualizar el empleado');
            });
        }
    });
});