<?php
class conexion
{
    private $conectar;
    public function __construct()
    {
        $pdo = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";.charset.";
        try {
            $this->conectar = new PDO($pdo, DB_USER, DB_PASS);
            $this->conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexion" . $e->getMessage();
        }
    }


    public function conexion()
    {
        return $this->conectar;
    }
}
