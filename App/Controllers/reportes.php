<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class reportes extends controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function index()
    {
        $this->verificarSesion();
        $this->vista->obtenerVista($this, "index");
    }

    private function verificarSesion()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . APP_URL); // Redirigir a la vista de inicio de sesión si no hay sesión
            exit();
        }
    }

    public function historial()
    {
        if ($_SESSION['id'] === 1) {
            // Para el jefe, obtener todo el historial
            $data = $this->modelo->listaHistorial(null);
        } else {
            // Para un encargado, obtener solo el historial de su laboratorio
            $no_laboratorio = $_SESSION['no_laboratorio'];
            $data = $this->modelo->listaHistorial($no_laboratorio);
        }
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
            $otra_lista = isset($_POST['otra_lista']) ? $_POST['otra_lista'] : null;

            $resultado = null; // Inicializamos $resultado con un valor nulo



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
            } elseif ($tipo_reporte == 'general') {
                if ($otra_lista == 'anio') {
                    if (empty($anio)) {
                        throw new Exception("El año es obligatorio para el reporte por año.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, null, null);
                } elseif ($otra_lista == 'rango') {
                    if (empty($desde) || empty($hasta)) {
                        throw new Exception("Las fechas 'desde' y 'hasta' son obligatorias para el reporte por rango.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, null, null, null);
                } elseif ($otra_lista == 'dia') {
                    if (empty($dia)) {
                        throw new Exception("El día es obligatorio para el reporte por día.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, null, null, $dia);
                } elseif ($otra_lista == 'ciclo') {
                    if (empty($ciclo) || empty($anio)) {
                        throw new Exception("El ciclo y el año son obligatorios para el reporte por ciclo.");
                    }

                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, $ciclo);
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
            $privilegio = $_SESSION['privilegio'];

            $pdf = new pdf();

            // Establecemos el título del reporte basado en el tipo seleccionado
            $tipo_reporte = $_POST['tipo_reporte'];
            $titulo_reporte = '';

            if ($tipo_reporte == 'rango') {
                $titulo_reporte = 'Reporte Historial - Rango de Fechas desde ' . $desde . ' hasta ' . $hasta;
            } elseif ($tipo_reporte == 'anio') {
                $titulo_reporte = 'Reporte Historial - Por Año ' . $anio;
            } elseif ($tipo_reporte == 'ciclo') {
                $titulo_reporte = 'Reporte Historial - Por Ciclo ' . $ciclo . ' año ' . $anio;
            } elseif ($tipo_reporte == 'dia') {
                $titulo_reporte = 'Reporte Historial - Por Día ' . $dia;
            } elseif ($tipo_reporte == 'general') {
                // Título general base
                $titulo_reporte = 'Reporte General - Por Laboratorios';

                // Subfiltros dentro del reporte general
                if (!empty($anio)) {
                    $titulo_reporte .= ' Año ' . $anio;
                }
                if (!empty($desde) && !empty($hasta)) {
                    $titulo_reporte .= ' Rango de Fechas desde ' . $desde . ' hasta ' . $hasta;
                }
                if (!empty($ciclo) && !empty($anio)) {
                    $titulo_reporte .= ' Ciclo ' . $ciclo;
                }
                if (!empty($dia)) {
                    $titulo_reporte .= ' Día ' . $dia;
                }
            }

            // Codificamos el título correctamente antes de establecerlo en el PDF
            $titulo_reporte = mb_convert_encoding($titulo_reporte, 'ISO-8859-1', 'UTF-8');
            $pdf->setTituloReporte($titulo_reporte);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetFont('Arial', '', 12);
            date_default_timezone_set("America/El_Salvador");
            $currentHour = date('H'); // Obtener la hora actual en formato de 24 horas

            if ($currentHour < 12) {
                $am_pm = 'AM';
            } else {
                $am_pm = 'PM';
            }
            // Convertir la hora actual al formato de hora normal (12 horas)
            $normalTime = date('h:i:s', strtotime(date('H:i:s')));

            $pdf->Cell(64, 10, 'Fecha: ' . date('Y-m-d') . ' ' . $normalTime . ' ' . $am_pm, 0, 1, 'C');
            // Verificar el tipo de privilegio y ajustar el texto en consecuencia
            if ($privilegio == 1) {
                $texto = 'Creado por: ';
            } else if ($privilegio == 2) {
                $texto = 'Encargado de laboratorio: ';
            } else {
                // Puedes agregar más condiciones para otros privilegios si es necesario
                $texto = 'Usuario: ';
            }

            // Imprimir la celda con el texto correspondiente
            $pdf->Cell(0, 10, $texto . mb_convert_encoding($nombre_usuario, 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->Cell(0, 10, 'Correo: ' . $email, 0, 1);

            // Inicializa la variable del total de registros
            $total_registros_general = 0;

            if ($tipo_reporte == 'general') {
                // Asume que $resultado ya contiene los datos obtenidos de la base de datos
                // Suma los registros totales para el reporte general
                foreach ($resultado as $row) {
                    if (array_key_exists('total_registros', $row)) {
                        $total_registros_general += $row['total_registros'];
                    }
                }
            } else {
                $total_registros_general = count($resultado);
            }

            // Mostrar el total de registros como un encabezado
            $pdf->Cell(0, 10, 'Total de registros: ' . $total_registros_general, 0, 1);

            if ($tipo_reporte == 'general') {

                // Colores para la tabla
                $pdf->SetFillColor(211, 211, 211); // Color gris claro para el encabezado
                $pdf->SetTextColor(0, 0, 0); // Color negro para el texto

                // Encabezados de la tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 10, mb_convert_encoding('Número de Laboratorio', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', true);
                $pdf->Cell(60, 10, mb_convert_encoding('Total de Registros', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', true);

                $pdf->SetFont('Arial', '', 10);
                $fill = false;
                foreach ($resultado as $row) {
                    $pdf->SetFillColor(240, 240, 240); // Color gris más claro para las filas alternadas
                    $pdf->Cell(60, 10, mb_convert_encoding($row['no_laboratorio'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C', $fill);

                    // Verificar si existe la clave 'total_registros' en el array $row
                    if (array_key_exists('total_registros', $row)) {
                        $pdf->Cell(60, 10, mb_convert_encoding($row['total_registros'], 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', $fill);
                    } else {
                        $pdf->Cell(60, 10, 'Sin datos', 1, 1, 'C', $fill); // Mostrar mensaje si no hay datos
                    }

                    $fill = !$fill;
                }
            } else {
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
            }

            $pdf->Output();
        } catch (Exception $e) {
            echo "Error al generar el reporte: " . $e->getMessage();
        }
    }

    public function generarXls()
    {
        try {
            $tipo_reporte = $_POST['tipo_reporte'];
            $desde = isset($_POST['desde']) ? $_POST['desde'] : null;
            $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : null;
            $anio = isset($_POST['anio']) ? $_POST['anio'] : null;
            $numero_laboratorio = isset($_POST['numero_laboratorio']) ? $_POST['numero_laboratorio'] : null;
            $ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : null;
            $dia = isset($_POST['dia']) ? $_POST['dia'] : null;
            $otra_lista = isset($_POST['otra_lista']) ? $_POST['otra_lista'] : null;

            $resultado = null;

            // Llamamos al modelo para obtener el resultado según el tipo de reporte seleccionado
            if ($tipo_reporte == 'anio') {
                if (empty($anio)) {
                    throw new Exception("El año es obligatorio para el reporte por año.");
                }
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, $numero_laboratorio);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null);
                }
            } elseif ($tipo_reporte == 'rango') {
                if (empty($desde) || empty($hasta)) {
                    throw new Exception("Las fechas 'desde' y 'hasta' son obligatorias para el reporte por rango.");
                }
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, $numero_laboratorio);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, null);
                }
            } elseif ($tipo_reporte == 'ciclo') {
                if (empty($ciclo) || empty($anio)) {
                    throw new Exception("El ciclo y el año son obligatorios para el reporte por ciclo.");
                }
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, $numero_laboratorio, $ciclo);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, $ciclo);
                }
            } elseif ($tipo_reporte == 'dia') {
                if (empty($dia)) {
                    throw new Exception("El día es obligatorio para el reporte por día.");
                }
                if ($numero_laboratorio) {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, $numero_laboratorio, null, $dia);
                } else {
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, null, null, $dia);
                }
            } elseif ($tipo_reporte == 'general') {
                if ($otra_lista == 'anio') {
                    if (empty($anio)) {
                        throw new Exception("El año es obligatorio para el reporte por año.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, null, null);
                } elseif ($otra_lista == 'rango') {
                    if (empty($desde) || empty($hasta)) {
                        throw new Exception("Las fechas 'desde' y 'hasta' son obligatorias para el reporte por rango.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, $desde, $hasta, null, null, null, null);
                } elseif ($otra_lista == 'dia') {
                    if (empty($dia)) {
                        throw new Exception("El día es obligatorio para el reporte por día.");
                    }
                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, null, null, null, $dia);
                } elseif ($otra_lista == 'ciclo') {
                    if (empty($ciclo) || empty($anio)) {
                        throw new Exception("El ciclo y el año son obligatorios para el reporte por ciclo.");
                    }

                    $resultado = $this->modelo->reporteGeneral($tipo_reporte, null, null, $anio, null, $ciclo);
                }
            } else {
                throw new Exception("Tipo de reporte no válido.");
            }

            if (!$resultado) {
                throw new Exception("No se encontraron registros para el tipo de reporte seleccionado.");
            }

            // Generar el archivo Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Reporte');

            // Agregar una fila para la imagen y el texto
            $sheet->mergeCells('A1:F1'); // Combinar celdas para cubrir todas las columnas
            $sheet->getRowDimension(1)->setRowHeight(100); // Ajustar la altura de la fila para la imagen
            $sheet->setCellValue('A1', ''); // Puede ser útil agregar un valor en la celda, aunque esté vacío

            // Insertar la imagen en la celda A1
            $logoPath = 'App/images/logo_utec_solo_letras.png';
            $logoDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $logoDrawing->setName('Logo');
            $logoDrawing->setDescription('Logo UTEC');
            $logoDrawing->setPath($logoPath);
            $logoDrawing->setCoordinates('A1');
            // Ajustar la posición horizontal y vertical de la imagen
            $logoDrawing->setOffsetX(90); // Ajustar la posición horizontal según sea necesario
            $logoDrawing->setOffsetY(20); // Ajustar la posición vertical según sea necesario

            $logoDrawing->setWidth(90); // Ajustar el ancho de la imagen
            $logoDrawing->setHeight(90); // Ajustar la altura de la imagen
            $logoDrawing->setWorksheet($sheet);

            // Añadir el texto "Unidad de Apoyo Técnico"
            $sheet->setCellValue('A2', 'Unidad de Apoyo Técnico');
            $sheet->mergeCells('A2:F2'); // Combinar celdas para cubrir todas las columnas
            $sheet->getRowDimension(2)->setRowHeight(25); // Ajustar la altura de la fila para el texto

            // Ajustar el estilo para el texto
            $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:F2')->getFont()->setSize(14)->setBold(true);

            // Establecer el margen superior para dar espacio al encabezado
            $sheet->getPageMargins()->setTop(150); // Ajusta este valor según sea necesario

            // Estilos para los encabezados
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_BLACK],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => Color::COLOR_BLACK],
                    ],
                ],
            ];

            // Estilos para las celdas de datos
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => Color::COLOR_BLACK],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            // Obtener el nombre completo del usuario desde la sesión
            $nombre_usuario = isset($_SESSION['nombres']) && isset($_SESSION['apellidos']) ? $_SESSION['nombres'] . ' ' . $_SESSION['apellidos'] : 'Usuario Desconocido';
            $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Correo no disponible';
            $privilegio = $_SESSION['privilegio'];

            $titulo_reporte = '';

            if ($tipo_reporte == 'rango') {
                $titulo_reporte = 'Reporte Historial - Rango de Fechas desde ' . $desde . ' hasta ' . $hasta;
            } elseif ($tipo_reporte == 'anio') {
                $titulo_reporte = 'Reporte Historial - Por Año ' . $anio;
            } elseif ($tipo_reporte == 'ciclo') {
                $titulo_reporte = 'Reporte Historial - Por Ciclo ' . $ciclo . ' año ' . $anio;
            } elseif ($tipo_reporte == 'dia') {
                $titulo_reporte = 'Reporte Historial - Por Día ' . $dia;
            } elseif ($tipo_reporte == 'general') {
                // Título general base
                $titulo_reporte = 'Reporte General - Por Laboratorios';

                // Subfiltros dentro del reporte general
                if (!empty($anio)) {
                    $titulo_reporte .= ' Año ' . $anio;
                }
                if (!empty($desde) && !empty($hasta)) {
                    $titulo_reporte .= ' Rango de Fechas desde ' . $desde . ' hasta ' . $hasta;
                }
                if (!empty($ciclo) && !empty($anio)) {
                    $titulo_reporte .= ' Ciclo ' . $ciclo;
                }
                if (!empty($dia)) {
                    $titulo_reporte .= ' Día ' . $dia;
                }
            }

            // Añadir encabezados de reporte
            $sheet->setCellValue('A4', $titulo_reporte);
            $sheet->mergeCells('A4:F4');
            $sheet->getStyle('A4:F4')->getFont()->setBold(true);
            $sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            date_default_timezone_set("America/El_Salvador");

            $currentHour = date('H'); // Obtener la hora actual en formato de 24 horas

            if ($currentHour < 12) {
                $am_pm = 'AM';
            } else {
                $am_pm = 'PM';
            }

            // Convertir la hora actual al formato de hora normal (12 horas)
            $normalTime = date('h:i:s A', strtotime(date('H:i:s')));

            $sheet->setCellValue('A5', 'Fecha: ' . date('Y-m-d') . ' ' . $normalTime);
            $sheet->mergeCells('A5:F5');
            // Verificar el tipo de privilegio y ajustar el texto en consecuencia
            if ($privilegio == 1) {
                $texto = 'Creado por: ';
            } else if ($privilegio == 2) {
                $texto = 'Encargado de laboratorio: ';
            } else {
                // Puedes agregar más condiciones para otros privilegios si es necesario
                $texto = 'Usuario: ';
            }

            $sheet->setCellValue('A6', $texto . $nombre_usuario);
            $sheet->mergeCells('A6:F6');

            $sheet->setCellValue('A7', 'Correo: ' . $email);
            $sheet->mergeCells('A7:F7');

            // Inicializa la variable del total de registros
            $total_registros_general = 0;

            if ($tipo_reporte == 'general') {
                // Asume que $resultado ya contiene los datos obtenidos de la base de datos
                // Suma los registros totales para el reporte general
                foreach ($resultado as $row) {
                    if (array_key_exists('total_registros', $row)) {
                        $total_registros_general += $row['total_registros'];
                    }
                }
            } else {
                $total_registros_general = count($resultado);
            }

            $sheet->setCellValue('A8', 'Total de registros: ' . $total_registros_general);
            $sheet->mergeCells('A8:F8');

            if ($tipo_reporte == 'general') {
                // Añadir los encabezados de columna y centrarlos
                $columns = [
                    'Número de Laboratorio', 'Total de Registros'
                ];

                $columnIndex = 'B';
                $rowIndex = 10;
                foreach ($columns as $column) {
                    $sheet->setCellValue($columnIndex . $rowIndex, $column);
                    $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
                    $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($headerStyle);
                    // Centrar los encabezados de columna
                    $sheet->getStyle($columnIndex . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $columnIndex++;
                }

                // Añadir los datos
                $rowIndex = 11;
                foreach ($resultado as $row) {
                    $columnIndex = 'B';
                    // Saltar la primera columna de contador y ajustar el bucle para que empiece desde el índice correcto
                    foreach ($row as $cell) {
                        $sheet->setCellValue($columnIndex . $rowIndex, $cell);
                        $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($dataStyle);
                        $columnIndex++;
                    }
                    $rowIndex++;
                }
            } else {
                // Añadir los encabezados de columna y centrarlos
                $columns = [
                    'Carnet', 'Fecha y Hora', 'Tiempo', 'Observacion', 'Laboratorio', 'Pc'
                ];
                $columnIndex = 'A';
                $rowIndex = 10;
                foreach ($columns as $column) {
                    $sheet->setCellValue($columnIndex . $rowIndex, $column);
                    $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
                    $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($headerStyle);
                    // Centrar los encabezados de columna
                    $sheet->getStyle($columnIndex . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $columnIndex++;
                }

                // Añadir los datos
                $rowIndex = 11;
                foreach ($resultado as $row) {
                    $columnIndex = 'A';
                    // Saltar la primera columna de contador y ajustar el bucle para que empiece desde el índice correcto
                    foreach (array_slice($row, 1) as $cell) {
                        $sheet->setCellValue($columnIndex . $rowIndex, $cell);
                        $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($dataStyle);
                        $columnIndex++;
                    }
                    $rowIndex++;
                }
            }

            // Proteger la hoja para que no se pueda editar
            $sheet->getProtection()->setSheet(true);
            $sheet->getProtection()->setPassword('your_password_here');

            $writer = new Xlsx($spreadsheet);
            $fileName = 'reporte_historial.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($temp_file);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            readfile($temp_file);
            unlink($temp_file);
            exit;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
