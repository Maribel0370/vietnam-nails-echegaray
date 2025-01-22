// Referencia a los elementos
document.addEventListener("DOMContentLoades", () => {
    const closeModalButton = document.getElementById("closeModal");
    const cancelReservaButton = document.getElementById("cancelReservation");
    const reservationModal = document.getElementById("reservationModal");
    const addServiceButton = document.getElementById("addService");
    const reservationForm = document.getElementById("reservationForm");
    const servicesContainer = document.getElementById("servicesContainer");
    let serviceCounter = 1;
    
    reservationModal.classList.remove("hidden");
    const today = new Date().toISOString().split("T")[0];
    const calendarInput = document.getElementById("calendar_1");
    calendarInput.setAttribute("min", today);
    calendarInput.setAttribute("value", today);

    // Inicializar el primer grupo de servicios
    createCalendar(undefined, undefined, 1);
    generateTimeSlots(1);

// Función para cerrar el modal
closeModalButton.addEventListener("click", () => {
    if (confirm("¿Estás seguro que deseas salir? Los datos no guardados se perderán.")) {
        resetForm();
        reservationModal.classList.add("hidden");
    }
});

// Función para limpia el formulario
function resetForm() {
    reservationForm.reset();

    // Limpiar datos personales
    document.getElementById("name").value = "";
    document.getElementById("phone").value = "";

    // Eliminar todos los grupos de servicios excepto el primero
    const serviceGroups = document.querySelectorAll(".service-Group");
    service-groups.forEach((group, index) => {
        if (index > 0) {
            group.remove();
        }
    });

    // Resetear el primer grupo de servicios
    const firstGroup = serviceGroups[0];
    if(firstGroup) {
        const calendarDays = firstGroup.querySelectorAll(".clendar-day");
        calendarDays.forEach(day => day.classList.remove("selected"));

        const timeRadios = firstGroup.querySelectorAll(".time-optin input[type='radio']");
        timeRadios.forEach(radio => radio.checked = false);

        firstGroup.querySelector("select[id^='service']").value = "";
        firstGroup.querySelector("select[id^='employee']").value = "";
    }

    serviceCounter = 1;
};

// Función para el botón Cancelar
cancelReservationButton.addEventListener("click", () => {
    if (confirm("¿Estás seguro que deseas cancelar la reserva? Los datos no guardados se perderán.")) {
        resetForm();
        reservationModal.classList.add("hidden");
    }
});

// Agregar evento para confirmar antes de cerrar con la "X" o haciendo click fuera del modal
window.addEventListener("click", (event) => {
    if (event.target === reservationModal) {
        if (confirm("¿Estás seguro que deseas salir? Los datos no guardados se perderán.")) {
            resetForm();
            reservationModal.classList.add("hidden");
        }
    }
});

// Función para crear un nuevo grupo de servicios
function createServiceGroup(counter) {
    const serviceGroup = document.createElement("div");
    serviceGroup.className = "service-group";
    serviceGroup.dataset.serviceId = counter;

    // Obtener las opciones de los selects del primer servicio
    const firstServiceSelect = document.getElementById("service_1");
    const firstEmployeeSelect = document.getElementById("employee_1");

    serviceGroup.innerHTML = `
        <h5>Servicio ${counter}</h5>
        <!-- Fecha y Hora -->
        <div class="date-time-container">
            <div class="calendar-wrapper">
                <input type="date" id="calendar_${counter}" name="calendar_${counter}" required
                    lang="es"
                    data-date-format="DD/MM/YYYY"
                    style="display: none;">
                <div id="calendarContainer_${counter}" class="calendar-container"></div>
            </div>
            <div class="time-wrapper">
                <label>Horario disponible:</label>
                <div id="timeSelector_${counter}" class="time-selector">
                    <!-- Los horarios se generarán dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Servicios y Empleados -->
        <div class="service-employee-container">
            <div class="form-group">
                <label for="service_${counter}">Servicio:</label>
                <select id="service_${counter}" name="service_${counter}" required>
                    ${firstServiceSelect.innerHTML}
                </select>
            </div>

            <div class="form-group">
                <label for="employee_${counter}">Empleado:</label>
                <select id="employee_${counter}" name="employee_${counter}" required>
                    ${firstEmployeeSelect.innerHTML}
                </select>
            </div>
        </div>
        ${counter > 1 ? `<button type="button" class="remove-service" data-service="${counter}">Eliminar Servicio</button>` : ""}
    `;
    return serviceGroup;
}

// Evento para agregar nuevo servicio
addServiceButton.addEventListener("click", () => {
    serviceCounter++;
    const newServiceGroup = createServiceGroup(serviceCounter);
    servicesContainer.appendChild(newServiceGroup);

    // Generar calendario y horarios para el nuevo servicio
    createCalendar(undefined, undefined, serviceCounter);
    generateTimeSlots(serviceCounter);

    // Agregar evento para eliminar el servicio
    const removeButton = newServiceGroup.querySelector(".remove-service");
    if (removeButton) {
        removeButton.addEventListener("click", () => {
            if (confirm("¿Estás seguro que deseas eliminar este servicio?")) {
                newServiceGroup.remove();
            }
        });
    }
});

// Función para crear el calendario
function createCalendar(year = new Date().getFullYear(), month = new Date().getMonth(), groupId = 1) {
    const calendarContainer = document.getElementById(`calendarContainer_${groupId}`);
    const calendarInput = document.getElementById(`calendar_${groupId}`);
    const date = new Date(year, month);
    const currentMonth = date.getMonth();
    const currentYear = date.getFullYear();

    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);

    // Verificar si es el mes actual
    const today = new Date();
    const isCurrentMonth = currentMonth === today.getMonth() && currentYear === today.getFullYear();

    // Crear el calendario
    let calendarHTML = `
        <div class="calendar-header">
            <button type="button" id="prevMonth" ${isCurrentMonth ? 'disabled' : ''}>&lt;</button>
            <h5>${date.toLocaleDateString("es-ES", { month: "long", year: "numeric" })}</h5>
            <button type="button" id="nextMonth">&gt;</button>
        </div>
        <div class="calendar-body">
            <div class="calendar-weekdays">
                <span>Lun</span>
                <span>Mar</span>
                <span>Mie</span>
                <span>Jue</span>
                <span>Vie</span>
                <span>Sáb</span>
                <span>Dom</span>
            </div>
            <div class="calendar-days">
    `;

    // Ajustar el primer día de la semana
    let firstDayOfWeek = firstDay.getDay();
    firstDayOfWeek = firstDayOfWeek === 0 ? 7 : firstDayOfWeek;
    
    // Agregar spacios en blanco para los días antes del primer día del mes
    for (let i = 1; i < firstDayOfWeek; i++) {
        calendarHTML += "<div></div>";
    }

    // Agregar los días del mes
    for (let day = 1; day <= lastDay.getDate(); day++) {
        const currentDate = new Date(currentYear, currentMonth, day);
        const isToday = isCurrentMonth && day === today.getDate();
        const dateString = currentDate.toISOString().split("T")[0];
        const isPast = currentDate < new Date(new Date().setHours(0, 0, 0, 0));

        calendarHTML += `
            <div class="calendar-day ${isToday ? "today" : ""} ${isPast ? "past" : ""}"
                data-date="${dateString}"
                ${isPast ? "disabled" : ""}>
                ${day}
            </div>
        `;
    }

    calendarHTML += `
            </div>
        </div>
    `;

    calendarContainer.innerHTML = calendarHTML;

    // Agregar eventos a los días del calendario
    document.querySelectorAll(`.calendar-day:not(.past)`).forEach(day => {
        day.addEventListener("click", () => {
            const selectedDate = day.dataset.date;
            calendarInput.value = selectedDate;
            document.querySelectorAll('.calendar-day').forEach(day => day.classList.remove("selected"));
            day.classList.add("selected");

            // Generar los horarios disponibles para la fecha seleccionada
            generateTimeSlots(groupId);
        });
    });

    // Agregar eventos para cambiar de mes
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");

    prevMonthButton.addEventListener("click", () => {
        createCalendar(currentYear, currentMonth - 1, groupId);
    });

    nextMonthButton.addEventListener("click", () => {
        createCalendar(currentYear, currentMonth + 1, groupId);
    });
}

// Función para generar los horarios disponibles
function generateTimeSlots(groupId = 1) {
    const timeSelector = document.getElementById(`timeSelector_${groupId}`);
    const selectedDate = document.getElementById(`calendar_${groupId}`).value;

    // Si no hay fecha seleccionada, no se generan horarios
    if (!selectedDate) {
        timeSelector.innerHTML = "<div class='no-slots-message'>Por favor selecciona una fecha.</div>";
        return;
    }

    const timeSlots = [
        "09:30", "10:00", "10:30", "11:00", "11:30", 
        "12:00", "12:30", "13:00", "13:30", "14:00",
        "14:30", "15:00", "15:30", "16:00", "16:30",
        "17:00", "17:30", "18:00", "18:30", "19:00",
        "19:30", "20:00"
    ];

    // Obtener la fecha y hora actual
    const now = new Date();
    const today = now.toISOString().split("T")[0];
    const currentHour = now.getHours();
    const currentMinutes = now.getMinutes();

    // Verificar si estamos fuera del horario de atención
    const isOutsideBusinessHours = currentHour >= 20 || currentHour < 9 || 
                                  (currentHour === 9 && currentMinutes > 30) || 
                                  (currentHour === 20 && currentMinutes > 0);

    // Si es el día actual y estamos fuera del horario de atención, mostrar mensaje
    if (selectedDate === today && isOutsideBusinessHours) {
        timeSelector.innerHTML = "<div class='no-slots-message'>Lo sentimos, no hay horarios disponibles para el día seleccionado. Por favor, seleccione otro día.</div>";
        return;
    }

    let slotsHTML = "";
    timeSlots.forEach(timeString => {
        const [slotHour, slotMinute] = timeString.split(":").map(num => parseInt(num));
        let isDisabled = false;

        // Verificar si la hora ya pasó
        if (selectedDate === today) {
            if (slotHour < currentHour || 
                (slotHour === currentHour && slotMinute <= currentMinutes)) {
                isDisabled = true;
            }
        }

        slotsHTML += `
            <div class="time-option">
                <input type="radio" 
                       id="time_${groupId}_${timeString}" 
                       name="time_${groupId}" 
                       value="${timeString}" 
                       required
                       ${isDisabled ? "disabled" : ""}>
                <label for="time_${groupId}_${timeString}"
                       class="${isDisabled ? 'disabled' : ''}">${timeString}</label>
            </div>
        `;
    });

    timeSelector.innerHTML = slotsHTML;
}

// Función para validar el nombre
function validateName(name){
    return /^[A-Za-záéíóúñÁÉÍÓÚÑ]+(\s[A-Za-záéíóúñÁÉÍÓÚÑ]+)+$/.test(name.trim());
}

// Función para validar el teléfono
function validatePhone(phone){
    return /^(?:(?:\+|00)34)?[6789]\d{8}$/.test(phone.trim());
}

// Función para verificar la disponibilidad del empleado
async function checkEmployeeAvailability(employeeId, date, time) {
    try{
        const response = await fetch('/setup_files/check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                employeeId,
                date,
                time
            })
        });
        return await response.json();
    } catch (error) {
        console.error('Error checking availability:', error);
        return { available: false, error: true };
    }
}

