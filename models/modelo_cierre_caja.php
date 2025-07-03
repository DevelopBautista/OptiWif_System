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

    // Registrar cierre de caja
    public function registrarCierre($data)
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

    // Total del día (mensualidad + mora)
    public function totalDelDia()
    {
        $sql = "SELECT SUM(m.monto + IFNULL(p.mora_pagada,0)) as total
                FROM pago_servicio p
                INNER JOIN mensualidades m ON p.id_mensualidad = m.id_mensualidad
                WHERE DATE(p.creado_en) = CURDATE() AND p.cerrado = 0";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cerrar caja abierta por usuario
    public function cerrarCajaAbierta($usuario)
    {
        $sql = "UPDATE apertura_caja 
                SET estado = 'cerrada', fecha_cierre = NOW() 
                WHERE estado = 'abierta' AND id_usuario = :usuario
                ORDER BY id_apertura DESC
                LIMIT 1";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        return $stmt->execute();
    }

    // Marcar pagos como cerrados
    public function marcarPagosComoCerrados()
    {
        $sql = "UPDATE pago_servicio 
                SET cerrado = 1 
                WHERE DATE(creado_en) = CURDATE() AND cerrado = 0";
        $stmt = $this->conn->conexion->prepare($sql);
        return $stmt->execute();
    }

    // Verificar si ya hay una caja abierta
    public function hayCajaAbierta($id_usuario)
    {
        $sql = "SELECT COUNT(*) FROM apertura_caja 
                WHERE estado = 'abierta' AND id_usuario = ?";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn() > 0;
    }

    // Obtener caja abierta del usuario
    public function obtenerCajaAbierta($id_usuario)
    {
        $sql = "SELECT * FROM apertura_caja 
                WHERE estado = 'abierta' AND id_usuario = ? 
                ORDER BY id_apertura DESC LIMIT 1";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si ya se hizo cierre hoy
    public function yaSeCerroHoy($id_usuario)
    {
        $sql = "SELECT COUNT(*) FROM cierre_caja 
                WHERE DATE(fecha_cierre) = CURDATE() AND id_usuario = ?";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn() > 0;
    }

    // Listar cierres anteriores
    public function listarCierres()
    {
        $sql = "SELECT c.*, u.nombre AS usuario
                FROM cierre_caja c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                ORDER BY c.fecha_cierre DESC";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
