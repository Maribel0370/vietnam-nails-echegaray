// Crear el archivo admin.js con las funciones necesarias
$(document).ready(function() {
    // Prevenir la duplicación de contenido
    $('.nav-tabs button').on('shown.bs.tab', function (e) {
        // Remover cualquier contenido duplicado
        $('.tab-pane').each(function() {
            $(this).find('.staff-content, .offers-content, .schedule-content, .special-days-content').not(':first').remove();
        });
    });

    // Función para limpiar contenido duplicado
    function cleanDuplicateContent() {
        $('.tab-pane').each(function() {
            const $pane = $(this);
            const $content = $pane.children().first();
            if ($pane.children().length > 1) {
                $pane.children().not($content).remove();
            }
        });
    }

    // Limpiar contenido duplicado al cargar
    cleanDuplicateContent();

    // Manejar cambio de pestañas
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).data('bs-target');
        localStorage.setItem('activeAdminTab', target);
        cleanDuplicateContent();
    });

    // Restaurar última pestaña activa
    const lastTab = localStorage.getItem('activeAdminTab');
    if (lastTab) {
        const tab = new bootstrap.Tab(document.querySelector(`[data-bs-target="${lastTab}"]`));
        tab.show();
    }

    // Activar/Desactivar oferta
    $('.toggle-offer-status').change(function() {
        const id = $(this).data('id');
        const isActive = $(this).prop('checked');
        const $checkbox = $(this);
        
        $.ajax({
            url: 'offers/offer_reactivate.php',
            type: 'POST',
            data: {
                id_offer: id,
                is_active: isActive ? 1 : 0
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error al actualizar el estado');
                    $checkbox.prop('checked', !isActive);
                }
            }
        });
    });

    // Editar oferta
    $('.edit-offer').click(function() {
        const row = $(this).closest('tr');
        const cells = row.find('td');
        
        // Convertir a campos editables
        cells.eq(0).html(`<input type="text" class="form-control" value="${cells.eq(0).text()}">`);
        cells.eq(1).html(`<textarea class="form-control">${cells.eq(1).text()}</textarea>`);
        cells.eq(3).html(`
            <select class="form-control">
                <option value="Seman" ${cells.eq(3).text().trim() === 'Semanal' ? 'selected' : ''}>Semanal</option>
                <option value="Mens" ${cells.eq(3).text().trim() === 'Mensual' ? 'selected' : ''}>Mensual</option>
                <option value="Temp" ${cells.eq(3).text().trim() === 'Temporal' ? 'selected' : ''}>Temporal</option>
            </select>
        `);
        cells.eq(4).html(`<input type="number" step="0.01" class="form-control" value="${cells.eq(4).text().replace('€', '').trim()}">`);
        cells.eq(5).html(`<input type="date" class="form-control" value="${cells.eq(5).text().split('/').reverse().join('-')}">`);
        cells.eq(6).html(`<input type="date" class="form-control" value="${cells.eq(6).text().split('/').reverse().join('-')}">`);

        // Cambiar botones
        $(this).hide();
        row.find('.delete-offer').hide();
        row.find('.btn-group').append(`
            <button type="button" class="btn btn-sm btn-success save-changes" data-id="${$(this).data('id')}">
                <i class="fas fa-save"></i>
            </button>
            <button type="button" class="btn btn-sm btn-secondary cancel-edit">
                <i class="fas fa-times"></i>
            </button>
        `);
    });

    // Guardar cambios
    $(document).on('click', '.save-changes', function() {
        const row = $(this).closest('tr');
        const id = $(this).data('id');
        
        $.ajax({
            url: 'offers/offer_update.php',
            type: 'POST',
            data: {
                id_offer: id,
                title: row.find('td:eq(0) input').val(),
                description: row.find('td:eq(1) textarea').val(),
                offer_type: row.find('td:eq(3) select').val(),
                final_price: row.find('td:eq(4) input').val(),
                start_date: row.find('td:eq(5) input').val(),
                end_date: row.find('td:eq(6) input').val()
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error al actualizar la oferta');
                }
            }
        });
    });

    // Eliminar oferta
    $('.delete-offer').click(function() {
        if (confirm('¿Estás seguro de que deseas eliminar esta oferta?')) {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            
            $.ajax({
                url: 'offers/offer_delete.php',
                type: 'POST',
                data: { id_offer: id },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                    } else {
                        alert('Error al eliminar la oferta');
                    }
                }
            });
        }
    });

    // Cancelar edición
    $(document).on('click', '.cancel-edit', function() {
        location.reload();
    });

    // Gestión de Personal
    // Añadir personal
    $('#addStaffForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'employees/employee_create.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error al añadir personal: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión al servidor');
            }
        });
    });

    

 // Editar personal
