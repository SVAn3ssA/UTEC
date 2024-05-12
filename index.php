<?php
require_once "Config/config.php";
$ruta = !empty($_GET['url']) ? $_GET['url'] : "home/index";
$array = explode("/", $ruta);
$controlador = $array[0];
$metodo = "index";
$parametro = "";
if (!empty($array[1])) {
    if (!empty($array[1] != "")) {
        $metodo = $array[1];
    }
}
if (!empty($array[2])) {
    if (!empty($array[2] != "")) {
        for ($i = 2; $i < count($array); $i++) {
            $parametro .= $array[$i] . ",";
        }
        $parametro = trim($parametro, ",");
    }
}
require_once "Config/App/autoload.php";
$dirControlador = "Controllers/" . $controlador . ".php";
if (file_exists($dirControlador)) {
    require_once $dirControlador;
    $controlador = new $controlador();
    if (method_exists($controlador, $metodo)) {
        $controlador->$metodo($parametro);
    } else {
        echo "No existe el metodo";
    }
} else {
    echo "No existe el controlador";
}
?>