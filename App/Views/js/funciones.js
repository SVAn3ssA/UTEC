document.addEventListener("DOMContentLoaded", function () {
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
        }
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

        var resultsBody = document.getElementById('resultsBody');
        if (resultsBody) {
            resultsBody.addEventListener('click', function (e) {
                if (e.target && e.target.nodeName === 'TD') {
                    var carnet = e.target.parentElement.cells[0].textContent;
                    selectedCarnet = carnet;
                }
            });
        } else {
            console.log('El cuerpo de la tabla de resultados no fue encontrado en esta página');
        }

        var cancelarBusqueda = document.getElementById('cancelarBusqueda');
        if (cancelarBusqueda) {
            cancelarBusqueda.addEventListener('click', function () {
                document.getElementById('carnet').value = '';
                document.getElementById('noLaboratorio').value = '';
                document.getElementById('noPc').value = '';
                document.getElementById('resultsBody').innerHTML = '';
                document.getElementById('resultsTable').style.display = 'none';
            });
        } else {
            console.log('El botón de cancelar búsqueda no fue encontrado en esta página');
        }
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
                // Mostrar mensaje de error dentro de la tabla
                document.getElementById('resultsBody').innerHTML = '<tr><td colspan="7" style="text-align:center;">No se encontraron resultados.</td></tr>';
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
    const noPc = document.getElementById("noPc").value;

    if (noLaboratorio === "") {
        console.log('El número de laboratorio no está definido');
        return;
    }

    if (selectedCarnet === undefined || selectedCarnet === null) {
        console.log('No se ha seleccionado ningún carnet');
        return;
    }

    const carnet = selectedCarnet; // Obtener el carnet seleccionado de la búsqueda
    const url = APP_URL + "inicio/registrar";
    const frm = document.getElementById("prestamoForm");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    const formData = new FormData(frm);
    formData.append('carnet', carnet); // Agregar el carnet al formulario
    formData.append('noLaboratorio', noLaboratorio); // Agregar el número de laboratorio al formulario
    formData.append('noPc', noPc); // Agregar el número de PC al formulario
    http.send(formData);
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            // Cambiar el color del cuadro correspondiente al número de PC
            const pcElement = document.querySelector(`.computadora[data-pc="${noPc}"] .cuadro`);
            if (pcElement) {
                pcElement.style.backgroundColor = 'red';
            } else {
                alert('No se encontró la PC especificada');
            }

            // Recargar la tabla después de completar el préstamo
            tblRegistro.ajax.reload();
        }
    }
}

function btnFinalizar(id) {
    const observacion = document.getElementById("observacion_" + id).value;
    const url = APP_URL + "inicio/modificar/" + id + "?observacion=" + encodeURIComponent(observacion);
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            // Recargar la tabla solo cuando la solicitud haya finalizado correctamente
            tblRegistro.ajax.reload(function () {
                // Obtener el número de PC asociado al registro finalizado
                const table = $('#registrosTable').DataTable();
                const rowData = table.row("#row_" + id).data();
                const pcNumber = rowData.no_pc;
               
                // Buscar y quitar el color del cuadro de la PC asociada
                const pcElement = document.querySelector(`.computadora[data-pc="${pcNumber}"] .cuadro`);
                if (pcElement) {
                    pcElement.style.backgroundColor = ''; // Quitar el color de fondo
                } else {
                    alert('No se encontró la PC especificada');
                }
            });
        }
    }
}




