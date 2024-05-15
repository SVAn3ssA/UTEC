<?php
class inicio extends controller
{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function index()
    {
        $this->vista->obtenerVista($this, "index");
    }

    public function validar()
    {
        if (empty($_POST['email_usuario']) || empty($_POST['password_usuario'])) {
            $mensaje = "Los campos estan vacios";
        } else {
            $email = $_POST['email_usuario'];
            $password = $_POST['password_usuario'];
            $data = $this->modelo->getUsuario($email, $password);
            if ($data) {
                $_SESSION['id'] = $data['id_usuario'];
                $_SESSION['nombres'] = $data['nombres'];
                $_SESSION['apellidos'] = $data['apellidos'];
                $_SESSION['email'] = $data['email'];
                $mensaje = "Ok";
            } else {
                $mensaje = "Usuario o contrasenia incorrecta";
            }
        }
        echo json_encode($mensaje);
        die();
    }

    public function buscar($carnet)
    {
        // Realizar la búsqueda
        $resultados = $this->modelo->buscarCarnet($carnet);

        // Mostrar los resultados en formato JSON
        echo json_encode($resultados);
    }

    public function registrar()
    {
        $nolaboratorio = $_POST['noLaboratorio'];
        $nopc = $_POST['noPc'];
        $carnet = $_POST['carnet'];
        $resultados = $this->modelo->iniciarPrestamo($nolaboratorio, $nopc, $carnet);
        if ($resultados == "OK") {
            $mensaje = "si";
        }else{
            $mensaje = "error"; 
        }

        echo json_encode($mensaje);
        die();
    }

    public function listarEstudiantesTiempo() {
        $registros = $this->modelo->listarEstudiantesTiempo();
        header('Content-Type: application/json'); // Establecer la cabecera HTTP para indicar que se está devolviendo JSON
        echo json_encode($registros); // Devolver los registros como JSON
    }
    
    
}