$(document).on('click', '.edit-staff', function() {
    const row = $(this).closest('tr');
    
    // Hacer que las celdas sean editables
    row.find('td').each(function(index) {
        if (index < 3) { // Solo para Nombre, Apellidos y Teléfono
            const currentText = $(this).text();
            $(this).html(`<input type="text" value="${currentText}" class="form-control" />`);
        }
    });

    // Ocultar los botones de editar y eliminar
    $(this).hide(); // Ocultar el botón de editar
    row.find('.delete-staff').hide(); // Ocultar el botón de eliminar

    // Mostrar los botones de guardar y cancelar
    row.find('.save-staff').show(); // Mostrar el botón de guardar
    row.find('.cancel-edit').show(); // Mostrar el botón de cancelar
});

// Guardar cambios de personal
$(document).on('click', '.save-staff', function() {
    const row = $(this).closest('tr');
    const id = $(this).data('id');
    
    // Obtener los valores de los inputs
    const firstName = row.find('td:eq(0) input').val();
    const lastName = row.find('td:eq(1) input').val();
    const phone = row.find('td:eq(2) input').val();

    $.ajax({
        url: 'employees/employee_update.php',
        type: 'POST',
        data: {
            id: id,
            firstName: firstName,
            lastName: lastName,
            phone: phone
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error al actualizar personal: ' + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error de conexión:', textStatus, errorThrown);
            alert('Error de conexión al servidor');
        }
    });
});

// Cancelar edición
$(document).on('click', '.cancel-edit', function() {
    const row = $(this).closest('tr');
    
    // Volver a mostrar los datos originales
    row.find('td').each(function(index) {
        if (index < 3) { // Solo para Nombre, Apellidos y Teléfono
            const currentText = $(this).find('input').val(); // Obtener el valor del input
            $(this).html(currentText); // Reemplazar el input con el texto original
        }
    });

    // Ocultar los botones de guardar y cancelar
    row.find('.save-staff').hide();
    row.find('.cancel-edit').hide();

    // Mostrar el botón de editar y eliminar
    row.find('.edit-staff').show();
    row.find('.delete-staff').show();
});

// Activar/Desactivar personal
$('.toggle-staff-status').change(function() {
    const $checkbox = $(this);
    const id = $checkbox.data('id');
    const isActive = $checkbox.prop('checked') ? 1 : 0; // 1 para activo, 0 para inactivo

    $.ajax({
        url: 'ajax/toggle_status.php', // Cambia a la URL de toggle_status.php
        type: 'POST',
        data: {
            id: id,
            type: 'employee', // Especificar que es un empleado
            is_active: isActive
        },
        success: function(response) {
            if (!response.success) {
                alert('Error al actualizar el estado: ' + response.message);
                $checkbox.prop('checked', !$checkbox.prop('checked')); // Revertir el estado si hay un error
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error de conexión:', textStatus, errorThrown);
            alert('Error de conexión al servidor');
            $checkbox.prop('checked', !$checkbox.prop('checked')); // Revertir el estado si hay un error
        }
    });
});

// Eliminar personal
$('.delete-staff').click(function() {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
        const row = $(this).closest('tr');
        const id = $(this).data('id');
        
        $.ajax({
            url: 'employees/employee_delete.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    row.remove();
                } else {
                    alert('Error al eliminar el empleado: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión al servidor');
            }
        });
    }
});


    $(document).on('submit', '#addSpecialDayForm', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'special_days/special_day_create.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error al añadir el día especial: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la solicitud: ' + error);
            }
        });
    });

    // Cambiar estado de horario (activar/desactivar)
    $(document).on('change', '.toggle-schedule-status', function() {
        const id = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: 'ajax/toggle_status.php',
            type: 'POST',
            data: {
                id: id,
                isActive: isActive
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    // Si la respuesta es exitosa, no hacer nada
                    console.log('Estado actualizado correctamente.');
                } else {
                    // Si hay un error, mostrarlo
                    alert('Error al cambiar el estado del horario: ' + result.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error de conexión:', textStatus, errorThrown);
                // No mostrar un mensaje de error si la conexión es exitosa
            }
        });
    });
}); 
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-schedule');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-id');
            console.log('ID enviado:', scheduleId); // Verifica si el ID se envía correctamente
            if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
                fetch('schedule/schedule_delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_workSchedule=' + scheduleId // Asegúrate de usar el mismo nombre de campo
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta:', data); // Imprime la respuesta del servidor
                    alert(data.message);
                    if (data.success) {
                        location.reload(); // Recargar la página si la eliminación fue exitosa
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
});



