<?php

class agregarusuario extends controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function index()
    {
        $privilegio = $this->modelo->listarPrivilegios()->fetchAll(PDO::FETCH_ASSOC);
        $laboratorio = $this->modelo->listarLaboratorios()->fetchAll(PDO::FETCH_ASSOC);

        $datos = ['privilegio' => $privilegio, 'laboratorio' => $laboratorio];

        $this->vista->obtenerVista($this, "index", $datos);
    }

    public function agregarUsuario()
    {
        $nombres = $_POST['nombres_usuario'];
        $apellidos = $_POST['apellidos_usuario'];
        $email = $_POST['email_usuario'];
        $password = $_POST['passwordInput'];
        $telefono = $_POST['telefono_usuario'];
        $estado = $_POST['estado_usuario'];
        $id_privilegio = $_POST['privilegio_usuario'];
        $no_laboratorio = $_POST['laboratorio_usuario'];

        $resultados = $this->modelo->insertarUsuario($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio);

        if ($resultados === "OK") {
            $mensaje = "SI";
        } else if ($resultados === "existe") {
            $mensaje = "El email ya existe";
        } else {
            $mensaje = "Error al registrar: " . $resultados; // Mostrar mensaje de error claro
        }

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }
}
