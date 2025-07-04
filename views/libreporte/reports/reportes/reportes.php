<?php
require_once '../../../../models/modelo_BuscarFecha_caja.php';
require_once '../../vendor/autoload.php';
$MC = new modelo_BuscarFecha_caja;

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$resultado = $MC->buscarCierrePorFecha($fecha);

$pagos_cliente = $MC->contador_pagos($fecha);

if (!$resultado) {
    die("No se encontró información para la fecha $fecha");
}

// Preparar los datos
$usuario = $resultado['nombre_usuario'] ?? '---';
$monto_inicial = floatval($resultado['monto_apertura'] ?? 0);
$total_movimientos = floatval($resultado['total_sistema'] ?? 0);
$total_real = floatval($resultado['total_real'] ?? 0);
$diferencia = ($total_real + $monto_inicial) - $total_movimientos;

$pagos_procesados = $total_movimientos - $monto_inicial;

$monto_esperado = $pagos_procesados + $monto_inicial;

$fecha_cierre = $resultado['fecha_cierre'] ?? '---';

// Determinar estado
$estado = 'Caja cuadrada';
$colorEstado = '#27ae60'; // Verde por defecto
$mensajeEstado = 'La caja fue cerrada correctamente.';

if ($diferencia > 0) {
    $estado = 'Sobra dinero';
    $colorEstado = '#2980b9'; // Azul
    $mensajeEstado = "Sobra RD$ " . number_format($diferencia, 2);
} elseif ($diferencia < 0) {
    $estado = 'Falta dinero';
    $colorEstado = '#c0392b'; // Rojo
    $mensajeEstado = "Faltan RD$ " . number_format(abs($diferencia), 2);
}

// Formatos
$fmt_inicial = number_format($monto_inicial, 2);
$fmt_movimientos = number_format($total_movimientos, 2);
$fmt_total_real = number_format($total_real, 2);
$fmt_diferencia = number_format($diferencia, 2);
$fmt_pagos_procesados = number_format($pagos_procesados, 2);
$fmt_monto_esperado = number_format($monto_esperado, 2);
$fmt_pagos_clientes = number_format($pagos_cliente['total_pagos']);

// Crear PDF
if (!is_writable(__DIR__ . '/../../tmp/mpdf')) {
    die('La carpeta tmp/mpdf no es escribible.');
}

$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../tmp/mpdf']);
$html = "
    <div style='max-width: 700px; margin: auto; font-family: Arial, sans-serif; color: #333;'>
        <h2 style='text-align: center; color: #2c3e50; border-bottom: 2px solid #2980b9; padding-bottom: 10px;'>
             Reporte de Cierre de Caja
        </h2>
        <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
            <tr><td style='padding: 8px; font-weight: bold;'> Usuario:</td><td style='padding: 8px;'>$usuario</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Fecha del reporte:</td><td style='padding: 8px;'>$fecha</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Fecha de cierre:</td><td style='padding: 8px;'>$fecha_cierre</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Monto inicial:</td><td style='padding: 8px;'>RD$ $fmt_inicial</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Pagos procesados:</td><td style='padding: 8px;'>RD$ $$fmt_pagos_procesados</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Clientes que realizaron pagos:</td><td style='padding: 8px;'>$fmt_pagos_clientes</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Total contado(Caja):</td><td style='padding: 8px;'>RD$ $fmt_total_real</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Monto esperado (sistema):</td><td style='padding: 8px;'>RD$ $fmt_monto_esperado</td></tr>
            <tr><td style='padding: 8px; font-weight: bold;'> Diferencia:</td><td style='padding: 8px;'>RD$ $fmt_diferencia</td></tr>
        </table>
        <div style='margin-top: 30px; padding: 15px; background-color: #f0f0f0; border-left: 5px solid $colorEstado;'>
            <strong style='color: $colorEstado;'>Estado:</strong> $mensajeEstado
        </div>
    </div>
";

$mpdf->WriteHTML($html);
$mpdf->Output("reporte_cierre_caja_$fecha.pdf", "I");
