<?php
class views{
    public function obtenerVista($controlador, $vista)
    {
        $controlador = get_class($controlador);
        if($controlador == "home"){
            $vista = "Views/".$vista.".php";
        }else{
            $vista = "Views/".$controlador."/".$vista.".php";
        }
        require $vista;
    }
}


?>