<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>UTEC</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>
<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>

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

    <div class="contenedor-tabla"></div>

    <div class="tablaBuscador">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="resultsTable" style="display: none;">
                <thead class="thead-dark">
                    <tr>
                        <th><center>Carnet</center></th>
                        <th><center>Nombres</center></th>
                        <th><center>Apellidos</center></th>
                        <th><center>Carrera</center></th>
                        <th><center>Facultad</center></th>
                        <th><center>Teléfono</center></th>
                        <th><center>Email</center></th>
                    </tr>
                </thead>
                <tbody id="resultsBody">
                    <!-- Aquí irán los datos de la tabla -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="computadoras" id="computadoras">
        <?php
        $num_pcs = isset($_SESSION['num_pcs']) ? $_SESSION['num_pcs'] : 0;
        for ($i = 1; $i <= $num_pcs; $i++) :
        ?>
            <div class="computadora" data-pc="<?= $i ?>">
                <div class="etiqueta">PC <?= $i ?></div>
                <div class="cuadro"></div>
            </div>
        <?php endfor; ?>
    </div>

    <div id="frmdatosprestamo" class="frmdatosprestamo">
        <form method="post" id="prestamoForm" onsubmit="registrarTiempo(event)">
            <h4>Préstamo de máquina</h4>
            <input type="hidden" id="noLaboratorioSession" value="<?php echo isset($_SESSION['no_laboratorio']) ? $_SESSION['no_laboratorio'] : ''; ?>">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label">No. PC</label>
                    <input type="text" class="form-control" id="noPc" name="noPc" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <button id="btnPrestar" class="btn btn-success" type="submit">Prestar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-4 mx-auto" style="max-width: 90%;">
        <table id="registrosTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>
                        ID Registro
                    </th>
                    <th>
                        Carnet
                    </th>
                    <th>
                        No. PC
                    </th>
                    <th>
                        Observaciones
                    </th>
                    <th>
                        Acción
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los datos de los registros -->
            </tbody>
        </table>
    </div>

    <?php require_once "./app/views/inc/script.php"; ?>
</body>
</html>
