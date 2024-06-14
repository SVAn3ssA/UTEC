<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>
    <div class="contenido d-flex justify-content-center"></div>
    <div class=" text-center mb-3">
                        <h3>Reportes</h3>
                    </div>
    <div class="container d-flex justify-content-center align-items-center">
        <form id="reporteForm" method="POST" target="_blank" class="w-100">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_reporte">Tipo de Reporte:</label>
                        <select name="tipo_reporte" id="tipo_reporte" class="form-control" onchange="toggleInputs()">
                            <option value="rango">Rango de Fechas</option>
                            <option value="anio">Año</option>
                            <option value="ciclo">Ciclo</option>
                            <option value="dia">Día</option>
                            <?php if ($_SESSION['privilegio'] == 1): ?>
                            <option value="general">General</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="row">
                        <!-- Segunda lista desplegable -->
                        <div id="lista_general" style="display: none;">
                            <div class="form-group">
                                <label for="otra_lista">Por:</label>
                                <select name="otra_lista" id="otra_lista" class="form-control" onchange="toggleOtraLista()">
                                    <option value="rango">Rango de Fechas</option>
                                    <option value="anio">Año</option>
                                    <option value="ciclo">Ciclo</option>
                                    <option value="dia">Día</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-group" id="numero_laboratorio_group">
                        <label for="numero_laboratorio">Número de Laboratorio</label>
                        <?php if ($_SESSION['privilegio'] == 1) : ?>
                            <!-- Si el usuario tiene privilegio 1, se muestra un input editable -->
                            <input type="number" name="numero_laboratorio" id="numero_laboratorio" class="form-control" value="<?php echo isset($_SESSION['no_laboratorio']) ? $_SESSION['no_laboratorio'] : ''; ?>">
                        <?php else : ?>
                            <!-- Si el usuario no tiene privilegio 1, se muestra un input de solo lectura -->
                            <input type="number" name="numero_laboratorio" id="numero_laboratorio" class="form-control" value="<?php echo isset($_SESSION['no_laboratorio']) ? $_SESSION['no_laboratorio'] : ''; ?>" readonly>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="col-md-3" id="fecha_rango">
                    <div class="form-group">
                        <label for="desde">Desde</label>
                        <input type="date" name="desde" id="desde" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input type="date" name="hasta" id="hasta" class="form-control">
                    </div>
                </div>
                <div class="col-md-3" id="anio_input">
                    <div class="form-group">
                        <label for="anio">Año</label>
                        <input type="number" name="anio" id="anio" class="form-control">
                    </div>
                </div>
                <div class="col-md-3" id="ciclo_input">
                    <div class="form-group">
                        <label for="ciclo">Ciclo</label>
                        <div class="form-check">
                            <input type="radio" name="ciclo" id="ciclo1" value="1" class="form-check-input">
                            <label for="ciclo1" class="form-check-label">Ciclo 1</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="ciclo" id="ciclo2" value="2" class="form-check-input">
                            <label for="ciclo2" class="form-check-label">Ciclo 2</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="dia_input">
                    <div class="form-group">
                        <label for="dia">Día</label>
                        <input type="date" name="dia" id="dia" class="form-control">
                    </div>
                </div>
            </div>
        </form>


    </div>


    <div class="card my-2">
        <div class="card">
            <div class="card-header bg-dark text-white text-center">
                Historial de prácticas libres en laboratorios de informática
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4 mx-auto" style="max-width: 90%;">
                <table id="historial" class="display nowrap table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Carnet</th>
                            <th>Fecha y Hora</th>
                            <th>Tiempo</th>
                            <th>Observacion</th>
                            <th>Laboratorio</th>
                            <th>Pc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se insertarán los datos de los registros -->
                        <!-- Agrega más filas si es necesario -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once "./app/views/inc/script.php"; ?>

</body>

</html>