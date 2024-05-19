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
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            orderable: false,
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


    const url = APP_URL + "usuario/agregar";
    const frm = document.getElementById("frmUsuarios");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    const formData = new FormData(frm);
    formData.append('nombres_usuario', nombres_usuario); 
    formData.append('apellidos_usuario', apellidos_usuario); 
    formData.append('email_usuario', email_usuario);
    formData.append('passwordInput', passwordInput); 
    formData.append('telefono_usuario', telefono_usuario); 
    formData.append('privilegio_usuario', privilegio_usuario);
    formData.append('laboratorio_usuario', laboratorio_usuario); 
    formData.append('estado_usuario', estado_usuario); 
    http.send(formData);
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
}


function btnModificarUsuario($usu) {
    $("#prueba").modal("show");
    const url = APP_URL + "listaUsuario/modificarUsuario/" + usu ;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
}