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
    <div class="espacio"></div>

    <div class="contenedor">
        <div class="left-column">
            <div class="form-container">
                <form id="buscarForm" class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ingrese carnet:</label>
                        <input class="form-control" type="text" id="carnet" name="carnet" required>
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
                <?php for ($i = 1; $i <= 50; $i++) : ?>
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