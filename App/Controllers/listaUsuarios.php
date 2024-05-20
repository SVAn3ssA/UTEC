<?php
class listaUsuarios extends controller
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
        if (
            empty($_POST['id']) || empty($_POST['nombres_usuario']) || empty($_POST['apellidos_usuario']) ||
            empty($_POST['email_usuario']) || empty($_POST['telefono_usuario']) || !isset($_POST['estado_usuario']) ||
            empty($_POST['privilegio_usuario']) || empty($_POST['laboratorio_usuario'])
        ) {
            $mensaje = "Todos los campos son obligatorios";
        } else {
            // Obtener los datos del formulario
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
            if ($resultados) {
                $mensaje = "MODIFICADO";
            } else {
                $mensaje = "El correo electrónico ya está en uso por otro usuario";
            }
        }

        echo json_encode($mensaje);
        die();
    }
}
