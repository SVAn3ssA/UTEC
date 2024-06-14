let tblUsuarios;
document.addEventListener("DOMContentLoaded", function () {
    tblUsuarios = $('#lista').DataTable({
        responsive: true,
        ajax: {
            url: APP_URL + "listaUsuarios/listarUsuarios",
            dataSrc: ''
        },
        columns: [
            { data: "id_usuario", searchable: false, className: "text-center" },
            { data: "nombres", searchable: true},
            { data: "apellidos", searchable: true},
            { data: "email", searchable: true},
            { data: "telefono", searchable: false, className: "text-center" },
            { data: "estado", searchable: true, className: "text-center" },
            { data: "privilegio", searchable: false, className: "text-center" },
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


function btnSeleccionarUsuario(id) {
    const url = APP_URL + "listaUsuarios/seleccioanrUsuarios/" + id;
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

            document.getElementById("privilegio_usuario").value = usuario.id_privilegio;

            document.getElementById("laboratorio_usuario").value = usuario.no_laboratorio;

            if (usuario.estado === 1) {
                document.getElementById("estado_usuario1").checked = true;
            } else {
                document.getElementById("estado_usuario2").checked = true;
            }

            $("#modalUsuarios").modal("show");

        }
    }

}


function modificarUsuario(e) {
    e.preventDefault();

    const url = APP_URL + "listaUsuarios/modificarUsuarios";
    const frm = document.getElementById("frmprueba");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            const respuesta = JSON.parse(this.responseText);
            if (respuesta.mensaje === "MODIFICADO") {
                $("#modalUsuarios").modal('hide');
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Usuario modificado con éxito",
                    showConfirmButton: false,
                    timer: 2000
                });
                frm.reset();
                tblUsuarios.ajax.reload();

                // Actualizar el número de computadoras en la UI
                const num_pcs = respuesta.num_pcs;
                const computadorasDiv = document.getElementById("computadoras");
                computadorasDiv.innerHTML = ""; // Limpiar contenido actual
                for (let i = 1; i <= num_pcs; i++) {
                    const computadoraDiv = document.createElement("div");
                    computadoraDiv.classList.add("computadora");
                    computadoraDiv.setAttribute("data-pc", i);

                    const etiquetaDiv = document.createElement("div");
                    etiquetaDiv.classList.add("etiqueta");
                    etiquetaDiv.textContent = "PC " + i;

                    const cuadroDiv = document.createElement("div");
                    cuadroDiv.classList.add("cuadro");

                    computadoraDiv.appendChild(etiquetaDiv);
                    computadoraDiv.appendChild(cuadroDiv);
                    computadorasDiv.appendChild(computadoraDiv);
                }
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: respuesta.mensaje,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }
    };
}



