<?php
session_start();
require_once("../../models/modelo_generar_pdfPago.php");

if (!isset($_GET['num_factura'])) {
    error_log("No se proporcionó el número de factura.", 3, 'logs/error_ticket.log');
    header("Location: error_ticket.php?msg=" . urlencode("No se proporcionó el número de factura."));
    exit;
}

$numero_factura = $_GET['num_factura'];

// Para obtener los datos necesarios del pago/factura
require_once("../../models/modelo_conexion.php");
$conn = new Conexion();
$conn->conectar();

$sql = "SELECT f.numero_factura,
               f.fecha_emision,
               p.metodo_pago,
               p.referencia_pago,
               p.observaciones,
               p.mora_pagada,
               p.fecha_pago,
               c.nombre_completo AS cliente,
               (m.monto - p.mora_pagada) AS mensualidad_base,
               m.monto AS total_pagado
        FROM facturas f
        JOIN pago_servicio p ON f.id_pago_servicio = p.id_pago_servicio
        JOIN mensualidades m ON p.id_mensualidad = m.id_mensualidad
        JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato
        JOIN clientes c ON cs.id_cliente = c.id_cliente
        WHERE f.numero_factura = ?";

try {
    $stmt = $conn->conexion->prepare($sql);
    $stmt->execute([$numero_factura]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datos) {
        throw new Exception("No se encontró la factura.");
    }

    $numero_factura = $datos['numero_factura'];
    $cliente = $datos['cliente'];
    $mensualidad_base = floatval($datos['mensualidad_base']); // ahora sí es solo la mensualidad
    $mora = floatval($datos['mora_pagada']);
    $metodo_pago = $datos['metodo_pago'];
    $total_pagado = floatval($datos['total_pagado']); // = mensualidad_base + mora

    $ticket = new modelo_ticket();
    $ticket->imprimir_ticket_pos($numero_factura, $cliente, $mensualidad_base, $mora, $metodo_pago, $total_pagado);
} catch (Exception $e) {
    die("Error al generar el ticket: " . $e->getMessage());
}
