<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../config/config.php");
require_once __DIR__ . '/../views/libreporte/vendor/autoload.php';
require_once("modelo_generar_pdfPago.php");



class modelo_pago
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");

        $this->conn = new Conexion();
        $this->conn->conectar();
    }

    public function registrar_pago($id_mensualidad, $monto_total_pagar, $fecha_pago, $metodo_pago, $referencia_pago, $observaciones, $mora_pagada)
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
        $cliente = $row['cliente'];

        $cargo_mora = floatval($mora_pagada);  // Mora ya calculada y pasada como parámetro

        // Recalcular el monto total a pagar (monto original + mora)
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

            // 2. Insertar en pago_servicio con mora_pagada incluida
            $sql_insert = "INSERT INTO pago_servicio (
               id_contrato,
               fecha_pago,
               metodo_pago,
               estado_pago,
               referencia_pago,
               observaciones,
               creado_en,
               id_mensualidad,
               mora_pagada
           ) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

            $stmt_insert = $this->conn->conexion->prepare($sql_insert);
            $stmt_insert->execute([
                $id_contrato,
                $fecha_pago,
                $metodo_pago,
                'pagado',
                $referencia_pago,
                $observaciones,
                $id_mensualidad,
                $cargo_mora
            ]);

            // 3. Generar factura con número
            $id_pago = $this->conn->conexion->lastInsertId();
            $numero_factura = 'Fact-' . str_pad($id_pago, 8, '0', STR_PAD_LEFT);

            $sql_factura = "INSERT INTO facturas (numero_factura, id_pago_servicio, fecha_emision) 
                        VALUES (?, ?, NOW())";
            $stmt_factura = $this->conn->conexion->prepare($sql_factura);
            $stmt_factura->execute([$numero_factura, $id_pago]);

            $this->conn->conexion->commit();

            return json_encode([
                'success' => true,
                'nfactura' => $numero_factura
            ]);
        } catch (Exception $e) {
            $this->conn->conexion->rollBack();
            return "Error al registrar el pago: " . $e->getMessage();
        }
    }

    public function listar_pagos() //esta 
    {
        $sql = "SELECT m.id_mensualidad,
                       c.nombre_completo as cliente,
                       p.nombre_plan as plan,
                       m.monto,
                       m.estado,
                       m.cargo_extra as mora,
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


    public function listar_pagos_realizados() // y esta
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
            $sql = "SELECT estado FROM mensualidades WHERE id_mensualidad = :id_mensualidad";
            $stmt = $this->conn->conexion->prepare($sql);
            $stmt->bindParam(':id_mensualidad', $id_mensualidad, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['estado'] === 'pagado'; // true si está pagado, false si no
            } else {
                return null; // mensualidad no encontrada
            }
        } catch (PDOException $e) {
            return false; // error silencioso, puedes loguear si quieres
        }
    }


    public function hayCajaAbierta($id_usuario)
    {
        require_once("modelo_caja_diaria.php");
        $caja = new modelo_caja_diaria();
        return $caja->existeCajaAbierta($id_usuario);
    }
}
