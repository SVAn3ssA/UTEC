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
        $sql = "CALL SP_ValidarUsuario(:email, :password)";

        // Preparar la consulta
        $stmt = $this->con->prepare($sql);

        // Bind de parámetros
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultado
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function buscarCarnet(string $carnet)
    {
        try {
            // Preparar la llamada al procedimiento almacenado
            $query = "CALL SP_BuscarEstudiante(:carnet)";
            $stmt = $this->con->prepare($query);

            // Vincular parámetro
            $stmt->bindParam(':carnet', $carnet, PDO::PARAM_STR);

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
            $query = "CALL SP_IniciarPrestamo(:nolaboratorio_param, :nopc_param, :carnet)";
            $stmt = $this->con->prepare($query);

            // Bind de parámetros
            $stmt->bindParam(':nolaboratorio_param', $nolaboratorio, PDO::PARAM_INT);
            $stmt->bindParam(':nopc_param', $nopc, PDO::PARAM_INT);
            $stmt->bindParam(':carnet', $carnet, PDO::PARAM_STR);

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
            echo "Error: " . $e->getMessage();
            return false; // Falla
        }
    }

    public function listarEstudiantesTiempo(){
        $consulta = "SELECT `id_registro`, `carnet`, `no_laboratorio`, `no_pc`
        FROM `registrotiempoestudiantes`";
        
        // Preparar la consulta SQL
        $stmt = $this->con->prepare($consulta);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener los resultados como un array asociativo
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $registros;
    }
}
