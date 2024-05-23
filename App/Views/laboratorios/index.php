<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>
    <div class="contenido d-flex justify-content-center"></div>

    <button type="button" class="btn btn-primary btnnuevo" id="frmLaboratorio" onclick="frmLab();">Nuevo</button>


    <div id="modalLaboratorio" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo">Agregar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar un nuevo laboratorio -->
                    <form method="post" id="formLaboratorio">
                        <input type="hidden" id="no_laboratorio" name="no_laboratorio">
                        <div class="form-group mb-4">
                            <label for="noLaboratorio">No. Laboratorio</label>
                            <input type="text" class="form-control" id="noLaboratorio" name="noLaboratorio">
                        </div>
                        <div class="form-group mb-4">
                            <label for="noPc">No. Pc</label>
                            <input type="text" class="form-control" id="noPc" name="noPc">
                        </div>
                        <div class="form-group mb-4">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
                        </div>
                        <div class="form-group mb-4">
                            <label for="programas">Programas</label>
                            <textarea class="form-control" id="programas" name="programas" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="registrarLaboratorio(event)" id="btnAccionGuardar">Guardar</button>
                        <button type="submit" class="btn btn-primary" onclick="modifirLaboratorio(event)" id="btnAccionModificar">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-4 mx-auto" style="max-width: 90%;">
        <table id="listaLaboratorios" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>No. Laboratorio</th>
                    <th>No. Pc</th>
                    <th>Descripción</th>
                    <th>Programas</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los datos de los registros -->
                <!-- Agrega más filas si es necesario -->
            </tbody>
        </table>
    </div>


    <?php require_once "./app/views/inc/script.php"; ?>
</body>

</html>