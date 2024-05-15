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
                }
            }
        }
    }
}


document.addEventListener('DOMContentLoaded', function () {
    // Evento de escucha para el formulario de búsqueda
    document.getElementById('buscarForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var searchTerm = document.getElementById('carnet').value;
        if (searchTerm.length > 0) {
            buscar(searchTerm);
        }
    });

    // Variable global para almacenar el carnet seleccionado
    var selectedCarnet;
    document.getElementById('resultsBody').addEventListener('click', function (e) {
        if (e.target && e.target.nodeName === 'TD') {
            // Obtener el carnet del estudiante seleccionado
            var carnet = e.target.parentElement.cells[0].textContent;
            // Guardar el carnet en la variable global
            selectedCarnet = carnet;
        }
    });


    // Evento de escucha para el botón de cancelar
    document.getElementById('cancelarBusqueda').addEventListener('click', function () {
        // Limpiar los campos de entrada
        document.getElementById('carnet').value = '';
        document.getElementById('noLaboratorio').value = '';
        document.getElementById('noPc').value = '';
        // Restablecer la tabla de resultados
        document.getElementById('resultsBody').innerHTML = '';
        // Ocultar la tabla y el mensaje de error
        document.getElementById('resultsTable').style.display = 'none';

    });

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
                document.getElementById('errorMessage').style.display = 'none';
            }
        })
        .catch(function (error) {
            console.log('Error en la solicitud: ', error);
        });
}


function registrarPrestamo(e) {
    const noLaboratorio = document.getElementById("noLaboratorio");
    const noPc = document.getElementById("noPc");
    if (!selectedCarnet) {
        console.log('No se ha seleccionado un estudiante');
    } else {
        const url = APP_URL + "inicio/registrar";
        const frm = document.getElementById("prestamoForm");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        http.send("noLaboratorio=" + encodeURIComponent(noLaboratorio.value) + "&noPc=" + encodeURIComponent(noPc.value) + "&carnet=" + encodeURIComponent(selectedCarnet));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        }
    }
}


