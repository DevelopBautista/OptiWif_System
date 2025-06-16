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
        $sql = "SELECT f.id_factura,f.numero_factura as nfactura,
                       c.nombre_completo as cliente,
                       c.id_cliente ,
                       ps.id_pago_servicio,
                       cs.id_contrato,
                       f.fecha_emision

                from facturas f
                INNER JOIN pago_servicio ps on ps.id_pago_servicio=f.id_pago_servicio
                INNER JOIN contratos_servicio cs on ps.id_pago_servicio=cs.id_servicio
                INNER JOIN clientes c on cs.id_cliente =c.id_cliente"; //fix this , that to get a client'name
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
