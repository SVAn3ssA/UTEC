<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista_Usuarios</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>

    <div class="contenido d-flex justify-content-center"></div>
    
    <div class="table-responsive mt-4 mx-auto" style="max-width: 90%;">
        <table id="lista" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                    <th>Privilegio</th>
                    <th>No. Laboratorio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los datos de los registros -->
                <!-- Agrega más filas si es necesario -->
            </tbody>
        </table>
    </div>
    <div id="modalUsuarios" class="modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frmprueba">
                        <input type="hidden" id="id" name="id">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="nombres_usuario" name="nombres_usuario">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Apellidos:</label>
                                    <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email_usuario" name="email_usuario">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Contraseña:</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="passwordInput" name="passwordInput">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('passwordInput', 'eyeIcon')">
                                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Telefono:</label>
                                    <input type="text" class="form-control" id="telefono_usuario" name="telefono_usuario">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Privilegio:</label>
                                    <select class="form-select" id="privilegio_usuario" name="privilegio_usuario" required>
                                        <option value="" selected>Seleccione...</option>
                                        <?php foreach ($datos['privilegio'] as $row) { ?>
                                            <option value="<?php echo $row['id_privilegio']; ?>"><?php echo $row['privilegio']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Laboratorio:</label>
                                    <select class="form-select" id="laboratorio_usuario" name="laboratorio_usuario" required>
                                        <option value="0" selected>Seleccione...</option>
                                        <?php foreach ($datos['laboratorio'] as $row) { ?>
                                            <option><?php echo $row['no_laboratorio']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group" style="margin-top: 20px;"> <!-- Agrega margen superior -->
                                    <label class="form-check-label">Estado:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="estado_usuario1" value="1" name="estado_usuario" checked>
                                        <label class="form-check-label">
                                            Activo
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="estado_usuario2" value="0" name="estado_usuario" checked>
                                        <label class="form-check-label">
                                            Inactivo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button type="submit" class="btn btn-primary" onclick="modificarUsuario(event)">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once "./app/views/inc/script.php"; ?>
</body>

</html>