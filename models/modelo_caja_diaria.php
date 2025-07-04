<?php
class modelo_caja_diaria
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }

    public function existeCajaAbierta($id_usuario)
    {
        $query = "SELECT COUNT(*) as total FROM caja_diaria WHERE fecha = CURDATE() AND fecha_cierre IS NULL AND id_usuario =:id_usuario";
        $stmt = $this->conn->conexion->prepare($query);
        $stmt->bindparam(':id_usuario', $id_usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    public function abrirCaja($monto_apertura, $id_usuario)
    {
        $query = "INSERT INTO caja_diaria (fecha, monto_apertura, id_usuario) 
                  VALUES (CURDATE(), :monto_apertura, :id_usuario)";
        $stmt = $this->conn->conexion->prepare($query);
        $stmt->bindParam(":monto_apertura", $monto_apertura);
        $stmt->bindParam(":id_usuario", $id_usuario);
        return $stmt->execute();
    }

    public function cerrarCaja($total_real, $observaciones, $id_usuario)
    {
        $sqlTotal = "SELECT SUM(m.monto) AS total_pagos
             FROM pago_servicio p
             INNER JOIN mensualidades m ON p.id_mensualidad = m.id_mensualidad
             WHERE DATE(p.creado_en) = CURDATE() AND p.cerrado = 0";

        $stmtTotal = $this->conn->conexion->prepare($sqlTotal);
        $stmtTotal->execute();
        $row = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $pagos_dia = $row['total_pagos'] ?? 0;

        $monto_apertura = $this->obtenerMontoApertura($id_usuario);

        $total_sistema = $pagos_dia + $monto_apertura;

        $diferencia = $total_real - $total_sistema;//aqui

        $sql = "UPDATE caja_diaria 
            SET total_sistema = :total_sistema, 
                total_real = :total_real, 
                diferencia = :diferencia, 
                observaciones = :observaciones, 
                fecha_cierre = NOW()
            WHERE fecha = CURDATE() AND id_usuario = :id_usuario AND fecha_cierre IS NULL";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':total_sistema', $total_sistema);
        $stmt->bindParam(':total_real', $total_real);
        $stmt->bindParam(':diferencia', $diferencia);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id_usuario', $id_usuario);

        $success = $stmt->execute();

        if ($success) {
            $this->marcarPagosComoCerrados();
        }

        return $success;
    }


    public function marcarPagosComoCerrados()
    {
        $sql = "UPDATE pago_servicio SET cerrado = 1 WHERE DATE(creado_en) = CURDATE() AND cerrado = 0";
        $stmt = $this->conn->conexion->prepare($sql);
        return $stmt->execute();
    }

    public function totalDelDia()
    {
        $sql = "SELECT SUM(m.monto) AS total
            FROM pago_servicio p
            INNER JOIN mensualidades m ON p.id_mensualidad = m.id_mensualidad
            WHERE DATE(p.creado_en) = CURDATE() AND p.cerrado = 0";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarCierres()
    {
        $sql = "SELECT c.*, u.usuario_nombres AS usuario
                FROM caja_diaria c
                LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
                ORDER BY c.fecha DESC";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    c.creada_en AS abierta_en,
    c.fecha_cierre,
    u.usuario_nombres AS nombre_usuario,
    COALESCE((
        SELECT SUM(m2.monto)
        FROM pago_servicio ps2
        INNER JOIN mensualidades m2 ON ps2.id_mensualidad = m2.id_mensualidad
        WHERE DATE(ps2.creado_en) = :fecha
          AND ps2.cerrado = 1
    ), 0) AS total_cerrados,

    (c.monto_apertura + COALESCE((
        SELECT SUM(m2.monto)
        FROM pago_servicio ps2
        INNER JOIN mensualidades m2 ON ps2.id_mensualidad = m2.id_mensualidad
        WHERE DATE(ps2.creado_en) = :fecha
          AND ps2.cerrado = 1
    ), 0)) AS total_caja

    FROM caja_diaria c
    LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
    WHERE c.fecha = :fecha
    LIMIT 1";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerMontoApertura($id_usuario)
    {
        $sql = "SELECT monto_apertura FROM caja_diaria 
            WHERE fecha = CURDATE() AND id_usuario = :id_usuario AND fecha_cierre IS NULL";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['monto_apertura'] ?? 0;
    }
}
