<?php

class agregarusuarioModel extends conexion
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
        $sql = $this->con->prepare($consulta);
        $sql->execute();
        return $sql;
    }


    public function listarPrivilegios()
    {
        $consulta = "CALL SP_ListarPrivilegios";
        return $this->ejecutarConsulta($consulta);
    }

    public function listarLaboratorios()
    {
        $consulta = "CALL SP_ListarLaboratorios";
        return $this->ejecutarConsulta($consulta);
    }

    public function insertarUsuario($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)
    {
        try {
            // Preparar la consulta
            $query = "CALL SP_InsertarUsuario(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);

            // Bind de parámetros
            $stmt->bindParam(1, $nombres, PDO::PARAM_STR);
            $stmt->bindParam(2, $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(3, $email, PDO::PARAM_STR);
            $stmt->bindParam(4, $password, PDO::PARAM_STR);
            $stmt->bindParam(5, $telefono, PDO::PARAM_STR);
            $stmt->bindParam(6, $estado, PDO::PARAM_BOOL);
            $stmt->bindParam(7, $id_privilegio, PDO::PARAM_INT);
            $stmt->bindParam(8, $no_laboratorio, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se afectaron filas en la base de datos
            $filas_afectadas = $stmt->rowCount();

            // Si se afectó al menos una fila, se considera exitoso
            if ($filas_afectadas > 0) {
                return true;
            } else {
                return false; // No se afectaron filas, operación fallida
            }
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            echo "Error de base de datos: " . $e->getMessage();
            return false; // Falla
        }
    }
}
