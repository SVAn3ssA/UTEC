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

    public function modificarUsuario($id){
        print_r($id);
    }
}
