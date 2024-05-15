<?php
    spl_autoload_register(function($clase){
        if(file_exists("App/".$clase.".php")){
            require_once "App/".$clase.".php";
        }
    })
?>