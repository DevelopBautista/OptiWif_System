<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_ticket
{
    private $conn;

    public function __construct()
    {
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
        $direccionEmpresa = $empresa['direccion'];
        $telEmpresa = $empresa['telefono'];
        $rncEmpresa = $empresa['rnc'];
        $logoEmpresa = $empresa['logo']; // ejemplo: '684b782d2897c_logoEmpresa.png'
        $rutaLogo = __DIR__."/../views/logos/" . $logoEmpresa;

        //instanciar mPDF (formato tipo ticket POS)
        $mpdf = new Mpdf\Mpdf([
            'format' => [80, 130], // 80mm x 150mm
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $html = '
    <div style="text-align: center; font-family: monospace; font-size: 10pt; line-height: 1.4;">
        <!-- Logo (si se desea usar) -->
        <img src="' . $rutaLogo . '" style="width:60px; margin-bottom: 5px;"><br><br>
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
            <strong>Total      : ' . MONEDA . number_format($monto_total_pagar, 2, ',', '.') . '</strong><br>
        </div>
        <div style="margin: 6px 0;">================================</div>
        <div style="text-align: left; padding-left: 5px;">
            Atencion por : <strong>' . $_SESSION['user'] . '</strong><br>
        </div>
        <div style="margin: 6px 0;">===============================</div>
                         <span>¡Gracias por su preferencia!</span><br>
        <div style="margin: 6px 0;">===============================</div>
    </div>';
        // Escribir contenido
        $mpdf->WriteHTML($html);

        // Ruta de guardado
        $nombre_archivo = "Fact_" . $numero_factura . ".pdf";
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
