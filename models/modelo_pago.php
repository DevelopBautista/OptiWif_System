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


    public function registrar_pago($id_mensualidad, $monto_pagado, $fecha_pago, $metodo_pago, $referencia_pago, $observaciones)
    {
        // Obtener id_contrato desde mensualidad
        $sql_datos = "SELECT cs.id_contrato 
                  FROM mensualidades m 
                  JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato 
                  WHERE m.id_mensualidad = ?";
        $stmt = $this->conn->conexion->prepare($sql_datos);
        $stmt->execute([$id_mensualidad]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return "No se encontró el contrato relacionado.";
        }

        $id_contrato = $row['id_contrato'];

        $this->conn->conexion->beginTransaction();

        try {
            // 1. Actualizar estado de la mensualidad
            $sql_update = "UPDATE mensualidades 
                       SET estado = 'pagado', monto = ?, fecha_pagada = ? 
                       WHERE id_mensualidad = ?";
            $stmt_update = $this->conn->conexion->prepare($sql_update);
            $stmt_update->execute([$monto_pagado, $fecha_pago, $id_mensualidad]);

            // 2. Insertar en pago_servicio (corregido con nombres correctos)
            $sql_insert = "INSERT INTO pago_servicio (
                           id_contrato,
                           fecha_pago,
                           metodo_pago,
                           estado_pago,
                           referecnia_pago,
                           observaciones,
                           creado_en,
                           id_mensualidad
                       ) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";

            $stmt_insert = $this->conn->conexion->prepare($sql_insert);
            $stmt_insert->execute([
                $id_contrato,
                $fecha_pago,
                $metodo_pago,
                'pagado',          // puedes ajustar este estado si usas otros
                $referencia_pago,
                $observaciones,
                $id_mensualidad
            ]);

            $this->conn->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->conexion->rollBack();
            return "Error al registrar el pago: " . $e->getMessage();
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


    public function listar_pagos_realizados()
    {
        $sql = "SELECT 
                    ps.id_pago_servicio as id,
                    c.nombre_completo as cliente,
                    m.monto as mensualidad,
                    ps.creado_en as fecha_pago
                FROM pago_servicio ps
                
                INNER JOIN 
                        contratos_servicio cs ON ps.id_contrato = cs.id_contrato
                INNER JOIN 
                        clientes c ON cs.id_cliente = c.id_cliente
                INNER JOIN mensualidades m on ps.id_mensualidad=m.id_mensualidad";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
