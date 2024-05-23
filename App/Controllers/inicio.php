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
        $mensaje = "Los campos están vacíos";
    } else {
        $email = $_POST['email_usuario'];
        $password = $_POST['password_usuario'];
        $data = $this->modelo->getUsuario($email, $password);

        if ($data) {
            $_SESSION['id'] = $data['id_usuario'];
            $_SESSION['nombres'] = $data['nombres'];
            $_SESSION['apellidos'] = $data['apellidos'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['no_laboratorio'] = $data['no_laboratorio'];

            // Depuración: Verificar datos del usuario
            error_log("Usuario autenticado: " . json_encode($data));

            // Obtener el número de PCs del laboratorio
            $num_pcs = $this->modelo->getNumeroPCs($data['no_laboratorio']);
            if ($num_pcs !== false && isset($num_pcs['no_pc'])) {
                $_SESSION['num_pcs'] = $num_pcs['no_pc'];
                error_log("Número de PCs obtenido: " . $_SESSION['num_pcs']);
                $mensaje = "Ok";
            } else {
                error_log("Error al obtener el número de PCs.");
                $mensaje = "Error al obtener el número de PCs del laboratorio.";
            }
        } else {
            $mensaje = "Email o password incorrecto";
        }
    }
    echo json_encode($mensaje);
    die();
}


    public function cerrarSesion()
    {
        session_destroy();
        // Redirigir a la página de inicio u otra página según sea necesario
        header("Location: " . APP_URL);
        exit();
    }

    public function buscar($carnet)
    {
        $resultados = $this->modelo->buscarCarnet($carnet);
        echo json_encode($resultados);
    }

    public function registrar()
    {
        $nolaboratorio = $_POST['noLaboratorio'];
        $nopc = $_POST['noPc'];
        $carnet = $_POST['carnet'];
        $resultados = $this->modelo->iniciarPrestamo($nolaboratorio, $nopc, $carnet);
        if ($resultados) {
            $mensaje = "OK"; // Cambiado a "OK" en lugar de "si"
        } else {
            $mensaje = "Error al iniciar el préstamo"; // Mensaje de error claro
        }

        echo json_encode($mensaje);
        die();
    }


    public function listar()
    {
        $registros = $this->modelo->listarEstudiantesTiempo();
        for ($i = 0; $i < count($registros); $i++) {
            $id_registro = $registros[$i]['id_registro'];
            $registros[$i]['acciones'] =
                '<div>
            <button class="btn btn-primary" type="button" onclick="btnFinalizar(' . $id_registro . ')">Finalizar</button>
        </div>';
            // Agregar campo de observación con identificador único
            $registros[$i]['observacion'] =
                '<div>
            <input type="text" class="form-control" id="observacion_' . $id_registro . '" name="observacion">
        </div>';
        }
        echo json_encode($registros);
    }


    public function modificar($id){
        $observacion = isset($_GET['observacion']) ? $_GET['observacion'] : ""; // Obtener el valor de la observación si se proporciona
        $registros = $this->modelo->finalizarPrestamo($id, $observacion);
        echo json_encode($registros);
    }
    
}
