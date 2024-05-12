<?php
    spl_autoload_register(function($clase){
        if(file_exists("Config/App/".$clase.".php")){
            require_once "Config/App/".$clase.".php";
        }
    })
?>