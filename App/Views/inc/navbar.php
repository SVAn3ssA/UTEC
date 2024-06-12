<nav class="navbar navbar-expand-lg navbar-dark menu fixed-top">
    <div class="container-fluid">
        <img src="<?php echo APP_URL; ?>/app/views/images/UTEClogo.png" alt="Inicio" width="50" height="50" class="d-inline-block align-top" loading="lazy">
        <a class="navbar-brand" href="<?php echo APP_URL; ?>inicio">INICIO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if ($_SESSION['privilegio'] == 1): ?>
                
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>agregarusuario">Agregar Usuario</a>
                    </li>

                    <li>
                        <a class="nav-link" href="<?php echo APP_URL; ?>listausuarios">Lista de usuarios</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>laboratorios">Laboratorios</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>reportes">Reportes</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['nombres'] . " " . $_SESSION["apellidos"]; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" id="" href="<?php echo APP_URL . "modificarusuario/" . $_SESSION['id'] . "/"; ?>"></a></li>
                        <li><a class="dropdown-item" id="btn_cerrarSesion" href="<?php echo APP_URL . "inicio/cerrarSesion"; ?>">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
