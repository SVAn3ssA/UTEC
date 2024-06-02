let registrosFinalizados = [];

document.addEventListener("DOMContentLoaded", function () {
    // Cargar la tabla y los cuadros de las computadoras
    tblRegistro = $('#registrosTable').DataTable({
        responsive: true,
        ajax: {
            url: APP_URL + "inicio/listar",
            dataSrc: ''
        },
        columns: [
            { data: "id_registro", searchable: false, className: "text-center" },
            { data: "carnet", searchable: true, className: "text-center" },
            { data: "no_pc", searchable: false, className: "text-center" },
            { data: "observacion", searchable: false, className: "text-center" },
            { data: "acciones", className: "text-center" }
        ],
        columnDefs: [{
            targets: [0, 1, 2, 3, 4], orderable: false
        }],
        language: {
            decimal: "",
            emptyTable: "No hay datos disponibles en la tabla",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            aria: {
                sortAscending: ": activar para ordenar la columna ascendente",
                sortDescending: ": activar para ordenar la columna descendente"
            }
        },
        initComplete: function () {
            // Obtener los cuadros de las computadoras
            const cuadros = document.querySelectorAll('.computadora .cuadro');

            // Limpiar colores previos de los cuadros
            cuadros.forEach(function (cuadro) {
                cuadro.style.backgroundColor = '';
            });

            // Iterar sobre los registros cargados en la tabla
            const registros = this.api().rows().data().toArray();
            registros.forEach(function (registro) {
                // Obtener el número de PC asignado
                const noPc = registro.no_pc;

                // Buscar el cuadro correspondiente a ese número de PC y ponerlo en rojo
                const pcElement = document.querySelector(`.computadora[data-pc="${noPc}"] .cuadro`);
                if (pcElement) {
                    pcElement.style.backgroundColor = 'red';
                }
            });
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    $('#historial').DataTable({
        responsive: true,
        ajax: {
            url: APP_URL + "reportes/historial",
            dataSrc: ''
        },
        columns: [
            { data: "carnet", searchable: true, className: "text-center" },
            { data: "fechahora", searchable: true, className: "text-center" },
            { data: "tiempo", searchable: true, className: "text-center" },
            { data: "observacion", searchable: false, className: "text-center" },
            { data: "no_laboratorio", searchable: false, className: "text-center" },
            { data: "no_pc", searchable: false, className: "text-center" },
        ],
        columnDefs: [{
            targets: [0, 1, 2, 3, 4], orderable: false
        }],
        language: {
            decimal: "",
            emptyTable: "No hay datos disponibles en la tabla",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            aria: {
                sortAscending: ": activar para ordenar la columna ascendente",
                sortDescending: ": activar para ordenar la columna descendente"
            }
        },
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row mt-3'<'col-sm-12'tr>>" + // Agregamos una clase de margen top (mt-3)
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [{
            text: '<button id="pdfButton" class="btn btn-danger"><i class="fa fa-file-pdf"></i></button>',
            action: function () {
                // Obtener el formulario por su ID
                var form = document.getElementById('reporteForm');

                // Enviar el formulario
                form.submit();
            }
        },
        {
            extend: 'excelHtml5',
            text: '<button class="btn btn-success"><i class="fa fa-file-excel"></i></button>',
            className: 'btn btn-success'
        }
        ]
    });
});






function frmLogin(e) {
    e.preventDefault();
    const email_usuario = document.getElementById("email_usuario");
    const password_usuario = document.getElementById("password_usuario");

    if (email_usuario.value == "") {
        password_usuario.classList.remove("is-invalid");
        email_usuario.classList.add("is-invalid");
        email_usuario.focus();
    } else if (password_usuario.value == "") {
        email_usuario.classList.remove("is-invalid")
        password_usuario.classList.add("is-invalid")
        password_usuario.focus();
    } else {
        const url = APP_URL + "inicio/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                if (res == "Ok") {
                    window.location = APP_URL + "inicio";
                } else {
                    document.getElementById("alerta").classList.remove("d-none");
                    document.getElementById("alerta").innerHTML = res;
                }
            }
        }
    }
}



