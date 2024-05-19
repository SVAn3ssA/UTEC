let tblUsuarios;
document.addEventListener("DOMContentLoaded", function () {
    tblUsuarios = $('#lista').DataTable({
        responsive: true,
        ajax: {
            url: APP_URL + "listaUsuarios/tabla",
            dataSrc: ''
        },
        columns: [
            { data: "id_usuario", searchable: false, className: "text-center" },
            { data: "nombres", searchable: true},
            { data: "apellidos", searchable: true},
            { data: "email", searchable: true},
            { data: "telefono", searchable: true, className: "text-center" },
            { data: "estado", searchable: true, className: "text-center" },
            { data: "privilegio", searchable: true, className: "text-center" },
            { data: "no_laboratorio", searchable: false, className: "text-center" },
            { data: "acciones",  className: "text-center" }
        ],
        columnDefs: [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
            "orderable": false,
        }],
        language: {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "aria": {
                "sortAscending": ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"

            }
        }
    });
});


function registrarUsuario(e) {
    e.preventDefault();
    const nombres_usuario = document.getElementById("nombres_usuario").value;
    const apellidos_usuario = document.getElementById("apellidos_usuario").value;
    const email_usuario = document.getElementById("email_usuario").value;
    const passwordInput = document.getElementById("passwordInput").value;
    const telefono_usuario = document.getElementById("telefono_usuario").value;
    const privilegio_usuario = document.getElementById("privilegio_usuario").value;
    const laboratorio_usuario = document.getElementById("laboratorio_usuario").value;
    const estado_usuario = document.getElementById("estado_usuario").value;
    if (nombres_usuario == "" || apellidos_usuario == "" || email_usuario == "" || passwordInput == "" || telefono_usuario == "" ||
        privilegio_usuario == "" || laboratorio_usuario == "" || estado_usuario == "") {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Todos los campos son obligatorios",
            showConfirmButton: false,
            timer: 2000
        });
    }
    else {
        const url = APP_URL + "agregarusuario/agregarUsuario";
        const frm = document.getElementById("frmUsuarios");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const mensaje = JSON.parse(this.responseText);
                if (mensaje == "SI") {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Usuario registrado con éxito",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    frm.reset();
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: mensaje,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        }
    }
}


function btnModificarUsuario(id) {
    const url = APP_URL + "listaUsuarios/modificarUsuario/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            const usuario = res[0];
            document.getElementById("id").value = usuario.id_usuario;
            document.getElementById("nombres_usuario").value = usuario.nombres;
            document.getElementById("apellidos_usuario").value = usuario.apellidos;
            document.getElementById("email_usuario").value = usuario.email;
            document.getElementById("passwordInput").value = usuario.password;
            document.getElementById("telefono_usuario").value = usuario.telefono;

            // Set the privilege select value
            document.getElementById("privilegio_usuario").value = usuario.id_privilegio;

            // Set the lab number select value
            document.getElementById("laboratorio_usuario").value = usuario.no_laboratorio;

            // Set the state radio buttons
            if (usuario.estado === 1) {
                document.getElementById("estado_usuario1").checked = true;
            } else {
                document.getElementById("estado_usuario2").checked = true;
            }

            $("#prueba").modal("show");

        }
    }

}


function modificarUsuario(e) {
    e.preventDefault();

    // Obtener los valores de los campos del formulario
    const id = document.getElementById("id").value;
    const nombres_usuario = document.getElementById("nombres_usuario").value;
    const apellidos_usuario = document.getElementById("apellidos_usuario").value;
    const email_usuario = document.getElementById("email_usuario").value;
    const passwordInput = document.getElementById("passwordInput").value;
    const telefono_usuario = document.getElementById("telefono_usuario").value;
    const privilegio_usuario = document.getElementById("privilegio_usuario").value;
    const laboratorio_usuario = document.getElementById("laboratorio_usuario").value;

    let estado_usuario;
    const radios = document.querySelectorAll('input[name="estado_usuario"]');
    for (const radio of radios) {
        if (radio.checked) {
            estado_usuario = radio.id; // Usar el id del radio button
            break;
        }
    }


    // Validar que todos los campos estén llenos
    if (nombres_usuario === "" || apellidos_usuario === "" || email_usuario === "" || telefono_usuario === "" ||
        privilegio_usuario === "" || laboratorio_usuario === "") {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Todos los campos son obligatorios",
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        const url = APP_URL + "listaUsuarios/prueba";
        const frm = document.getElementById("frmprueba");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                const mensaje = JSON.parse(this.responseText);
                if (mensaje === "MODIFICADO") {
                    $("#prueba").modal('hide');
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Usuario modificado con éxito",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    frm.reset();
                    tblUsuarios.ajax.reload();
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: mensaje,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        };
    }
}

