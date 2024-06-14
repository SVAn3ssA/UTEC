<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body>
    <?php require_once "./App/Views/inc/navbar.php"; ?>


    <div class="container mt-5">
        <div class="row justify-content-center contenido">
            <div class="card  p-3">
                <form class="" action="" method="post" id="frmUsuarios">

                    <div class=" text-center mb-3">
                        <h2>Agregar usuario</h2>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres_usuario" name="nombres_usuario">
                        </div>
                        <div class="col">
                            <label class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email_usuario" name="email_usuario">
                        </div>
                        <div class="col">
                            <label class="form-label">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="passwordInput" name="passwordInput">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('passwordInput', 'eyeIcon')">
                                    <i class="fas fa-eye-slash" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Telefono:</label>
                            <input type="text" class="form-control" id="telefono_usuario" name="telefono_usuario">
                        </div>
                        <div class="col">
                            <div>
                                <label class="form-label">Privilegio:</label>
                                <select class="form-select" id="privilegio_usuario" name="privilegio_usuario">
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
                            <div>
                                <label class="form-label">Laboratorio:</label>
                                <select class="form-select" id="laboratorio_usuario" name="laboratorio_usuario">
                                    <option value="" selected>Seleccione...</option>
                                    <?php
                                    foreach ($datos['laboratorio'] as $row) { ?>
                                        <option><?php echo $row['no_laboratorio']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <br>
                            <div>
                                <label class="form-check-label">Estado:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="estado_usuario" name="estado_usuario" value="1" checked>
                                    <label class="form-check-label">
                                        Activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" onclick="registrarUsuario(event)">Guardar</button>
                    </div>

                </form>
            </div>
        </div>

        <?php require_once "./app/views/inc/script.php"; ?>
</body>

</html>