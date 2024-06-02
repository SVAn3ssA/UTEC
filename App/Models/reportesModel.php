<?php

class reportesModel extends conexion
{
    private $pdo;
    private $con;

    public function __construct()
    {
        $this->pdo = new conexion();
        $this->con = $this->pdo->conexion();
    }

    public function listaHistorial($no_laboratorio = null)
    {
        try {
            $consulta = "CALL SP_Historial(?)";
            $stmt = $this->con->prepare($consulta);
            // Vincular el parámetro
            if ($no_laboratorio === null) {
                $stmt->bindValue(1, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(1, $no_laboratorio, PDO::PARAM_INT);
            }
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $registros;
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
        }
    }



    public function reporteGeneral($tipo_reporte, $desde = null, $hasta = null, $anio = null, $numero_laboratorio = null, $ciclo = null, $dia = null)
    {
        try {
            $consulta = "CALL SP_HistorialGeneralizado(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($consulta);
            $stmt->bindParam(1, $desde, PDO::PARAM_STR);
            $stmt->bindParam(2, $hasta, PDO::PARAM_STR);
            $stmt->bindParam(3, $anio, PDO::PARAM_INT);
            $stmt->bindParam(4, $tipo_reporte, PDO::PARAM_STR);
            $stmt->bindParam(5, $numero_laboratorio, PDO::PARAM_INT);
            $stmt->bindParam(6, $ciclo, PDO::PARAM_INT);
            $stmt->bindParam(7, $dia, PDO::PARAM_STR);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $registros;
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
        }
    }
}
