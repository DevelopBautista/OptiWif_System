<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_facturas
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();

        $this->conn->conectar();
    }


    public function listar_facturas()
    {
        $sql = "SELECT f.id_factura,f.numero_factura AS nfactura,
                       c.nombre_completo AS cliente,
                       c.id_cliente,
                       ps.id_pago_servicio,
                       cs.id_contrato,
                       f.fecha_emision
                FROM facturas f
                INNER JOIN pago_servicio ps ON ps.id_pago_servicio = f.id_pago_servicio
                INNER JOIN contratos_servicio cs ON ps.id_contrato = cs.id_contrato
                INNER JOIN clientes c ON cs.id_cliente = c.id_cliente;";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
