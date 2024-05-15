<!DOCTYPE html>
<html lang="en">
<title>UTEC</title>

<head>
    <?php require_once "./App/Views/inc/head.php"; ?>
    <style>
        .fixed-container {
            position: fixed;
            top: 10px;
            right: 500px;
            width: 70%;
            /* Cambiar el valor según sea necesario */
            overflow-x: auto;
            /* Agregar desplazamiento horizontal si el contenido excede el ancho */
            transform: translateX(50%);
        }

        .fixed-container table {
            width: 100%;
            /* Establecer un ancho fijo para la tabla */
        }
    </style>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>

    <!--Buscador -->
    <div class="contenido">
        <form id="buscarForm">
            <div class="form-grup">
                <label class="form-label">Ingrese carnet:</label><br>
                <input class="inputbuscar" type="text" id="carnet" name="carnet" required>
            </div>
            <div class="botones">
                <input type="submit" class="btn btn-primary" value="Buscar">
                <button type="button" class="btn btn-warning" id="cancelarBusqueda">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- Tabla para mostrar los resultados del buscador-->
    <div class="tablaBuscador">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="resultsTable" style="display: none;">
                <thead class="thead-dark">
                    <tr>
                        <th>Carnet</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Carrera</th>
                        <th>Facultad</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody id="resultsBody">
                    <!-- Aquí irán los datos de la tabla -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulario para iniciar el préstamo de la máquina -->
    <div class="frmdatosprestamo">
        <form id="prestamoForm">
            <h3>Datos para préstamo de máquina</h3>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label">Laboratorio</label>
                    <input type="text" class="form-control" id="noLaboratorio" name="noLaboratorio" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">No. PC</label>
                    <input type="text" class="form-control" id="noPc" name="noPc" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <button class="btn btn-success" type="submit">Prestar</button>
                </div>
            </div>
        </form>
    </div>


    <div class="fixed-container">
        <div class="container contenido d-flex justify-content-center">
            <table id="registrosTable" class="display" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID Registro</th>
                        <th>Carnet</th>
                        <th>No. Laboratorio</th>
                        <th>No. PC</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán los datos de los registros -->
                </tbody>
            </table>
        </div>
    </div>


    <!-- Scripts -->
    <?php require_once "./app/views/inc/script.php"; ?>

</body>

</html>