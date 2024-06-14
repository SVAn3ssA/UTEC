<?php

class ListaUsuariosModel extends conexion
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
            echo "Error de base de datos: " . $e->getMessage();
        }
    }

    protected function ejecutarConsulta($consulta)
    {
        try {
            $sql = $this->con->prepare($consulta);
            $sql->execute();
            return $sql;
        } catch (PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }

    public function listarPrivilegios()
    {
        try {
            $consulta = "CALL SP_ListarPrivilegios";
            return $this->ejecutarConsulta($consulta);
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
        }
    }

    public function listarLaboratorios()
    {
        try {
            $consulta = "CALL SP_ListarLaboratorios";
            return $this->ejecutarConsulta($consulta);
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
        }
    }

    public function seleccionarUsuario($id)
    {
        try {
            $consulta = "CALL SP_SeleccionarUsuario(?)";
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

    public function modificarUsuario($id, $nombres, $apellidos, $email, $password, $telefono, $estado, $id_privilegio, $no_laboratorio)
    {
        try {
            // Verificar si el correo electrónico ya existe para otro usuario
            $verificar = "SELECT COUNT(*) FROM usuarios WHERE email = ? AND id_usuario != ?";
            $stmt = $this->con->prepare($verificar);
            $stmt->execute([$email, $id]);
            $existe = $stmt->fetchColumn();

            if ($existe > 0) {
                return false; // Correo electrónico ya existe para otro usuario
            }

            // Proceder con la modificación del usuario
            $consulta = "CALL SP_ModificarUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($consulta);

            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $nombres, PDO::PARAM_STR);
            $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(4, $email, PDO::PARAM_STR);
            $stmt->bindParam(5, $password, PDO::PARAM_STR);
            $stmt->bindParam(6, $telefono, PDO::PARAM_STR);
            $stmt->bindParam(7, $estado, PDO::PARAM_BOOL);
            $stmt->bindParam(8, $id_privilegio, PDO::PARAM_INT);
            $stmt->bindParam(9, $no_laboratorio, PDO::PARAM_INT);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error de base de datos
            echo "Error de base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerNumeroPCs($no_laboratorio)
    {
        try {
            // Consulta SQL para obtener el número de PCs en el laboratorio dado
            $consulta = "SELECT no_pc FROM laboratorios WHERE no_laboratorio = :no_laboratorio";
            $stmt = $this->con->prepare($consulta);
            $stmt->bindParam(":no_laboratorio", $no_laboratorio, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el laboratorio
            if ($resultado) {
                return $resultado['no_pc'];
            } else {
                return 0; // O cualquier otro valor predeterminado adecuado
            }
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
            return 0; // O cualquier otro valor predeterminado adecuado
        }
    }
}
