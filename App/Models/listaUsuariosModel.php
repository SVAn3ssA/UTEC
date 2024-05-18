<?php

class listaUsuariosModel extends conexion
{
    private $pdo;
    private $con;

    public function __construct()
    {
        $this->pdo = new conexion();
        $this->con = $this->pdo->conexion();
    }

    public function listarUsuarios()
    {
        try {
            $consulta = "CALL SP_ListarUsuarios";
            $stmt = $this->con->prepare($consulta);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $registros;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error de base de datos
            echo "Error de base de datos: " . $e->getMessage();
            return []; // Devolver un array vacío en caso de error
        }
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

    public function modificarUser($id)
    {
        try {
            $consulta = "SELECT u.id_usuario, u.nombres, u.apellidos, u.email, 
                     CONVERT(AES_DECRYPT(u.password, 'Ut3c') USING utf8) AS password, 
                     u.telefono, u.estado, u.id_privilegio, u.no_laboratorio
                     FROM usuarios u
                     LEFT JOIN privilegios p ON u.id_privilegio = p.id_privilegio
                     WHERE u.id_usuario = :id";
            $stmt = $this->con->prepare($consulta);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $registros = [];
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $registros[] = $fila;
            }

            return $registros;
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
            return [];
        }
    }

    public function modificarUsuario($id, $nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)
    {
        try {
            $consulta = "CALL SP_ModificarUsuario(:id_usuario_param, :nombres_param, :apellidos_param, :email_param, 
                                                  :password_param, :telefono_param, :estado_param, :id_privilegio_param, :no_laboratorio_param)";
            $stmt = $this->con->prepare($consulta);

            $stmt->bindParam(':id_usuario_param', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombres_param', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos_param', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':email_param', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password_param', $password, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_param', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':estado_param', $estado, PDO::PARAM_BOOL);
            $stmt->bindParam(':id_privilegio_param', $id_privilegio, PDO::PARAM_INT);
            $stmt->bindParam(':no_laboratorio_param', $no_laboratorio, PDO::PARAM_INT);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error de base de datos
            echo "Error de base de datos: " . $e->getMessage();
            return false;
        }
    }
}
