<?php
require_once '../../../../models/modelo_BuscarFecha_caja.php';
require_once '../../vendor/autoload.php';
$MC = new modelo_BuscarFecha_caja;

$fecha = $_GET['fecha'] ?? date('Y-m-d');
$resultado = $MC->buscarCierrePorFecha($fecha);

if (!$resultado) {
    die("No se encontró información para la fecha $fecha");
}

// Preparar los datos
$usuario = $resultado['nombre_usuario'];
$monto_inicial = number_format($resultado['monto_inicial'], 2);
$total_caja = number_format($resultado['total_caja'], 2);
$fecha_cierre = $resultado['fecha_cierre'] ?? '---';

// Crear PDF
$mpdf = new \Mpdf\Mpdf();
$html = "
<h2 style='text-align: center;'>Reporte de Cierre de Caja</h2>
<p><strong>Fecha de cierre:</strong> $fecha_cierre</p>
<p><strong>Usuario:</strong> $usuario</p>
<p><strong>Monto Inicial:</strong> RD$ $monto_inicial</p>
<p><strong>Total en Caja:</strong> RD$ $total_caja</p>
<p><strong>Fecha del Reporte:</strong> $fecha</p>
";

$mpdf->WriteHTML($html);
$mpdf->Output("reporte_cierre_caja_$fecha.pdf", "I"); // "I" para mostrarlo en el navegador
