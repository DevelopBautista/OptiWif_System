<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_contador_infoBox
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }



    public function contar_clientes()
    {
        $sql = "SELECT COUNT(*) AS total FROM clientes";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }


    public function contar_servicos()
    {
        $sql = "SELECT COUNT(*) AS total FROM contratos_servicio";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }
}
