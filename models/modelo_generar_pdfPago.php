<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Mpdf\Mpdf;

class modelo_ticket
{
    private $conn;


    public function __construct()
    {
        require_once __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../views/libreporte/vendor/autoload.php';
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function imprimir_ticket_pos($numero_factura, $cliente, $mensualidad, $mora, $metodo_pago, $total_a_pagar)
    {
        $sql = "SELECT nombre, direccion, telefono, rnc, logo FROM empresa";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        $nombre = $empresa['nombre'];
        $direccionEmpresa = $empresa['direccion'];
        $telEmpresa = $empresa['telefono'];
        $rncEmpresa = $empresa['rnc'];
        $logoEmpresa = $empresa['logo'];
        $rutaLogo = __DIR__ . "/../views/logos/" . $logoEmpresa;

        $mpdf = new \Mpdf\Mpdf([
            'format' => [80, 150],
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $html = '<div style="text-align: center; font-family: monospace; font-size: 10pt; line-height: 1.4;">';

        if (!empty($logoEmpresa) && file_exists($rutaLogo)) {
            $html .= '
        <div style="margin-bottom: 4mm;">
            <img src="' . $rutaLogo . '" width="50mm" style="max-height: 25mm;" />
        </div>';
        }

        $html .= '
    <div style="margin-bottom: 4mm;">
        <strong>' . htmlspecialchars($nombre) . '</strong>
    </div>

    <div style="text-align: left; padding-left: 5px;">
        <div>Dir: ' . htmlspecialchars($direccionEmpresa) . '</div>
        <div>Tel: ' . htmlspecialchars($telEmpresa) . '</div>
        <div>RNC: ' . htmlspecialchars($rncEmpresa) . '</div>
    </div>

    <div style="margin: 6px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">&nbsp;</div>

    <div style="text-align: left; padding-left: 5px;">
        <div>N° Factura     : ' . htmlspecialchars($numero_factura) . '</div>
        <div>Cliente        : ' . htmlspecialchars($cliente) . '</div>
        <div>Mensualidad    : ' . MONEDA . number_format($mensualidad, 2, ',', '.') . '</div>';

        if ($mora > 0) {
            $html .= '<div>Mora           : ' . MONEDA . number_format($mora, 2, ',', '.') . '</div>';
        }

        $html .= '
        <div>Método de Pago : ' . htmlspecialchars($metodo_pago) . '</div>
        <div>Fecha          : ' . FECHA_HORA . '</div>
        <div style="margin-top: 5px;"><strong>Total a pagar: ' . MONEDA . number_format($total_a_pagar, 2, ',', '.') . '</strong></div>
    </div>

    <div style="margin: 6px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">&nbsp;</div>

    <div style="text-align: left; padding-left: 5px;">
        Atendido por: <strong>' . htmlspecialchars($_SESSION['user'] ?? 'Usuario') . '</strong>
    </div>

    <div style="margin: 6px 0; border-top: 1px dashed #000;">&nbsp;</div>

    <div style="text-align: center;">
        <span>¡Gracias por su preferencia!</span>
    </div>

    <div style="margin: 6px 0; border-top: 1px dashed #000;">&nbsp;</div>
</div>';

        $mpdf->WriteHTML($html);

        $nombre_archivo = preg_replace('/[^A-Za-z0-9_\-]/', '_', $numero_factura) . ".pdf";
        $ruta_guardado = __DIR__ . "/../views/libreporte/reports/facturas/" . $nombre_archivo;

        if (!file_exists(dirname($ruta_guardado))) {
            mkdir(dirname($ruta_guardado), 0777, true);
        }

        $mpdf->Output($ruta_guardado, \Mpdf\Output\Destination::FILE);

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=$nombre_archivo");
        readfile($ruta_guardado);
        exit;
    }
}
