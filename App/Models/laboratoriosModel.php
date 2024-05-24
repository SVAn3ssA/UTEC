<?php

class laboratoriosModel extends conexion
{
    private $pdo;
    private $con;

    public function __construct()
    {
        $this->pdo = new conexion();
        $this->con = $this->pdo->conexion();
    }

    protected function ejecutarConsulta($consulta)
    {
        try {
            $sql = $this->con->prepare($consulta);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }

    public function listarLaboratorio()
    {

        $consulta = "CALL SP_ListarLaboratorios";
        return $this->ejecutarConsulta($consulta);
    }

    public function insertarLaboratorio($noLaboratorio, $noPc, $descripcion, $programas, $estado)
    {
        $query = "CALL SP_InsertarLaboratorio(?,?,?,?,?)";

        try {
            $stmt = $this->con->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(1, $noLaboratorio, PDO::PARAM_STR);
            $stmt->bindParam(2, $noPc, PDO::PARAM_STR);
            $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(4, $programas, PDO::PARAM_STR);
            $stmt->bindParam(5, $estado, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            return true;  // Éxito
        } catch (PDOException  $e) {
            // Manejo de errores
            error_log("Error al insertar laboratorio: " . $e->getMessage());
            return false;  // Fallo
        }
    }

    public function seleccionarLabr($id)
    {
        try {
            $consulta = "CALL SP_SeleccionarLab(?)";
            $stmt = $this->con->prepare($consulta);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            $registros = [];
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $registros[] = $fila;
            }

            return $registros;
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
        }
    }

    public function modificarLab($noLaboratorio, $noPc, $descripcion, $programas, $estado) {
        $query = "CALL SP_ModificarLaboratorio(?,?,?,?,?)";
    
        try {
            $stmt = $this->con->prepare($query);
    
            // Vincular parámetros
            $stmt->bindParam(1, $noLaboratorio, PDO::PARAM_INT);
            $stmt->bindParam(2, $noPc, PDO::PARAM_INT);
            $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(4, $programas, PDO::PARAM_STR);
            $stmt->bindParam(5, $estado, PDO::PARAM_INT);
    
            error_log("Ejecutando consulta con estado: " . $estado); // Añade este log para ver el valor del estado
    
            // Ejecutar la consulta
            $stmt->execute();
    
            return true;  // Éxito
        } catch (PDOException $e) {
            // Manejo de errores
            error_log("Error al modificar laboratorio: " . $e->getMessage());
            return false;  // Fallo
        }
    }
}
