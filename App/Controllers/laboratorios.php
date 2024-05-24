<?php

class laboratorios extends controller
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

    public function listarLaboratorios()
    {
        $registros = $this->modelo->listarLaboratorio();
        for ($i = 0; $i < count($registros); $i++) {
            if ($registros[$i]['estado'] == 1) {
                $registros[$i]['estado'] = '<span style="color: green;">Activo</span>';
            } else {
                $registros[$i]['estado'] = '<span style="color: red;">Inactivo</span>';
            }
            $registros[$i]['acciones'] =
                '<div>
                    <button class="btn btn-primary" type="button" onclick="btnSeleccionarLab(' . $registros[$i]['no_laboratorio'] . ');">Modificar</button>
                </div>';
        }
        echo json_encode($registros, JSON_UNESCAPED_UNICODE);
    }

    public function agregarLaboratorio()
    {
        $noLaboratorio = $_POST['noLaboratorio'];
        $noPc = $_POST['noPc'];
        $descripcion = $_POST['descripcion'];
        $programas = $_POST['programas'];
        $estado = $_POST['estado'];

        // Validaciones del lado del servidor
        $mensajeError = $this->validarCampos($noLaboratorio, $noPc);
        if (!empty($mensajeError)) {
            $mensaje = $mensajeError;
        } else {
            $resultados = $this->modelo->insertarLaboratorio($noLaboratorio, $noPc, $descripcion, $programas, $estado);
            if ($resultados) {
                $mensaje = "SI";
            } else {
                $mensaje = "Error, este laboratorio ya existe";
            }
        }

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function modificarLaboratorio()
    {
        $noLaboratorio = $_POST['noLaboratorio'];
        $noPc = $_POST['noPc'];
        $descripcion = $_POST['descripcion'];
        $programas = $_POST['programas'];
        $estado = $_POST['estado'];

        $mensajeError = $this->validarCampos($noLaboratorio, $noPc);
        if (!empty($mensajeError)) {
            $mensaje = $mensajeError;
        } else {

            $resultados = $this->modelo->modificarLab($noLaboratorio, $noPc, $descripcion, $programas, $estado);
            if ($resultados) {
                $mensaje = "MODIFICADO";
            } else {
                $mensaje = "Error al modificar";
            }
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }


    private function validarCampos($noLaboratorio, $noPc)
    {

        if (empty($noLaboratorio) || empty($noPc)) {
            return "Los primeros 2 campos son obligatorios";
        } elseif (!preg_match('/^\d+$/', $noLaboratorio) || !preg_match('/^\d+$/', $noPc)) {
            return "Los campos de número de laboratorio y PC deben contener solo números.";
        }

        return ""; // Si no hay errores de validación, devolver una cadena vacía
    }

    public function seleccionarLab($id)
    {
        $laboratorio = $this->modelo->seleccionarLabr($id);
        echo json_encode($laboratorio, JSON_UNESCAPED_UNICODE);
    }
}
