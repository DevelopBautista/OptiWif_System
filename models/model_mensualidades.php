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

    // Crea mensualidades iniciales 
    public function crearMensualidades($id_contrato, $fecha_inicio, $monto, $cantidad, $cargo_extra)
    {
        $estado = "pendiente";

        if ($cantidad <= 0) {
            return false; // 0 lanzar excepción
        }

        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_vencimiento = date('Y-m-d', strtotime("+" . ($i + 1) . " month", strtotime($fecha_inicio)));

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
        return true;
    }

    // Obtiene precio base del plan
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

    // Obtiene cargo_extra del contrato para usarlo en mensualidades nuevas
    public function obtenerCargoExtraContrato($id_contrato)
    {
        $sql = "SELECT cargo_extra FROM contratos_servicio WHERE id_contrato = :id_contrato";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_contrato', $id_contrato);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['cargo_extra'] ?? 0;
        }
        return 0;
    }

    // Genera la siguiente mensualidad para cada contrato activo, pasando cargo_extra real
    public function generarMensualidadSiguiente()
    {
        $estado = "pendiente";

        try {
            // Obtener todos los contratos activos
            $sqlContratos = "SELECT id_contrato, fecha_contrato 
                         FROM contratos_servicio 
                         WHERE estado = 'activo'";
            $stmtContratos = $this->conn->conexion->prepare($sqlContratos);
            $stmtContratos->execute();
            $contratos = $stmtContratos->fetchAll(PDO::FETCH_ASSOC);

            foreach ($contratos as $contrato) {
                $id_contrato = $contrato['id_contrato'];
                $fecha_contrato = $contrato['fecha_contrato'];

                // Obtener monto del plan y cargo extra asociados al contrato
                $monto = $this->obtenerPrecioPlanPorContrato($id_contrato);
                $cargo_extra = $this->obtenerCargoExtraContrato($id_contrato);

                // Obtener la última mensualidad registrada
                $sqlUltima = "SELECT fecha_vencimiento 
                          FROM mensualidades 
                          WHERE id_contrato = :id_contrato 
                          ORDER BY fecha_vencimiento DESC 
                          LIMIT 1";
                $stmtUltima = $this->conn->conexion->prepare($sqlUltima);
                $stmtUltima->bindParam(':id_contrato', $id_contrato);
                $stmtUltima->execute();
                $ultima = $stmtUltima->fetch(PDO::FETCH_ASSOC);

                // Determinar fecha de inicio para la nueva mensualidad
                $nueva_fecha_inicio = $ultima ? $ultima['fecha_vencimiento'] : $fecha_contrato;

                // Calcular nueva fecha de vencimiento (+1 mes)
                $fecha_vencimiento = date('Y-m-d', strtotime("+1 month", strtotime($nueva_fecha_inicio)));

                // Verificar si ya existe una mensualidad con esa fecha para ese contrato
                $sqlExiste = "SELECT COUNT(*) 
                          FROM mensualidades 
                          WHERE id_contrato = :id_contrato 
                          AND fecha_vencimiento = :fecha_vencimiento";
                $stmtExiste = $this->conn->conexion->prepare($sqlExiste);
                $stmtExiste->bindParam(':id_contrato', $id_contrato);
                $stmtExiste->bindParam(':fecha_vencimiento', $fecha_vencimiento);
                $stmtExiste->execute();

                if ($stmtExiste->fetchColumn() == 0) {
                    // Insertar nueva mensualidad
                    $sqlInsert = "INSERT INTO mensualidades (
                                              id_contrato, monto, 
                                              fecha_vencimiento, 
                                              estado, fecha_inicio, 
                                              cargo_extra) 
                                   VALUES (:id_contrato, 
                                            :monto, :fecha_vencimiento, 
                                            :estado, :fecha_inicio, 
                                            :cargo_extra )";
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
        } catch (PDOException $e) {
            // Puedes registrar errores si lo deseas
            error_log("Error al generar mensualidades: " . $e->getMessage());
        }
    }


    // Función auxiliar para obtener precio plan por id_contrato
    private function obtenerPrecioPlanPorContrato($id_contrato)
    {
        $sql = "SELECT p.precio 
                FROM contratos_servicio cs
                INNER JOIN planes p ON cs.id_plan = p.id_plan
                WHERE cs.id_contrato = :id_contrato";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_contrato', $id_contrato);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['precio'];
        }
        return 0;
    }

    // Aplica mora a mensualidades pendientes que pasaron el plazo de gracia (dias_mas) y no tienen mora aplicada
    public function aplicarMoraMensualidades()
    {
        $sql = "SELECT m.id_mensualidad, m.fecha_vencimiento, cs.cargo_extra, cs.dias_mas
            FROM mensualidades m
            INNER JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato
            WHERE m.estado = 'pendiente'
              AND m.mora_aplicada = 0";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $mensualidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($mensualidades as $m) {
            $fecha_limite = date('Y-m-d', strtotime($m['fecha_vencimiento'] . " +{$m['dias_mas']} days"));

            if (date('Y-m-d') > $fecha_limite) {
                $sqlUpdate = "UPDATE mensualidades 
                          SET monto = monto + :cargo_extra, mora_aplicada = 1 
                          WHERE id_mensualidad = :id";
                $stmtUpdate = $this->conn->conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':cargo_extra', $m['cargo_extra']);
                $stmtUpdate->bindParam(':id', $m['id_mensualidad']);
                $stmtUpdate->execute();
            }
        }
    }
}
