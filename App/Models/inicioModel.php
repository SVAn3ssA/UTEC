<?php
class inicioModel extends conexion
{
    private $pdo;
    private $con;

    public function __construct()
    {
        $this->pdo = new conexion();
        $this->con = $this->pdo->conexion();
    }

    public function getUsuario(string $email, string $password)
    {
        // Llamar al procedimiento almacenado SP_ValidarUsuario
        $sql = "CALL SP_ValidarUsuario(?,?)";

        // Preparar la consulta
        $stmt = $this->con->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultado
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getNumeroPCs($laboratorio_id)
    {
        try {
            $sql = "SELECT no_pc FROM laboratorios WHERE no_laboratorio = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $laboratorio_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function buscarCarnet(string $carnet)
    {
        try {
            // Preparar la llamada al procedimiento almacenado
            $query = "CALL SP_BuscarEstudiante(?)";
            $stmt = $this->con->prepare($query);

            // Vincular parámetro
            $stmt->bindParam(1, $carnet, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener resultado
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } catch (PDOException $e) {
            // Manejar errores si es necesario
            echo "Error: " . $e->getMessage();
        }
    }


    public function iniciarPrestamo($nolaboratorio, $nopc, $carnet)
    {
        try {
            // Preparar la consulta
            $query = "CALL SP_IniciarPrestamo(?, ?, ?)";
            $stmt = $this->con->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(1, $nolaboratorio, PDO::PARAM_INT);
            $stmt->bindParam(2, $nopc, PDO::PARAM_INT);
            $stmt->bindParam(3, $carnet, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se afectaron filas en la base de datos
            $filas_afectadas = $stmt->rowCount();

            // Si se afectó al menos una fila, se considera exitoso
            return $filas_afectadas > 0;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            error_log("Error de base de datos: " . $e->getMessage());
            return false; // Falla
        }
    }


    public function listarEstudiantesTiempo($no_laboratorio)
    {
        try {
            // Preparar la consulta
            $query = "CALL SP_ListarEstudiantesTiempo(?)";
            $stmt = $this->con->prepare($query);

            // Vincular el parámetro
            $stmt->bindParam(1, $no_laboratorio, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados como un array asociativo
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $registros;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            error_log("Error de base de datos: " . $e->getMessage());
            return false; // Falla
        }
    }


    public function finalizarPrestamo($id_registro, $observacion)
    {
        try {
            // Preparar la consulta
            $query = "CALL SP_FinalizarPrestamo(?, ?)";
            $stmt = $this->con->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(1, $id_registro, PDO::PARAM_INT);
            $stmt->bindParam(2, $observacion, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Manejar excepciones si ocurre un error
            echo "Error de base de datos: " . $e->getMessage();
            return false; // Falla
        }
    }
}
