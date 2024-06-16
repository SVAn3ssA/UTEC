<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEC</title>

    <?php require_once "./App/Views/inc/head.php"; ?>
</head>

<body class="login-page">
    <div class="login-contenedor">
        <div class="login-contenido">
            <img src="<?php echo APP_URL; ?>App/images/logo_utec_solo_letras.png" class="imagen" alt="Logo">
            <p class="text-center">
                Iniciar sesión
            </p>
            <form id="frmLogin" action="" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="email_usuario" name="email_usuario" autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="password_usuario" name="password_usuario" autocomplete="current-password">
                </div>
                <div class="alert alert-danger text-center d-none" id="alerta" role="alert"></div>
                <p>
                    <button type="submit" class="btn-login" onclick="frmLogin(event);">Iniciar sesión</button>
                </p>
            </form>
        </div>
    </div>
    <?php require_once "./App/Views/inc/script.php"; ?>
</body>

</html>