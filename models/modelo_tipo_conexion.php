<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_tipo_conexion
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }

    public function listar_tipo_conexion()
    {
        $sql = "SELECT id_tipo_conexion, descripcion FROM tipo_conexion ";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        return $respuesta; //retorna texto
    }
}
