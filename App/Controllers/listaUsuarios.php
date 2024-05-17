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
            if($registros[$i]['estado'] == 1){
                $registros[$i]['estado'] = '<span style="color: green;">Activo</span>';
            }else{
                $registros[$i]['estado'] = '<span style="color: red;">Inactivo</span>';
            }
           
            $registros[$i]['acciones'] =
                '<div>
                    <button class="btn btn-primary" type="button" onclick="btnModificarUsuario('.$registros[$i]['id_usuario'].');">Modificar</button>
                </div>';
        }
        echo json_encode($registros, JSON_UNESCAPED_UNICODE);
    }
}
