<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_cierre_caja
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public  function registrarCierre($data)
    {
        $sql = "INSERT INTO cierre_caja (fecha_cierre, total_esperado, total_contado, diferencia, observaciones, id_usuario)
                VALUES (:fecha, :esperado, :contado, :diferencia, :obs, :usuario)";
        $stmt = $this->conn->conexion->prepare($sql);
        return $stmt->execute([
            ':fecha' => $data['fecha'],
            ':esperado' => $data['esperado'],
            ':contado' => $data['contado'],
            ':diferencia' => $data['diferencia'],
            ':obs' => $data['obs'],
            ':usuario' => $data['usuario']
        ]);
    }


    public function totalDelDia()//modelo
    {
        $sql = "SELECT SUM(m.monto) as total
                FROM pago_servicio ps
                INNER JOIN mensualidades m ON ps.id_mensualidad = m.id_mensualidad
                WHERE DATE(ps.creado_en) = CURDATE()";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
