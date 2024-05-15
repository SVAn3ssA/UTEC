<script>
    const APP_URL = "<?php echo APP_URL; ?>";
</script>

<!---FUNCIONES--->
<script src="<?php echo APP_URL; ?>App/Views/js/bootstrap.bundle.min.js"></script>

<!---FUNCIONES--->
<script src="<?php echo APP_URL; ?>App/Views/js/funciones.js"></script>

<!---JQUERY---><!---DATATABLE--->
<script src="<?php echo APP_URL; ?>App/Views/js/jquery-3.7.1.js"></script>
<script src="<?php echo APP_URL; ?>App/Views/js/dataTables.js"></script>

<script>
$(document).ready(function() {
    // Configurar DataTables para la tabla con el id 'registrosTable'
    $('#registrosTable').DataTable({
        "ajax": {
            "url": "<?php echo APP_URL; ?>inicio/listarEstudiantesTiempo",
            "type": "POST",
            "dataSrc": "" // No es necesario si el JSON devuelto ya es un array
        },
        "columns": [
            { "data": "id_registro" },
            { "data": "carnet" },
            { "data": "no_laboratorio" },
            { "data": "no_pc" }
        ],
        "language": {
            "decimal":        "",
            "emptyTable":     "No hay datos disponibles en la tabla",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
            "infoFiltered":   "(filtrado de _MAX_ registros totales)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "zeroRecords":    "No se encontraron registros coincidentes",
            "aria": {
                "sortAscending":  ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        },
    });
});
</script>
