<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_pago
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function registrar_pago($id_mensualidad, $monto_pagado, $fecha_pago)
    {
        // Obtener id_cliente desde mensualidad y contrato
        $sql_cliente = "SELECT cs.id_cliente 
                        FROM mensualidades m 
                        JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato 
                        WHERE m.id_mensualidad = ?";
        $stmt_cliente = $this->conn->conexion->prepare($sql_cliente);
        $stmt_cliente->execute([$id_mensualidad]);
        $cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            return false; // No se encontró el cliente
        }
        $id_cliente = $cliente['id_cliente'];

        // Empezar transacción
        $this->conn->conexion->beginTransaction();

        try {
            // 1. Actualizar estado de mensualidad
            $sql_update = "UPDATE mensualidades 
                           SET estado = 'pagado', monto = ?, fecha_pagada = ? 
                           WHERE id_mensualidad = ?";
            $stmt_update = $this->conn->conexion->prepare($sql_update);
            $stmt_update->execute([$monto_pagado, $fecha_pago, $id_mensualidad]);

            // 2. Insertar registro de pago en tabla pagos
            $sql_insert = "INSERT INTO pagos (id_cliente, id_mensualidad, monto, fecha_pago) VALUES (?, ?, ?, ?)";
            $stmt_insert = $this->conn->conexion->prepare($sql_insert);
            $stmt_insert->execute([$id_cliente, $id_mensualidad, $monto_pagado, $fecha_pago]);

            // Commit transacción
            $this->conn->conexion->commit();

            return true;
        } catch (Exception $e) {
            // Rollback en caso de error
            $this->conn->conexion->rollBack();
            return false;
        }
    }



    public function listar_pagos()
    {
        $sql = "SELECT m.id_mensualidad,
                       c.nombre_completo as cliente,
                       p.nombre_plan as plan,
                       m.monto,
                       m.estado,
                       m.fecha_generada,
                       m.fecha_vencimiento as fecha_pagos
                FROM mensualidades m
                INNER JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato
                INNER JOIN clientes c ON cs.id_cliente = c.id_cliente
                INNER JOIN planes p ON cs.id_plan = p.id_plan
                ORDER BY m.periodo_anio DESC, m.periodo_mes DESC";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
