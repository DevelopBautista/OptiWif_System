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


    public function imprimir_ticket_pos($numero_factura, $cliente, $monto_total_pagar, $fecha_pago, $metodo_pago)
    {
        $sql = "SELECT nombre,direccion,telefono,rnc,logo FROM empresa";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        //datos de la empresa
        $nombre = $empresa['nombre'];
        $direccionEmpresa = $empresa['direccion'];
        $telEmpresa = $empresa['telefono'];
        $rncEmpresa = $empresa['rnc'];
        $logoEmpresa = $empresa['logo'];
        $rutaLogo = __DIR__ . "/../views/logos/" . $logoEmpresa;

        //instanciar mPDF (formato tipo ticket POS)

        $mpdf = new Mpdf([
            'format' => [80, 150], // 80mm x 150mm
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $html = '
    <div style="text-align: center; font-family: monospace; font-size: 10pt; line-height: 1.4;">

                <!-- Logo de la empresa -->
                <div style="margin-bottom: 4mm;">
                    <img src="' . $rutaLogo . '" width="50mm" style="max-height: 25mm;" />
                </div>

                 <!-- Nombre de la empresa -->
                <div style="margin-bottom: 4mm;">
                    <strong>' . htmlspecialchars($nombre) . '</strong>
                </div>

            <!-- Datos de la empresa -->
            <div style="text-align: left; padding-left: 5px;">
                <div>Dir: ' . htmlspecialchars($direccionEmpresa) . '</div>
                <div>Tel: ' . htmlspecialchars($telEmpresa) . '</div>
                <div>RNC: ' . htmlspecialchars($rncEmpresa) . '</div>
            </div>

        <div style="margin: 6px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">&nbsp;</div>

                <!-- Datos de la factura -->
            <div style="text-align: left; padding-left: 5px;">
                <div>N° Factura  : ' . htmlspecialchars($numero_factura) . '</div>
                <div>Cliente     : ' . htmlspecialchars($cliente) . '</div>
                <div>Mensualidad : ' . htmlspecialchars($fecha_pago) . '</div>
                <div>Fecha       : ' . FECHA_HORA . '</div>
                <div>Método      : ' . htmlspecialchars($metodo_pago) . '</div>
            <div><strong>Total : ' . MONEDA . number_format($monto_total_pagar, 2, ',', '.') . '</strong></div>
        </div>

        <div style="margin: 6px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">&nbsp;</div>

        <!-- Atendido por -->
        <div style="text-align: left; padding-left: 5px;">
            Atendido por: <strong>' . htmlspecialchars($_SESSION['user'] ?? 'Usuario') . '</strong>
        </div>

        <div style="margin: 6px 0; border-top: 1px dashed #000;">&nbsp;</div>

        <!-- Mensaje final -->
        <div style="text-align: center;">
            <span>¡Gracias por su preferencia!</span>
        </div>

        <div style="margin: 6px 0; border-top: 1px dashed #000;">&nbsp;</div>
    </div>';

        // Escribir contenido
        $mpdf->WriteHTML($html);

        // Ruta de guardado
        $nombre_archivo = $numero_factura . ".pdf";
        $ruta_guardado = __DIR__ . "/../views/libreporte/reports/facturas/" . $nombre_archivo;

        // Asegurarse de que la carpeta exista
        if (!file_exists(__DIR__ . "/../views/libreporte/reports/facturas/")) {
            mkdir(__DIR__ . "/../views/libreporte/reports/facturas/", 0777, true);
        }

        // Guardar el archivo en la carpeta
        $mpdf->Output($ruta_guardado, \Mpdf\Output\Destination::FILE);

        // Redirigir al navegador para imprimir (mostrando el PDF)
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=$nombre_archivo");
        readfile($ruta_guardado);
        exit;
    }
}
