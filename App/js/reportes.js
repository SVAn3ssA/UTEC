document.addEventListener("DOMContentLoaded", function () {
    // Inicializar DataTable
    if (document.getElementById('historial')) {
        $('#historial').DataTable({
            responsive: true,
            ajax: {
                url: APP_URL + "reportes/historial",
                dataSrc: ''
            },
            columns: [
                { data: "carnet", searchable: true, className: "text-center" },
                { data: "fechahora", searchable: false, className: "text-center" },
                { data: "tiempo", searchable: false, className: "text-center" },
                { data: "observacion", searchable: false, className: "text-center" },
                { data: "no_laboratorio", searchable: true, className: "text-center" },
                { data: "no_pc", searchable: true, className: "text-center" },
            ],
            columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5], orderable: false
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
                    // Cambiar la acción del formulario a generar PDF
                    form.action = APP_URL + "reportes/generarPdf";
                    // Enviar el formulario
                    form.submit();
                }
            },
            {
                text: '<button id="excelButton" class="btn btn-success"><i class="fa fa-file-excel"></i></button>',
                action: function () {
                    // Obtener el formulario por su ID
                    var form = document.getElementById('reporteForm');
                    // Cambiar la acción del formulario a generar Excel
                    form.action = APP_URL + "reportes/generarXls";
                    // Enviar el formulario
                    form.submit();
                }
            }
            ]
        });
    }

    // Inicializar funciones de toggleInputs si los elementos existen
    if (document.getElementById('tipo_reporte')) {
        toggleInputs();
    }
});

function toggleInputs() {
    const tipoReporte = document.getElementById('tipo_reporte') ? document.getElementById('tipo_reporte').value : null;
    const otraLista = document.getElementById('otra_lista');
    const fechaRango = document.getElementById('fecha_rango');
    const anioInput = document.getElementById('anio_input');
    const cicloInput = document.getElementById('ciclo_input');
    const diaInput = document.getElementById('dia_input');
    const listaGeneral = document.getElementById('lista_general');
    const numeroLaboratorioGroup = document.getElementById('numero_laboratorio_group');

    if (fechaRango) fechaRango.style.display = 'none';
    if (anioInput) anioInput.style.display = 'none';
    if (cicloInput) cicloInput.style.display = 'none';
    if (diaInput) diaInput.style.display = 'none';
    if (listaGeneral) listaGeneral.style.display = 'none';
    if (numeroLaboratorioGroup) numeroLaboratorioGroup.style.display = 'block';

    if (tipoReporte) {
        document.getElementById('desde').value = '';
        document.getElementById('hasta').value = '';
        document.getElementById('anio').value = '';
        document.getElementById('ciclo1').checked = false;
        document.getElementById('ciclo2').checked = false;
        document.getElementById('dia').value = '';

        if (tipoReporte === 'rango') {
            fechaRango.style.display = 'block';
        } else if (tipoReporte === 'anio') {
            anioInput.style.display = 'block';
        } else if (tipoReporte === 'ciclo') {
            anioInput.style.display = 'block';
            cicloInput.style.display = 'block';
        } else if (tipoReporte === 'dia') {
            diaInput.style.display = 'block';
        } else if (tipoReporte === 'general') {
            listaGeneral.style.display = 'block';
            numeroLaboratorioGroup.style.display = 'none';
            toggleOtraLista();
        }
    }
}

function toggleOtraLista() {
    const otraLista = document.getElementById('otra_lista') ? document.getElementById('otra_lista').value : null;
    const fechaRango = document.getElementById('fecha_rango');
    const anioInput = document.getElementById('anio_input');
    const cicloInput = document.getElementById('ciclo_input');
    const diaInput = document.getElementById('dia_input');

    if (fechaRango) fechaRango.style.display = 'none';
    if (anioInput) anioInput.style.display = 'none';
    if (cicloInput) cicloInput.style.display = 'none';
    if (diaInput) diaInput.style.display = 'none';

    if (otraLista) {
        document.getElementById('desde').value = '';
        document.getElementById('hasta').value = '';
        document.getElementById('anio').value = '';
        document.getElementById('ciclo1').checked = false;
        document.getElementById('ciclo2').checked = false;
        document.getElementById('dia').value = '';

        if (otraLista === 'rango') {
            fechaRango.style.display = 'block';
        } else if (otraLista === 'anio') {
            anioInput.style.display = 'block';
        } else if (otraLista === 'ciclo') {
            anioInput.style.display = 'block';
            cicloInput.style.display = 'block';
        } else if (otraLista === 'dia') {
            diaInput.style.display = 'block';
        }
    }
}
