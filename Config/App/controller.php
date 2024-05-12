<?php

class controller
{
    protected $modelo;
    protected $vista;

    public function __construct()
    {
        $this ->vista = new views();
        $this->cargarModelo();
    }

    public function cargarModelo()
    {

        $modelo = get_class($this) . "Model";
        $ruta = "Models/" . $modelo . ".php";
        if (file_exists($ruta)) {
            require_once $ruta;
            $this->modelo = new $modelo();
        }
    }
}
