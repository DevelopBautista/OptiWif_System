<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../config/config.php");
require_once __DIR__ . '/../views/libreporte/vendor/autoload.php';

class modelo_pago
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");

        $this->conn = new Conexion();
        $this->conn->conectar();
    }

    public function registrar_pago($id_mensualidad, $monto_total_pagar, $fecha_pago, $metodo_pago, $referencia_pago, $observaciones, $dias_mas, $cargo_extra)
    {
        // Obtener datos de la mensualidad y del contrato
        $sql_datos = "SELECT m.monto, m.fecha_vencimiento, m.estado, cs.id_contrato, c.nombre_completo as cliente
                    FROM mensualidades m 
                    JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato 
                    JOIN clientes c on cs.id_cliente = c.id_cliente
                    WHERE m.id_mensualidad = ?";
        $stmt = $this->conn->conexion->prepare($sql_datos);
        $stmt->execute([$id_mensualidad]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return "No se encontró la mensualidad o el contrato relacionado.";
        }

        // Verificar si ya está pagada
        if ($row['estado'] === 'pagado') {
            return "Esta mensualidad ya ha sido pagada.";
        }

        $id_contrato = $row['id_contrato'];
        $monto_original = $row['monto'];
        $fecha_vencimiento = new DateTime($row['fecha_vencimiento']);
        $cliente = $row['cliente'];

        // Calcular fecha con días de gracia
        $fecha_gracia = clone $fecha_vencimiento;
        $dias_mas = (int) $dias_mas;
        $fecha_gracia->modify("+{$dias_mas} days");
        $fecha_actual = new DateTime($fecha_pago);

        // Calcular cargo de mora solo si se pasa de la fecha de gracia
        $cargo_mora = 0;
        if ($fecha_actual > $fecha_gracia) {
            $cargo_mora = $cargo_extra;
        }

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

            // 3. Generar factura
            $id_pago = $this->conn->conexion->lastInsertId();
            $numero_factura = 'Fact-' . str_pad($id_pago, 8, '0', STR_PAD_LEFT);
            try {
                $sql_factura = "INSERT INTO facturas (numero_factura, id_pago_servicio, fecha_emision) 
                            VALUES (?, ?, NOW())";
                $stmt_factura = $this->conn->conexion->prepare($sql_factura);
                $stmt_factura->execute([$numero_factura, $id_pago]);
            } catch (Exception $e) {
                error_log("Error insertando factura: " . $e->getMessage());
            }

            // 4. Imprimir POS
            try {
                $this->imprimir_ticket_pos($numero_factura, $cliente, $monto_total_pagar, $fecha_pago, $metodo_pago);
            } catch (Exception $e) {
                error_log("Error imprimiendo POS: " . $e->getMessage());
            }

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



    public function verificar_pago_existe($id_mensualidad)
    {
        try {

            $sql = ("SELECT estado FROM mensualidades WHERE id_mensualidad = :id_mensualidad");
            $stmt = $this->conn->conexion->prepare($sql);
            $stmt->bindParam(':id_mensualidad', $id_mensualidad, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if ($row['estado'] === 'pagado') {
                    echo json_encode(['exito' => false, 'mensaje' => 'La mensualidad ya fue pagada anteriormente']);
                    exit;
                }
            } else {

                echo json_encode(['exito' => false, 'mensaje' => 'Mensualidad no encontrada']);
                exit;
            }
        } catch (PDOException $e) {

            echo json_encode(['exito' => false, 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()]);
            exit;
        }
    }


    private function imprimir_ticket_pos($numero_factura, $cliente, $monto, $fecha_pago, $metodo_pago)
    {

        $html = '
    <style>
        body { font-family: monospace; font-size: 12px; }
        .ticket { width: 250px; padding: 10px; border: 1px dashed #000; }
        .line { text-align: center; border-top: 1px dashed #000; margin: 5px 0; }
    </style>
    <div class="ticket">
        <div class="line">============================</div>
        <div style="text-align: center;"><strong>MundoTecno</strong></div>
        <div class="line">============================</div>
        <p>N°: <strong>' . $numero_factura . '</strong></p>
        <p>Cliente: ' . htmlspecialchars($cliente) . '</p>
        <p>Fecha: ' . $fecha_pago . '</p>
        <p>Método: ' . $metodo_pago . '</p>
        <p><strong>Total: ' . MONEDA . number_format($monto, 2, ',', '.') . '</strong></p>
        <div class="line">============================</div>
        <div style="text-align: center;">¡Gracias por su pago!</div>
        <div class="line">============================</div>
    </div>';

        $mpdf = new \Mpdf\Mpdf(['format' => [80, 150]]); // Tamaño tipo ticket POS
        $mpdf->WriteHTML($html);

        $nombre_archivo = "$numero_factura.pdf";
        $ruta_archivo = __DIR__ . "/../views/libreporte/reports/$nombre_archivo";

        $mpdf->Output($ruta_archivo, 'F'); // Guardar en el servidor
    }
}
