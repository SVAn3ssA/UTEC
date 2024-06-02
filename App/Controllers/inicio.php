<?php
class inicio extends controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    private function verificarSesion()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . APP_URL); // Redirigir a la vista de inicio de sesión si no hay sesión
            exit();
        }
    }

    public function index()
    {
        $this->verificarSesion();
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
                $_SESSION['privilegio'] = $data['id_privilegio'];

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
        // Validar que los datos han sido enviados
        if (!isset($_POST['noLaboratorio'], $_POST['noPc'], $_POST['carnet'])) {
            echo json_encode("Error: Datos incompletos");
            die();
        }

        $nolaboratorio = $_POST['noLaboratorio'];
        $nopc = $_POST['noPc'];
        $carnet = $_POST['carnet'];

        // Validar que el número de PC sea mayor que 0
        if ($nopc <= 0) {
            $mensaje = "Error: El número de PC debe ser mayor que 0";
            echo json_encode($mensaje);
            die();
        }

        // Verificar si el número de PC está disponible
        $registros = $this->modelo->listarEstudiantesTiempo($nolaboratorio); // Filtrando por laboratorio
        foreach ($registros as $registro) {
            if ($registro['no_pc'] == $nopc) {
                $mensaje = "Error: El número de PC ya está en préstamo";
                echo json_encode($mensaje);
                die();
            }
        }

        $resultados = $this->modelo->iniciarPrestamo($nolaboratorio, $nopc, $carnet);
        if ($resultados) {
            $mensaje = "OK";
        } else {
            $mensaje = "Error al iniciar el préstamo";
        }

        echo json_encode($mensaje);
        die();
    }




    public function listar()
    {
        // Suponiendo que el número de laboratorio está almacenado en la sesión del usuario
        $no_laboratorio = $_SESSION['no_laboratorio']; // Ajusta esto según tu lógica

        $registros = $this->modelo->listarEstudiantesTiempo($no_laboratorio);
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
        die();
    }


    public function modificar($id)
    {
        $observacion = isset($_GET['observacion']) ? $_GET['observacion'] : ""; // Obtener el valor de la observación si se proporciona
        $registros = $this->modelo->finalizarPrestamo($id, $observacion);
        echo json_encode($registros);
    }
}
