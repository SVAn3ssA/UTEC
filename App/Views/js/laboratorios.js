let tblLaboratorios
document.addEventListener("DOMContentLoaded", function () {
    tblLaboratorios = $('#listaLaboratorios').DataTable({
        responsive: true,
        ajax: {
            url: APP_URL + "laboratorios/listarLaboratorios",
            dataSrc: ''
        },
        columns: [
            { data: "no_laboratorio", searchable: true, className: "text-center" },
            { data: "no_pc", searchable: true, className: "text-center" },
            { data: "descripcion", searchable: true, className: "text-center" },
            { data: "programas", searchable: true, className: "text-center" },
            { data: "acciones", className: "text-center" },
        ],
        columnDefs: [{
            "targets": [0, 1, 2, 3,],
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

function frmLab() {
    $("#modalLaboratorio").modal('show');
    document.getElementById("titulo").innerHTML = "Agregar";
    document.getElementById("btnAccionGuardar").style.display = "block"; // Asegura que el botón de "Guardar" esté visible
    document.getElementById("btnAccionModificar").style.display = "none"; // Oculta el botón de "Modificar"
    document.getElementById("formLaboratorio").reset();
}



function registrarLaboratorio(e) {
    e.preventDefault();
    const url = APP_URL + "laboratorios/agregarLaboratorio";
    const frm = document.getElementById("formLaboratorio");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if (response === "SI") {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Laboratorio registrado con éxito",
                    showConfirmButton: false,
                    timer: 2000
                });
                frm.reset();
                $("#modalLaboratorio").modal('hide');
                tblLaboratorios.ajax.reload();
            }
            else {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }
    }
}

function modifirLaboratorio(e) {
    e.preventDefault();
    const url = APP_URL + "laboratorios/modificarLaboratorio";
    const frm = document.getElementById("formLaboratorio");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if (response === "MODIFICADO") {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Laboratorio modificado con éxito",
                    showConfirmButton: false,
                    timer: 2000
                });
                frm.reset();
                $("#modalLaboratorio").modal('hide');
                tblLaboratorios.ajax.reload();
            }
            else {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }
    }
}


function btnSeleccionarLab(id) {
    document.getElementById("btnAccionGuardar").style.display = "none"; // Oculta el botón de Guardar
    document.getElementById("titulo").innerHTML = "Modificar";
    document.getElementById("btnAccionModificar").style.display = "block"; // Muestra el botón de Modificar
    document.getElementById("btnAccionModificar").innerHTML = "Modificar";
    const url = APP_URL + "laboratorios/seleccionarLab/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            const laboratorios = res[0];
            document.getElementById("noLaboratorio").value = laboratorios.no_laboratorio;
            document.getElementById("noPc").value = laboratorios.no_pc;
            document.getElementById("descripcion").value = laboratorios.descripcion;
            document.getElementById("programas").value = laboratorios.programas;

            $("#modalLaboratorio").modal('show');
        }
    }
}
