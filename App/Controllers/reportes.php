<?php

class reportes extends controller
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

    public function historial()
    {
        $data = $this->modelo->listaHistorial();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }



    public function generarPdf()
    {
        try {
            $tipo_reporte = $_POST['tipo_reporte'];
            $desde = isset($_POST['desde']) ? $_POST['desde'] : null;
            $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : null;
            $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
            $numero_laboratorio = isset($_POST['numero_laboratorio']) ? $_POST['numero_laboratorio'] : null;
            $ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : null;
            $dia = isset($_POST['dia']) ? $_POST['dia'] : null;

            $resultado = null; // Inicializamos $resultado con un valor nulo

            // Llamamos al modelo para obtener el resultado según el tipo de reporte seleccionado
            if ($tipo_reporte == 'anio') {
                if (empty($anio)) {
                    throw new Exception("El año es obligatorio para el reporte por año.");
                }
                // Llamamos al modelo para obtener el resultado
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, $numero_laboratorio);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null);
                }
            } elseif ($tipo_reporte == 'rango') {
                if (empty($desde) || empty($hasta)) {
                    throw new Exception("Las fechas 'desde' y 'hasta' son obligatorias para el reporte por rango.");
                }
                // Llamamos al modelo para obtener el resultado
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, $numero_laboratorio);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, null);
                }
            } elseif ($tipo_reporte == 'ciclo') {
                if (empty($ciclo) || empty($anio)) {
                    throw new Exception("El ciclo y el año son obligatorios para el reporte por ciclo.");
                }
                // Llamamos al modelo para obtener el resultado
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, $numero_laboratorio, $ciclo);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, $ciclo);
                }
            } elseif ($tipo_reporte == 'dia') {
                if (empty($dia)) {
                    throw new Exception("El día es obligatorio para el reporte por día.");
                }
                // Llamamos al modelo para obtener el resultado
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, $numero_laboratorio, null, $dia);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, null, null, $dia);
                }
            } else {
                throw new Exception("Tipo de reporte no válido.");
            }

            if (!$resultado) {
                throw new Exception("No se encontraron registros para el tipo de reporte seleccionado.");
            }

            // Inicio del script para generar el PDF
            require('pdf.php');

            // Obtén el nombre completo del usuario desde la sesión
            $nombre_usuario = isset($_SESSION['nombres']) && isset($_SESSION['apellidos']) ? $_SESSION['nombres'] . ' ' . $_SESSION['apellidos'] : 'Usuario Desconocido';
            $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Correo no disponible';

            $pdf = new pdf();

            // Establecemos el título del reporte basado en el tipo seleccionado
            $tipo_reporte = $_POST['tipo_reporte'];
            $titulo_reporte = '';

            if ($tipo_reporte == 'rango') {
                $titulo_reporte = 'Reporte Historial - Rango de Fechas';
            } elseif ($tipo_reporte == 'anio') {
                $titulo_reporte = 'Reporte Historial - Por Año';
            } elseif ($tipo_reporte == 'ciclo') {
                $titulo_reporte = 'Reporte Historial - Por Ciclo';
            } elseif ($tipo_reporte == 'dia') {
                $titulo_reporte = 'Reporte Historial - Por Día';
            }

            // Codificamos el título correctamente antes de establecerlo en el PDF
            $titulo_reporte = mb_convert_encoding($titulo_reporte, 'ISO-8859-1', 'UTF-8');
            $pdf->setTituloReporte($titulo_reporte);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetFont('Arial', '', 12);
            date_default_timezone_set("America/El_Salvador");
            $pdf->Cell(55, 10, 'Fecha: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
            $pdf->Cell(0, 10, 'Encargado de laboratorio: ' . mb_convert_encoding($nombre_usuario, 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->Cell(0, 10, 'Correo: ' . $email, 0, 1);
            // Mostrar el total de registros
            $pdf->Cell(0, 10, 'Total de registros: ' . count($resultado), 0, 1);
            $pdf->Ln();

            // Colores para la tabla
            $pdf->SetFillColor(211, 211, 211); // Color gris claro para el encabezado
            $pdf->SetTextColor(0, 0, 0); // Color negro para el texto

            // Encabezados de la tabla
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 10, mb_convert_encoding('Carnet', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
            $pdf->Cell(40, 10, mb_convert_encoding('Fecha y Hora', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
            $pdf->Cell(30, 10, mb_convert_encoding('Tiempo', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
            $pdf->Cell(50, 10, mb_convert_encoding('Observación', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
            $pdf->Cell(20, 10, mb_convert_encoding('Laboratorio', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
            $pdf->Cell(20, 10, mb_convert_encoding('Pc', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', true);

            // Datos de la tabla
            $pdf->SetFont('Arial', '', 10);
            $fill = false;
            foreach ($resultado as $row) {
                $pdf->SetFillColor(240, 240, 240); // Color gris más claro para las filas alternadas
                $pdf->Cell(30, 10, mb_convert_encoding($row['carnet'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);
                $pdf->Cell(40, 10, mb_convert_encoding($row['fechahora'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);
                $pdf->Cell(30, 10, mb_convert_encoding($row['tiempo'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);
                $pdf->Cell(50, 10, mb_convert_encoding($row['observacion'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);
                $pdf->Cell(20, 10, mb_convert_encoding($row['no_laboratorio'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);
                $pdf->Cell(20, 10, mb_convert_encoding($row['no_pc'], 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', $fill);
                $fill = !$fill;
            }

            $pdf->Output();
        } catch (Exception $e) {
            echo "Error al generar el reporte: " . $e->getMessage();
        }
    }
}
