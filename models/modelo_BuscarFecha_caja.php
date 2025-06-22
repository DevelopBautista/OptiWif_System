<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_BuscarFecha_caja
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }
    public function buscarCierrePorFecha($fecha)
    {
        $sql = "SELECT 
        ac.id_apertura,
        ac.fecha_apertura,
        ac.fecha_cierre,
        ac.monto_inicial,
        ac.id_usuario,
        u.usuario_nombres AS nombre_usuario,
        ac.estado,
        COALESCE(SUM(m.monto + IFNULL(m.cargo_extra, 0)), 0) AS total_movimientos,
        (ac.monto_inicial + COALESCE(SUM(m.monto + IFNULL(m.cargo_extra, 0)), 0)) AS total_caja
    FROM 
        apertura_caja ac
    INNER JOIN usuario u ON ac.id_usuario = u.id_usuario
    LEFT JOIN pago_servicio ps 
        ON ps.creado_en >= ac.fecha_apertura 
        AND (ac.fecha_cierre IS NULL OR ps.creado_en <= ac.fecha_cierre)
        AND ps.estado_pago = 'pagado'
    LEFT JOIN mensualidades m 
        ON ps.id_mensualidad = m.id_mensualidad
    WHERE 
        DATE(ac.fecha_apertura) = :fecha
    GROUP BY 
        ac.id_apertura, ac.fecha_apertura, ac.fecha_cierre, ac.monto_inicial, ac.id_usuario, u.usuario_nombres, ac.estado
    ORDER BY 
        ac.fecha_apertura DESC";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
