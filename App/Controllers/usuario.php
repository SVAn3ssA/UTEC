<?php

class usuario extends controller
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

        $datos = ['privilegio' => $privilegio,'laboratorio' => $laboratorio];

        $this->vista->obtenerVista($this, "index", $datos);
    }


    


    //controlador de los provilegios
    public function privilegioControlador()
    {
        $privilegios = $this->modelo->listarPrivilegios();
        return $privilegios;
    }

    public function laboratorioControlador()
    {
        $laboratorios = $this->modelo->listarLaboratorios();
        return $laboratorios;
    }


    public function mostrarPasswordDesencriptada($idUsuario)
    {
        $password = $this->modelo->obtenerPasswordDesencriptada($idUsuario);
        return $password;
    }

    /*----------  Controlador listar usuario  ----------*/
    public function listarUsuarioControlador()
    {
        $tabla = "";

        // Listar usuarios
        $datos = $this->modelo->listarUsuarios();

        $tabla .= '
            <div>
            <table class="table table-striped"  style="width:100%" id="tabla">
                <thead>
                    <tr>
                        <th><center>Id</center></th>
                        <th><center>Nombre</center></th>
                        <th><center>Email</center></th>
                        <th><center>Telefono</center></th>
                        <th><center>Estado</center></th>
                        <th><center>Privilegio</center></th>
                        <th><center>Laboratorio</center></th>
                        <th><center>Opciones</center></th>
                </tr>
            </thead>
            <tbody>
    ';

        foreach ($datos as $row) {
            $estado_texto = ($row['estado'] == 1) ? '<span style="color: green;">Activo</span>' : '<span style="color: red;">Inactivo</span>';
            $tabla .= '
                <tr>
                    <td><center>' . $row['id_usuario'] . '</center></td>
                    <td>' . $row['nombres'] . ' ' . $row['apellidos'] . '</td>
                    <td>' . $row['email'] . '</td>
                    <td><center>' . $row['telefono'] . '</center></td>
                    <td><center>' . $estado_texto . '</center></td>
                    <td><center>' . $row['privilegio'] . '</center></td>
                    <td><center>' . $row['no_laboratorio'] . '</center></td>
                    <td>
                        <center><a href="' . APP_URL . 'modificarUsuario/' . $row['id_usuario'] . '/">Actualizar</a></center>
                    </td>
                </tr>
            ';
        }

        $tabla .= '</tbody></table></div>';

        return $tabla;
    }

    
}