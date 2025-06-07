<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_servicio
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function listar_Servicios_modelo()
    {
        $sql = "SELECT id_servicio,descripcion FROM servicios";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        return $respuesta; //retorna texto
    }



    public function contrato_servicio($id_cliente, $id_plan, $id_tipo_conexion, $id_servicio, $fecha_contrato, $acceso_cliente, $observaciones)
    {
        try {
            $estado = "activo";

            // Insertar nuevo servicio
            $sql = "INSERT INTO contratos_servicio(id_cliente, id_plan,
                                                   id_servicio, id_tipo_conexion, 
                                                   fecha_contrato, estado, acceso_cliente, 
                                                   observaciones) 
                    VALUES (:id_cliente,:id_plan,
                            :id_servicio,:id_tipo_conexion,
                            :fecha_contrato,:estado,:acceso_cliente,
                            :observaciones)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':id_servicio', $id_servicio);
            $stmt->bindParam(':id_tipo_conexion', $id_tipo_conexion);
            $stmt->bindParam(':acceso_cliente', $acceso_cliente);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':fecha_contrato', $fecha_contrato);
            $stmt->bindParam(':estado', $estado);

            if ($stmt->execute()) {
                // obtener el id_contrato
                $id_contrato = $this->conn->conexion->lastInsertId();
                $monto = $this->obtenerPrecioPlan($id_plan);
                $this->crearMensualidades($id_contrato, $fecha_contrato, $monto, 1); //la 1ra mensualidad

                return [
                    "status" => "ok",
                    "mensaje" => "Servicio creado  correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al crear servicio."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Excepción: " . $e->getMessage()
            ];
        }
    }




    public function actualizar_datos_servicio($id_cliente, $id_plan, $id_tipo_conexion, $acceso_cliente, $id_cs)
    {
        $sql = "UPDATE contratos_servicio SET 
                        id_cliente=:id_cliente,id_plan=:id_plan,
                        id_tipo_conexion=:id_tipo_conexion, 
                        acceso_cliente=:acceso_cliente 
                WHERE id_contrato=:id_contrato";

        $stmt = $this->conn->conexion->prepare($sql);

        // Parámetros comunes
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindParam(':id_plan', $id_plan, PDO::PARAM_STR);
        $stmt->bindParam(':id_tipo_conexion', $id_tipo_conexion, PDO::PARAM_STR);
        $stmt->bindParam(':acceso_cliente', $acceso_cliente, PDO::PARAM_STR);
        $stmt->bindParam(':id_contrato', $id_cs, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensaje" => "Los datos del servicio han sido actualizados."
            ];
        } else {
            $errorInfo = $stmt->errorInfo();
            return [
                "status" => "error",
                "mensaje" => "No se pudo actualizar los datos del servicio.",
                "error" => $errorInfo
            ];
        }
    }


    public function listar_servicios()
    {
        $sql = "SELECT cs.id_contrato,cs.fecha_contrato,cs.estado,c.id_cliente,
                       p.id_plan,s.id_servicio,
                       cs.acceso_cliente,cs.observaciones,c.nombre_completo AS cliente,
                       p.nombre_plan,p.precio,p.velocidad,
                       s.descripcion AS servicio,
                       tp.id_tipo_conexion,
                       tp.desc_conexion AS tipo_conexion
                FROM contratos_servicio cs
                JOIN clientes c ON cs.id_cliente = c.id_cliente
                JOIN planes p ON cs.id_plan = p.id_plan
                JOIN servicios s ON cs.id_servicio = s.id_servicio
                JOIN tipo_conexion tp ON cs.id_tipo_conexion = tp.id_tipo_conexion";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
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




    // funcion para insertar casa mes una mensualidad
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
