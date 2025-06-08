<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_mensualidad
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function crearMensualidades($id_contrato, $fecha_inicio, $monto, $cantidad)
    {
        $estado = "pendiente";
        $cargo_extra = 0.00;

        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_vencimiento = date('Y-m-d', strtotime("+" . ($i + 1) . "month", strtotime($fecha_inicio)));

            $sql = "INSERT INTO mensualidades (id_contrato, monto, fecha_vencimiento, estado, fecha_inicio, cargo_extra)
                VALUES (:id_contrato, :monto, :fecha_vencimiento, :estado, :fecha_inicio, :cargo_extra)";
            $stmt = $this->conn->conexion->prepare($sql);
            $stmt->bindParam(':id_contrato', $id_contrato);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':cargo_extra', $cargo_extra);

            $stmt->execute();
        }
    }


    public function obtenerPrecioPlan($id_plan)
    {
        $sql = "SELECT precio FROM planes WHERE id_plan = :id_plan";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_plan', $id_plan);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['precio'];
        }
        return 0;
    }


    public function generarMensualidadSiguiente()
    {
        $estado = "pendiente";
        $cargo_extra = 0.00;

        // Buscar contratos activos
        $sqlContratos = "SELECT id_contrato, fecha_contrato, id_plan FROM contratos_servicio WHERE estado = 'activo'";
        $stmtContratos = $this->conn->conexion->prepare($sqlContratos);
        $stmtContratos->execute();
        $contratos = $stmtContratos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($contratos as $contrato) {
            $id_contrato = $contrato['id_contrato'];
            $monto = $this->obtenerPrecioPlan($contrato['id_plan']);

            // Buscar última mensualidad
            $sqlUltima = "SELECT fecha_vencimiento FROM mensualidades 
                      WHERE id_contrato = :id_contrato 
                      ORDER BY fecha_vencimiento DESC LIMIT 1";
            $stmtUltima = $this->conn->conexion->prepare($sqlUltima);
            $stmtUltima->bindParam(':id_contrato', $id_contrato);
            $stmtUltima->execute();
            $ultima = $stmtUltima->fetch(PDO::FETCH_ASSOC);

            if ($ultima) {
                $nueva_fecha_inicio = $ultima['fecha_vencimiento'];
                $fecha_vencimiento = date('Y-m-d', strtotime("+1 month", strtotime($nueva_fecha_inicio)));
            } else {
                $nueva_fecha_inicio = $contrato['fecha_contrato'];
                $fecha_vencimiento = date('Y-m-d', strtotime("+1 month", strtotime($nueva_fecha_inicio)));
            }

            // Insertar nueva mensualidad
            $sqlInsert = "INSERT INTO mensualidades (id_contrato, monto, fecha_vencimiento, estado, fecha_inicio, cargo_extra)
                      VALUES (:id_contrato, :monto, :fecha_vencimiento, :estado, :fecha_inicio, :cargo_extra)";
            $stmtInsert = $this->conn->conexion->prepare($sqlInsert);
            $stmtInsert->bindParam(':id_contrato', $id_contrato);
            $stmtInsert->bindParam(':monto', $monto);
            $stmtInsert->bindParam(':fecha_vencimiento', $fecha_vencimiento);
            $stmtInsert->bindParam(':estado', $estado);
            $stmtInsert->bindParam(':fecha_inicio', $nueva_fecha_inicio);
            $stmtInsert->bindParam(':cargo_extra', $cargo_extra);
            $stmtInsert->execute();
        }
    }
}
