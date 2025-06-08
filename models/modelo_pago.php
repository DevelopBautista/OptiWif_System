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

        // Obtener datos de la mensualidad
        $sql_mensualidad = "SELECT monto, fecha_vencimiento FROM mensualidades WHERE id_mensualidad = ?";
        $stmt_m = $this->conn->conexion->prepare($sql_mensualidad);
        $stmt_m->execute([$id_mensualidad]);
        $mensualidad = $stmt_m->fetch(PDO::FETCH_ASSOC);

        if (!$mensualidad) {
            return "No se encontró la mensualidad.";
        }

        $monto_original = $mensualidad['monto'];
        $fecha_vencimiento = $mensualidad['fecha_vencimiento'];

        // Calcular mora
        $fecha_actual = new DateTime($fecha_pago);
        $fecha_venc = new DateTime($fecha_vencimiento);
        $dias_mora = 0;

        if ($fecha_actual > $fecha_venc) {
            $interval = $fecha_actual->diff($fecha_venc);
            $dias_mora = $interval->days;
        }

        $tasa_interes_diaria = 0.01; // 1% diario
        $cargo_mora = $monto_original * $tasa_interes_diaria * $dias_mora;

        $monto_total_pagar = $monto_original + $cargo_mora;

        // Iniciar transacción
        $this->conn->conexion->beginTransaction();

        try {
            // 1. Actualizar mensualidad
            $sql_update = "UPDATE mensualidades 
                       SET estado = 'pagado', 
                           monto = ?, 
                           fecha_pago = ?, 
                           cargo_extra = ?
                       WHERE id_mensualidad = ?";
            $stmt_update = $this->conn->conexion->prepare($sql_update);
            $stmt_update->execute([$monto_total_pagar, $fecha_pago, $cargo_mora, $id_mensualidad]);

            // 2. Insertar en pago_servicio
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
                'pagado',
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
                       m.fecha_inicio,
                       m.fecha_vencimiento as fecha_pagos
                FROM mensualidades m
                INNER JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato
                INNER JOIN clientes c ON cs.id_cliente = c.id_cliente
                INNER JOIN planes p ON cs.id_plan = p.id_plan ";

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
