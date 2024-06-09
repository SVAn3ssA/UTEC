<?php

class AgregarUsuario extends controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    private function verificarSesion()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . APP_URL);
            exit();
        }
    }
    public function index()
    {
        $this->verificarSesion();
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

        // Validaciones del lado del servidor
        $mensajeError = $this->validarCampos($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio);
        if (!empty($mensajeError)) {
            echo json_encode($mensajeError, JSON_UNESCAPED_UNICODE);
            die();
        }

        $resultados = $this->modelo->insertarUsuario($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio);

        if ($resultados === "OK") {
            echo json_encode("SI", JSON_UNESCAPED_UNICODE);
        } elseif ($resultados === "existe") {
            echo json_encode("El email ya existe", JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode("Error al registrar: " . $resultados, JSON_UNESCAPED_UNICODE);
        }

        die();
    }


    private function validarCampos($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio)
    {
        if (
            empty($nombres) || empty($apellidos) || empty($email) || empty($password) || empty($telefono) ||
            empty($estado) || empty($id_privilegio)
        ) {

            return "Todos los campos son obligatorios";
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombres) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellidos)) {

            return "Los nombres y apellidos solo pueden contener letras y espacios";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El formato del email es incorrecto";
        }

        if (!preg_match('/^\d{8}$/', $telefono)) {

            return "El formato del teléfono es incorrecto. Debe ser un número de 8 dígitos";
        }

        return "";
    }
}
