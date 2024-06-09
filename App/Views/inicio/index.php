<!DOCTYPE html>
<html lang="es">

<head>
    <title>UTEC</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>
    <div class="espacio"></div>

    <?php 
    if ($_SESSION['privilegio'] != 1) {
    echo '<h5 class="nombre-lab text-center">Laboratorio: ' . $_SESSION['no_laboratorio'] . '</h5>';
    }
    ?>
    <div class="contenedor">
        <div class="left-column">
            <div class="form-container">
                <form id="buscarForm" class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ingrese carnet:</label>
                        <input class="form-control" type="text" id="carnet" name="carnet" pattern="[0-9]*" title="Por favor, ingrese solo números" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Buscar">
                        <button type="button" class="btn btn-warning" id="cancelarBusqueda">Cancelar</button>
                    </div>
                </form>
                <form method="post" id="frmdatosprestamo" class="col-md-6" onsubmit="registrarTiempo(event)">
                    <input type="hidden" id="noLaboratorioSession" value="<?php echo isset($_SESSION['no_laboratorio']) ? $_SESSION['no_laboratorio'] : ''; ?>">
                    <div class="form-group">
                        <label class="form-label">No. PC</label>
                        <input type="text" class="form-control" id="noPc" name="noPc" required>
                    </div>
                    <div class="form-group">
                        <button id="btnPrestar" class="btn btn-success" type="submit">Prestar</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="resultsTable" style="display: none;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Carne</th>
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
        <div class="right-column">
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
        </div>
    </div>

    <div class="espacio2">
        <div class="table-responsive">
            <table id="registrosTable" class="table display nowrap table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Registro</th>
                        <th>Carnet</th>
                        <th>No. PC</th>
                        <th>Observaciones</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán los datos de los registros -->
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once "./App/Views/inc/script.php"; ?>
</body>

</html>