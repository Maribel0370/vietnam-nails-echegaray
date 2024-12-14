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
            url: 'staff/staff_create.php',
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
    $('.edit-staff').click(function() {
        const row = $(this).closest('tr');
        const cells = row.find('td');
        
        cells.eq(0).html(`<input type="text" class="form-control" value="${cells.eq(0).text()}" required>`);
        cells.eq(1).html(`<input type="text" class="form-control" value="${cells.eq(1).text()}" required>`);
        cells.eq(2).html(`<input type="tel" class="form-control" value="${cells.eq(2).text()}" pattern="[0-9]{9}" required>`);

        $(this).hide();
        row.find('.delete-staff').hide();
        row.find('.btn-group').append(`
            <button type="button" class="btn btn-sm btn-success save-staff" data-id="${$(this).data('id')}">
                <i class="fas fa-save"></i>
            </button>
            <button type="button" class="btn btn-sm btn-secondary cancel-edit">
                <i class="fas fa-times"></i>
            </button>
        `);
    });

    // Guardar cambios de personal
    $(document).on('click', '.save-staff', function() {
        const row = $(this).closest('tr');
        const id = $(this).data('id');
        
        $.ajax({
            url: 'staff/staff_update.php',
            type: 'POST',
            data: {
                id_employee: id,
                firstName: row.find('td:eq(0) input').val(),
                lastName: row.find('td:eq(1) input').val(),
                phone: row.find('td:eq(2) input').val()
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error al actualizar personal');
                }
            }
        });
    });

    // Activar/Desactivar personal
    $('.toggle-staff-status').change(function() {
        const $checkbox = $(this);
        const id = $checkbox.data('id');
        const isActive = $checkbox.prop('checked');
        
        $.ajax({
            url: 'staff/staff_reactivate.php',
            type: 'POST',
            data: {
                id_employee: id,
                isActive: isActive ? 1 : 0
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error al actualizar el estado');
                    $checkbox.prop('checked', !isActive);
                }
            }
        });
    });

    // Eliminar personal
    $('.delete-staff').click(function() {
        if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            
            $.ajax({
                url: 'staff/staff_delete.php',
                type: 'POST',
                data: { id_employee: id },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                    } else {
                        alert('Error al eliminar el empleado');
                    }
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
}); 