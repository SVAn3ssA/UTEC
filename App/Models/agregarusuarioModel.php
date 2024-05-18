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
        try {
            $consulta = "CALL SP_ListarPrivilegios";
            return $this->ejecutarConsulta($consulta);
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            echo "Error de base de datos: " . $e->getMessage();
            return false; // Falla
        }
    }

    public function listarLaboratorios()
    {
        try {
            $consulta = "CALL SP_ListarLaboratorios";
            return $this->ejecutarConsulta($consulta);
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            echo "Error de base de datos: " . $e->getMessage();
            return false; // Falla
        }
    }

    public function insertarUsuario($nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)
    {
        try {
            $verificar = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $this->con->prepare($verificar);
            $stmt->execute([$email]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if(empty($existe)){
                $query = "CALL SP_InsertarUsuario(?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->con->prepare($query);
    
                $stmt->bindParam(1, $nombres, PDO::PARAM_STR);
                $stmt->bindParam(2, $apellidos, PDO::PARAM_STR);
                $stmt->bindParam(3, $email, PDO::PARAM_STR);
                $stmt->bindParam(4, $password, PDO::PARAM_STR);
                $stmt->bindParam(5, $telefono, PDO::PARAM_STR);
                $stmt->bindParam(6, $estado, PDO::PARAM_BOOL);
                $stmt->bindParam(7, $id_privilegio, PDO::PARAM_INT);
                $stmt->bindParam(8, $no_laboratorio, PDO::PARAM_INT);
    
                if($stmt->execute()){
                    return "OK";
                }else{
                    return "Error al ejecutar la consulta"; // Mensaje de error claro
                }
                
            }else{
                return "existe";
            }
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            echo "Error de base de datos: " . $e->getMessage();
            return false;
        }
    }
    
    
}
