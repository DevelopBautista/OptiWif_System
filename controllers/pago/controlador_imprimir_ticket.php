<?php
session_start();
require_once("../../models/modelo_generar_pdfPago.php");

if (!isset($_GET['num_factura'])) {
    die("No se proporcionó el número de factura.");
}

$numero_factura = $_GET['num_factura'];

// Para obtener los datos necesarios del pago/factura
require_once("../../models/modelo_conexion.php");
$conn = new Conexion();
$conn->conectar();

$sql = "SELECT ps.fecha_pago, 
               ps.metodo_pago,
               c.nombre_completo AS cliente,
               f.numero_factura,
               f.id_factura,
               f.id_pago_servicio,
               m.monto
        FROM facturas f
        JOIN pago_servicio ps ON f.id_pago_servicio = ps.id_pago_servicio
        JOIN mensualidades m ON ps.id_mensualidad = m.id_mensualidad
        JOIN contratos_servicio cs ON ps.id_contrato = cs.id_contrato
        JOIN clientes c ON cs.id_cliente = c.id_cliente
        WHERE f.numero_factura = ?";

$stmt = $conn->conexion->prepare($sql);
$stmt->execute([$numero_factura]);
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datos) {
    die("No se encontró la factura.");
}
//variables para enviar a la funcion imprimir_ticket_pos
$numero_factura = $datos['numero_factura'];
$cliente = $datos['cliente'];
$monto_total_pagar = $datos['monto'];
$fecha_pago = $datos['cliente'];
$metodo_pago = $datos['metodo_pago'];

$ticket = new modelo_ticket();
$ticket->imprimir_ticket_pos($numero_factura = $datos, $cliente, $monto_total_pagar, $fecha_pago, $metodo_pago);
