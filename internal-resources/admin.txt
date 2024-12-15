$(document).ready(function() {
    $('#add-offer-form').on('submit', function(e) {
        e.preventDefault(); // Evitar que la página se recargue
        
        const formData = $(this).serialize(); // Serializar los datos del formulario
        
        $.ajax({
            type: 'POST',
            url: 'admin/admin.php', // Ruta a tu backend
            data: formData, // Enviar los datos del formulario
            success: function(response) {
                // Asegúrate de que el backend envíe un objeto JSON con una clave `success` y `message`
                if (response.success) {
                    // Mostrar mensaje de éxito
                    const alertDiv = `<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        ${response.message}
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                      </div>`;
                    $('.admin-container').prepend(alertDiv);

                    // Limpiar el formulario (opcional)
                    $('#add-offer-form')[0].reset();
                } else {
                    // Mostrar error si `response.success` es falso
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Hubo un error en el servidor. Por favor, inténtalo más tarde.');
            }
        });
    });
});
$(document).ready(function () {
    // Manejar el envío del formulario de añadir oferta
    $('#addOfferForm').on('submit', function (e) {
        e.preventDefault(); // Evitar el comportamiento por defecto del formulario

        const formData = $(this).serialize(); // Serializar datos del formulario

        $.ajax({
            type: 'POST',
            url: 'admin.php', // Asegúrate de que esta URL sea correcta
            data: formData,
            success: function (response) {
                // Verificar si el backend envió una respuesta JSON válida
                if (response.success) {
                    // Mostrar mensaje de éxito
                    const alertDiv = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${response.message}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>`;
                    $('.offers-content').prepend(alertDiv);

                    // Resetear el formulario
                    $('#addOfferForm')[0].reset();

                    // Actualizar la tabla (opcional: agregar la nueva oferta directamente)
                    // Aquí puedes recargar la tabla o actualizar dinámicamente
                    location.reload(); // Simplemente recargar la página
                } else {
                    // Mostrar mensaje de error si `success` es falso
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('Hubo un error al procesar la solicitud. Inténtalo más tarde.');
            }
        });
    });
});

document.getElementById("update-offer-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('offer_update.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert("Oferta actualizada exitosamente.");
            // Redirigir a la lista de ofertas o recargar
            window.location.href = "admin.php";
        } else {
            alert("Error al actualizar la oferta: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Ha ocurrido un error al procesar la solicitud.");
    }
});

