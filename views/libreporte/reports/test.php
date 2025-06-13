<?php
include_once(__DIR__ . "/../../../config/config.php");
require_once(__DIR__ . '/../vendor/autoload.php');
require_once("../../../models/modelo_conexion.php");


$conexion = new conexion();

$conexion->conectar();



//obtener datos de la empresa desde la db
$sql = "SELECT nombre,direccion,telefono,rnc FROM empresa";

$stmt = $conexion->conexion->prepare($sql);

$stmt->execute();

$empresa = $stmt->fetch(PDO::FETCH_ASSOC);

use Mpdf\Mpdf;

// Datos del ticket
$numero_factura = "000123";
$cliente = "Juan Pérez";
$fecha_pago = date("d/m/Y");
$metodo_pago = "Efectivo";
$monto = 120.5;

//datos de la empresa
$nombreEmpresa = $empresa['nombre'];
$direccionEmpresa = $empresa['direccion'];
$telEmpresa = $empresa['telefono'];
$rncEmpresa = $empresa['rnc'];



// Crear instancia mPDF (formato tipo ticket POS)
$mpdf = new Mpdf([
    'format' => [80, 150], // 80mm x 150mm
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
]);



$html = '
<div style="text-align: center; font-family: monospace; font-size: 10pt; line-height: 1.4;">
    <!-- Logo (si se desea usar) -->
    <img src="../../logos/684b782d2897c_logoEmpresa.png" style="width:60px; margin-bottom: 5px;"><br>
    <div style="text-align: left; padding-left: 5px;">
        <span>Dir: ' . $direccionEmpresa . '</span><br>
        <span>Tel: ' . $telEmpresa . '</span><br>
        <span>RNC: ' . $rncEmpresa . '</span><br>
    </div>
    <div style="margin: 6px 0;">================================</div>
    <div style="text-align: left; padding-left: 5px;">
        N° Factura : ' . $numero_factura . '<br>
        Cliente    : ' . $cliente . '<br>
        Mensualidad: ' . $fecha_pago . '<br>
        Fecha      : ' . FECHA_HORA . '<br>
        Método     : ' . $metodo_pago . '<br>
        <strong>Total      : ' . MONEDA . number_format($monto, 2, ',', '.') . '</strong><br>
    </div>

    <div style="margin: 6px 0;">===============================</div>
                         <span>¡Gracias por su preferencia!</span><br>
    <div style="margin: 6px 0;">===============================</div>
</div>
';


// Escribir contenido
$mpdf->WriteHTML($html);

// Ruta de guardado
$nombre_archivo = "Fact_" . $numero_factura . ".pdf";
$ruta_guardado = __DIR__ . "/facturas/" . $nombre_archivo;

// Asegurarse de que la carpeta exista
if (!file_exists(__DIR__ . "/facturas")) {
    mkdir(__DIR__ . "/facturas", 0777, true);
}

// Guardar el archivo en la carpeta
$mpdf->Output($ruta_guardado, \Mpdf\Output\Destination::FILE);

// Redirigir al navegador para imprimir (mostrando el PDF)
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=$nombre_archivo");
readfile($ruta_guardado);
exit;
