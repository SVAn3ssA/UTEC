<?php

class usuarioModel extends conexion
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

    public function limpiarCadena($cadena)
    {
        $palabras = ["<script>", "</script>", "<script src", "<script type=", "SELECT * FROM", "SELECT ", " SELECT ", "DELETE FROM", "INSERT INTO", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLE", "SHOW TABLES", "SHOW DATABASES", "<?php", "?>", "--", "^", "<", ">", "==", "=", ";", "::"];

        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);

        foreach ($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena);
        }

        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);

        return $cadena;
    }


    

    public function listarPrivilegios()
    {
        $consulta = "CALL SP_ListarPrivilegios";
        return $this->ejecutarConsulta($consulta);
    }

    //funcion para listar los laboratorios
    public function listarLaboratorios()
    {
        $consulta = "CALL SP_ListarLaboratorios";
        return $this->ejecutarConsulta($consulta);
    }

    //funcion para listar los laboratorios
    protected function listarUsuarios()
    {
        $consulta = "CALL SP_ListarUsuarios";
        return $this->ejecutarConsulta($consulta);
    }

    public function obtenerPasswordDesencriptada($idUsuario)
    {
        $query = "CALL SP_DesencriptarPassword(?)";
        $sql = $this->con->prepare($query);
        $sql->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }
}