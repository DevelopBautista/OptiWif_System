<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_apertura
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }
    public function existeCajaAbierta()
    {
        $query = "SELECT COUNT(*) as total FROM apertura_caja WHERE estado = 'abierta'";
        $stmt = $this->conn->conexion->prepare($query);

        if($stmt->execute()){
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] > 0;
        }else{
            return false;
        }
    }

    public function abrirCaja($monto, $usuario)
    {
        $sql = "INSERT INTO apertura_caja (monto_inicial, id_usuario) 
              VALUES (:monto_inicial,:id_usuario)";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(":monto_inicial", $monto);
        $stmt->bindParam(":id_usuario", $usuario);
        return $stmt->execute();
    }
}