// Función para buscar el siguiente horario disponible
async function findNextAvailableSlot(date, time, excludeEployeeIds = []) {
    try {
        const response = await fetch('/setup_files/find_next_slot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                date,
                time,
                excludeEployeeIds
            })
        });
        return await response.json();
    } catch (error) {
        console.error('Error finding next slot:', error);
        return { found: false, error: true };
    }
}

// Modificar el evento de envío del formulario
reservationForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    try {
        // 1. Validar los datos personales
        const nameInput = document.getElementById("name");
        const phoneInput = document.getElementById("phone");

        if (!validateName(nameInput.value)) {
            alert("Por favor, ingresa un nombre completo válido (nombre y apellido).");
            nameInput.focus();
            return;
        }

        if (!validatePhone(phoneInput.value)) {
            alert("Por favor, ingresa un número de teléfono válido (formato español).");
            phoneInput.focus();
            return;
        }

        const customerData = {
            name: nameInput.value.trim(),
            phone: phoneInput.value.trim()
        };

        // 2. Validar los servicios selecionados
        const services = [];
        const serviceGroups = document.querySelectorAll(".service-group");
        const usedSlots = new Set();

        for (const group of serviceGroups) {
            const groupId = group.dataset.serviceId;

            // Validar la fecha seleccionada
            const calendarInput = document.getElementById(`calendar_${groupId}`);
            if(!calendarInput.value) {
                alert(`Por favor, seleccione una fecha para todos los servicios.`);
                calendarInput.focus();
                return;
            }

            // Validar la hora seleccionada
            const timeSelected = group.querySelector(`input[name='time_${groupId}']:checked`);
            if (!timeSelected) {
                alert(`Por favor, seleccione un horario para todos los servicios.`);
                return;
            }

            // Validar el servicio seleccionado
            const serviceSelect = document.getElementById(`service_${groupId}`);
            if (!serviceSelect.value) {
                alert(`Por favor, seleccione un servicio para todas las reservas.`);
                serviceSelect.focus();
                return;
            }

            // Validar el empleado seleccionado
            const employeeSelect = document.getElementById(`employee_${groupId}`);
            if (!employeeSelect.value) {
                alert(`Por favor, seleccione un empleado para todas las reservas.`);
                employeeSelect.focus();
                return;
            }

            // Verificar duplicados en la misma solicitud
            const slotKey = `${employeeSelect.value}_${calendarInput.value}_${timeSelected.value}`;
            if (usedSlots.has(slotKey)) {
                alert(`No se puede reservar el mismo horario más de una vez para el mismo empleado.`);
                return;
            }
            usedSlots.add(slotKey);

            // 3. Verificar la disponibilidad del empleado en tiempo real
            const availabilityCheck = await checkEmployeeAvailability(
                employeeSelect.value, 
                calendarInput.value, 
                timeSelected.value
            );

            if (!availabilityCheck.available) {
                if (availabilityCheck.error) {
                    alert("Error al verificar la disponibilidad del empleado. Por favor, inténtalo de nuevo.");
                    return;
                }

                alert(`Lo sentimos, el empleado seleccionado no está disponible en el horario seleccionado. Por favor, selecciona otro horario.`);
                return;
            }

            services.push({
                date: calendarInput.value,
                time: timeSelected.value,
                serviceId: parseInt(serviceSelect.value),
                employeeId: parseInt(employeeSelect.value)
            });
        }

        // 4. Enviar la solicitud de reserva al servidor
        const response = await fetch('/setup_files/process_reservation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                customer: customerData,
                services: services
            })
        });

        const result = await response.json();

        if (result.success) {
            alert("¡Reserva realizada con éxito!");
            resetForm();
            reservationModal.classList.add("hidden");
        } else {
            alert ('Error: ' + (result.message || 'No se pudo procesar la reserva'));
        }

    } catch (error) {
        console.error('Error al procesar la reserva:', error);
        alert('Error al procesar la reserva. Por favor, inténtalo de nuevo.');
    }
});
});