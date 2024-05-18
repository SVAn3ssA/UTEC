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


    public function tabla()
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
                    <button class="btn btn-primary" type="button" onclick="btnModificarUsuario(' . $registros[$i]['id_usuario'] . ');">Modificar</button>
                </div>';
        }
        echo json_encode($registros, JSON_UNESCAPED_UNICODE);
    }

    public function modificarUsuario($id)
    {
        $registros = $this->modelo->modificarUser($id);
        echo json_encode($registros, JSON_UNESCAPED_UNICODE);
    }

    public function prueba()
    {
        if ($_POST['id'] == "") {
            $mensaje = "No se ha seleccionado ningun id";
        } else {
            $id_usuario = $_POST['id'];
            $nombres = $_POST['nombres_usuario'];
            $apellidos = $_POST['apellidos_usuario'];
            $email = $_POST['email_usuario'];
            $password = $_POST['passwordInput'];
            $telefono = $_POST['telefono_usuario'];
            $estado = $_POST['estado_usuario'];
            $id_privilegio = $_POST['privilegio_usuario'];
            $no_laboratorio = $_POST['laboratorio_usuario'];
            $resultados = $this->modelo->modificarUsuario($id_usuario, $nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio);
            if ($resultados) {
                $mensaje = "MODIFICADO"; 
            } else {
                $mensaje = "Error al modificar"; 
            }
        }
        echo json_encode($mensaje);
        die();
    }
}
