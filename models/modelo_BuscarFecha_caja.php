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
                    c.id_caja,
                    c.fecha,
                    c.monto_apertura,
                    c.total_sistema,
                    c.total_real,
                    c.diferencia,
                    c.observaciones,
                    c.creada_en,
                    c.fecha_cierre,
                    u.usuario_nombres AS nombre_usuario,
                    COALESCE((
                        SELECT SUM(m2.monto + IFNULL(ps2.mora_pagada,0))
                        FROM pago_servicio ps2
                        INNER JOIN mensualidades m2 ON ps2.id_mensualidad = m2.id_mensualidad
                        WHERE DATE(ps2.creado_en) = :fecha
                          AND ps2.cerrado = 0
                    ), 0) AS total_no_cerrados
                FROM caja_diaria c
                LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE c.fecha = :fecha
                LIMIT 1";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


     public function contador_pagos($fecha)
    {
        $sql = "SELECT COUNT(*) AS total_pagos FROM pago_servicio where creado_en = :fecha LIMIT 1";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
