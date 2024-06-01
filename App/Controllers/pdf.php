<?php
require('../UTEC/Libreria/fpdf186/fpdf.php');

class pdf extends FPDF
{
    private $titulo_reporte = ''; // Variable para almacenar el título del reporte

    // Setter para establecer el título del reporte
    public function setTituloReporte($titulo)
    {
        $this->titulo_reporte = $titulo;
    }

    /// Cabecera de página
    function Header()
    {
        // Encabezado del PDF
        $imageWidth = 100; // Ancho de la imagen
        $this->Image('App/Views/images/logo_utec_solo_letras.png', ($this->GetPageWidth() - $imageWidth) / 2, 10, $imageWidth);
        $this->Ln(30); // Dejamos un espacio después de la imagen
        $this->SetFont('Arial', 'B', 12); // Establecemos la fuente regular
        $this->Cell(0, 10, $this->titulo_reporte, 0, 1, 'C'); // Utilizamos el título dinámico
        $this->Ln(10); // Espacio después del título
    }


    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
