<?php
class views{
    public function obtenerVista($controlador, $vista)
    {
        $controlador = get_class($controlador);
        if($controlador == "home"){
            $vista = "App/Views/".$vista.".php";
        }else{
            $vista = "App/Views/".$controlador."/".$vista.".php";
        }
        require $vista;
    }
    
}


?>