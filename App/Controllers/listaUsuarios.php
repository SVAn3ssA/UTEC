<?php
class ListaUsuarios extends controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    private function verificarSesion()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . APP_URL); // Redirigir a la vista de inicio de sesión si no hay sesión
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


    public function listarUsuarios()
    {
        $registros = $this->modelo->listarUsuarios();
        for ($i = 0; $i < count($registros); $i++) {
            if ($registros[$i]['estado'] == 1) {
                $registros[$i]['estado'] = '<span style="color: green;">Activo</span>';
            } else {
                $registros[$i]['estado'] = '<span style="color: red;">Inactivo</span>';
            }
            $registros[$i]['acciones'] =
                '<div>
                    <button class="btn btn-primary" type="button" onclick="btnSeleccionarUsuario(' . $registros[$i]['id_usuario'] . ');">Modificar</button>
                </div>';
        }
        echo json_encode($registros, JSON_UNESCAPED_UNICODE);
    }

    public function seleccioanrUsuarios($id)
    {
        $usuario = $this->modelo->seleccionarUsuario($id);
        echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
    }

    public function modificarUsuarios()
    {
        // Validar campos del lado del servidor
        $mensajeError = $this->validarCampos();
        if (!empty($mensajeError)) {
            echo json_encode($mensajeError);
            die();
        }

        // Obtener datos del formulario
        $id_usuario = $_POST['id'];
        $nombres = $_POST['nombres_usuario'];
        $apellidos = $_POST['apellidos_usuario'];
        $email = $_POST['email_usuario'];
        $password = $_POST['passwordInput'];
        $telefono = $_POST['telefono_usuario'];
        $estado = $_POST['estado_usuario'];
        $id_privilegio = $_POST['privilegio_usuario'];
        $no_laboratorio = $_POST['laboratorio_usuario'];

        // Llamar al método para modificar el usuario
        $resultados = $this->modelo->modificarUsuario($id_usuario, $nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio);

        // Enviar el mensaje de resultado al cliente
        if ($resultados) {
            $mensaje = "MODIFICADO";
        } else {
            $mensaje = "El correo electrónico ya está en uso por otro usuario";
        }

        echo json_encode($mensaje);
        die();
    }

    private function validarCampos()
    {
        // Validar que todos los campos necesarios estén presentes y no estén vacíos
        if (
            empty($_POST['id']) || empty($_POST['nombres_usuario']) || empty($_POST['apellidos_usuario']) ||
            empty($_POST['email_usuario']) || empty($_POST['telefono_usuario']) || !isset($_POST['estado_usuario']) ||
            empty($_POST['privilegio_usuario'])
        ) {
            return "Todos los campos son obligatorios";
        }

        // Validar el formato del correo electrónico
        if (!filter_var($_POST['email_usuario'], FILTER_VALIDATE_EMAIL)) {
            return "El formato del correo electrónico es inválido";
        }

        // Validar el formato del teléfono (solo números y 8 dígitos)
        if (!preg_match('/^\d{8}$/', $_POST['telefono_usuario'])) {
            return "El formato del teléfono es incorrecto. Debe ser un número de 8 dígitos";
        }

        // Si no hay errores, devolver una cadena vacía
        return "";
    }
}
