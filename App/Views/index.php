<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEC</title>

    <!-- Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Estilos-->
    <link rel="stylesheet" href="App/Views/css/estilos.css">
</head>

<body class="login-page">
    <div class="login-contenedor">
        <div class="login-contenido">
            <img src="<?php echo APP_URL; ?>App/Views/images/logo_utec_solo_letras.png" class="imagen" alt="Logo">
            <p class="text-center">
                Iniciar sesión
            </p>
            <form id="frmLogin" action="" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="email_usuario" name="email_usuario">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" id="password_usuario" name="password_usuario">
                </div>
                <p>
                    <button type="submit" class="btn-login" onclick="frmLogin(event);">Iniciar sesión</button>
                </p>
            </form>
        </div>
    </div>
    <script>
        const APP_URL="<?php echo APP_URL; ?>";
    </script>
    <!---FUNCIONES--->
    <script src="<?php echo APP_URL; ?>app/views/js/funciones.js"></script>
</body>

</html>