function limpiarBusqueda() {
    // Limpiar el campo de búsqueda de carnet y el número de PC
    document.getElementById('carnet').value = '';
    document.getElementById('noPc').value = '';

    // Limpiar la tabla de resultados y ocultarla
    document.getElementById('resultsBody').innerHTML = '';
    document.getElementById('resultsTable').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    // Función para agregar event listeners
    function setupEventListeners() {
        var buscarForm = document.getElementById('buscarForm');
        if (buscarForm) {
            buscarForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var searchTerm = document.getElementById('carnet').value;
                if (searchTerm.length > 0) {
                    buscar(searchTerm);
                }
            });
        } else {
            console.log('El formulario de búsqueda no fue encontrado en esta página');
        }

        // Agregar event listener para el botón de limpiar búsqueda
        var limpiarBusquedaBtn = document.getElementById('cancelarBusqueda');
        if (limpiarBusquedaBtn) {
            limpiarBusquedaBtn.addEventListener('click', limpiarBusqueda);
        } else {
            console.log('El botón de cancelar búsqueda no fue encontrado en esta página');
        }

        // Resto del código de event listeners...
    }

    // Llamar a la función para agregar event listeners
    setupEventListeners();
});

function buscar(buscar) {
    fetch('inicio/buscar/' + buscar)
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            // Mostrar la tabla incluso si no se encontraron resultados
            document.getElementById('resultsTable').style.display = 'block';

            if (data.length === 0) {
                document.getElementById('resultsTable').style.display = 'none';
                // Mostrar alerta si no se encontraron resultados
                alert('Carnet no encontrado');
                document.getElementById('errorMessage').style.display = 'none';
            } else {
                // Guardar el carnet del primer estudiante en la variable global
                selectedCarnet = data[0].carnet;
                // Mostrar resultados en la tabla
                var resultadosHTML = '';
                data.forEach(function (value) {
                    resultadosHTML += '<tr><td>' + value.carnet + '</td><td>' + value.nombres + '</td><td>' + value.apellidos + '</td><td>' + value.carrera + '</td><td>' + value.facultad + '</td><td>' + value.telefono + '</td><td>' + value.email + '</td></tr>';
                });
                document.getElementById('resultsBody').innerHTML = resultadosHTML;
            }
        })
        .catch(function (error) {
            console.log('Error en la solicitud: ', error);
        });
}

function registrarTiempo(e) {
    e.preventDefault();

    const noLaboratorio = document.getElementById("noLaboratorioSession").value;
    const noPc = parseInt(document.getElementById("noPc").value, 10);

    if (noLaboratorio === "") {
        console.log('El número de laboratorio no está definido');
        return;
    }

    if (selectedCarnet === undefined || selectedCarnet === null) {
        console.log('No se ha seleccionado ningún carnet');
        return;
    }

    // Verificar si el número de PC es mayor que 0
    if (isNaN(noPc) || noPc <= 0) {
        alert('El número de PC debe ser mayor que 0');
        return;
    }

    // Verificar si existe el elemento PC especificado
    const pcElement = document.querySelector(`.computadora[data-pc="${noPc}"] .cuadro`);
    if (!pcElement) {
        alert('No se encontró la PC especificada');
        return; // Detener la ejecución de la función si no se encuentra la PC
    }

    const carnet = selectedCarnet; // Obtener el carnet seleccionado de la búsqueda
    const url = APP_URL + "inicio/registrar";
    const frm = document.getElementById("frmdatosprestamo");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    const formData = new FormData(frm);
    formData.append('carnet', carnet); // Agregar el carnet al formulario
    formData.append('noLaboratorio', noLaboratorio); // Agregar el número de laboratorio al formulario
    formData.append('noPc', noPc); // Agregar el número de PC al formulario
    http.send(formData);
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            console.log(response);

            if (response === "OK") {
                // Cambiar el color del cuadro correspondiente al número de PC
                pcElement.style.backgroundColor = 'red';

                // Recargar la tabla después de completar el préstamo
                tblRegistro.ajax.reload();
                // Limpiar la búsqueda después de registrar el tiempo
                limpiarBusqueda();
            } else {
                alert(response);
            }
        }
    }
}




function btnFinalizar(id_registro) {
    const observacion = document.getElementById("observacion_" + id_registro).value;
    const url = APP_URL + "inicio/modificar/" + id_registro + "?observacion=" + encodeURIComponent(observacion);
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            // Redireccionar a la página actual después de finalizar el registro
            window.location.reload();
        }
    }
}















