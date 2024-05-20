<?php

class AgregarUsuario extends controller
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

        // Validaciones del lado del servidor
        if ($this->validarCampos($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)) {
            $resultados = $this->modelo->insertarUsuario($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio);

            if ($resultados === "OK") {
                $mensaje = "SI";
            } elseif ($resultados === "existe") {
                $mensaje = "El email ya existe";
            } else {
                $mensaje = "Error al registrar: " . $resultados;
            }
        } else {
            $mensaje = "Validación fallida. Revisa los datos ingresados.";
        }

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }

    private function validarCampos($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)
    {
        if (
            empty($nombres) || empty($apellidos) || empty($email) || empty($password) || empty($telefono) ||
            empty($estado) || empty($id_privilegio) || empty($no_laboratorio)
        ) {
            echo json_encode("Todos los campos son obligatorios", JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombres) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellidos)) {
            echo json_encode("Los nombres y apellidos solo pueden contener letras y espacios", JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode("El formato del email es incorrecto", JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (!preg_match('/^\d{8}$/', $telefono)) {
            echo json_encode("El formato del teléfono es incorrecto. Debe ser un número de 8 dígitos", JSON_UNESCAPED_UNICODE);
            return false;
        }

        return true;
    }
}
