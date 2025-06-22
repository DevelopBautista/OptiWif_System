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
$total_movimientos = number_format($resultado['total_movimientos'], 2);
$total_caja = number_format($resultado['total_caja'], 2);
$fecha_cierre = $resultado['fecha_cierre'] ?? '---';

// Crear PDF
$mpdf = new \Mpdf\Mpdf();
$html = "
    <div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; color: #333;'>
        <h2 style='text-align: center; color: #2c3e50; border-bottom: 2px solid #2980b9; padding-bottom: 10px;'>
            Reporte de Cierre de Caja
        </h2>
        <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
            <tr>
                <td style='padding: 8px; font-weight: bold; width: 40%; color: #34495e;'>Fecha de cierre:</td>
                <td style='padding: 8px;'>$fecha_cierre</td>
            </tr>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 8px; font-weight: bold; color: #34495e;'>Usuario:</td>
                <td style='padding: 8px;'>$usuario</td>
            </tr>
            <tr>
                <td style='padding: 8px; font-weight: bold; color: #34495e;'>Monto Inicial:</td>
                <td style='padding: 8px;'>RD$ $monto_inicial</td>
            </tr>
             <tr>
                <td style='padding: 8px; font-weight: bold; color: #34495e;'>Total de pagos procesados:</td>
                <td style='padding: 8px;'>RD$ $total_movimientos</td>
            </tr>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 8px; font-weight: bold; color: #34495e;'>Total en Caja:</td>
                <td style='padding: 8px;'>RD$ $total_caja</td>
            </tr>
            <tr>
                <td style='padding: 8px; font-weight: bold; color: #34495e;'>Fecha del Reporte:</td>
                <td style='padding: 8px;'>$fecha</td>
            </tr>
        </table>
    </div>
";


$mpdf->WriteHTML($html);
$mpdf->Output("reporte_cierre_caja_$fecha.pdf", "I"); // "I" para mostrarlo en el navegador
