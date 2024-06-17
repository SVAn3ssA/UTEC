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
    if ($_SESSION['privilegio'] == 1 && $_SESSION['no_laboratorio'] == 0) {
        echo '<div class="text-center mt-4">
                <img src="App/images/Temaletras.png" alt="Temas Letras" style="width: 65%;">
                </div>';
    } else {
    ?>
        <?php if ($_SESSION['privilegio'] == 1 || $_SESSION['no_laboratorio'] != 0) : ?>
            <h5 class="nombre-lab text-center">Laboratorio: <?= $_SESSION['no_laboratorio']; ?></h5>
        <?php endif; ?>
        <div class="contenedor">
        <div class="left-column">
  <div class="form-container">
    <div class="form-groupset">
      <form id="buscarForm" class="formulario">
        <div class="form-group">
          <label class="form-label">Carnet</label>
          <input class="form-control" type="text" style="width: 200px;" id="carnet" name="carnet" pattern="[0-9]*" title="Por favor, ingrese solo números" required>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Buscar">
          <button type="button" class="btn btn-warning" id="cancelarBusqueda">Cancelar</button>
        </div>
      </form>

      <form method="post" id="frmdatosprestamo" class="formulario" onsubmit="registrarTiempo(event)">
        <input type="hidden" id="noLaboratorioSession" value="<?php echo isset($_SESSION['no_laboratorio']) ? $_SESSION['no_laboratorio'] : ''; ?>">
        <div class="form-group">
          <label class="form-label">No. PC</label>
          <input type="text" class="form-control" id="noPc" name="noPc" style="width: 200px;" required>
        </div>
        <div class="form-group">
          <button id="btnPrestar" class="btn btn-success" type="submit">Prestar</button>
        </div>
      </form>
    </div>
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
        <div class="container d-flex justify-content-center">
            <table class="table table-striped table-bordered text-center" id="resultsTable" style="display: none;">
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

        <div class="espacio2">
            <div class="table-responsive">
                <table id="registrosTable" class="table display nowrap table-bordered" style="width:100%">
                    <thead id="tbRegistroTiempo">
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
    <?php
    }
    ?>

    <?php require_once "./App/Views/inc/script.php"; ?>
    <?php
    if ($_SESSION['privilegio'] == 1 && $_SESSION['no_laboratorio'] == 0) {
        echo '<footer class="text-center mt-4">
                <p>Copyright © 2024 Universidad Tecnológica de El Salvador - UTEC - San Salvador, El Salvador, C.A.</p>
                </footer>';
    }
    ?>
</body>

</html>
