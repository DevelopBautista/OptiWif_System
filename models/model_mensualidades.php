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

                // Obtener monto del plan y cargo extra
                $monto = $this->obtenerPrecioPlanPorContrato($id_contrato);
                $cargo_extra = $this->obtenerCargoExtraContrato($id_contrato);

                // Última mensualidad
                $sqlUltima = "SELECT fecha_vencimiento 
                          FROM mensualidades 
                          WHERE id_contrato = :id_contrato 
                          ORDER BY fecha_vencimiento DESC 
                          LIMIT 1";
                $stmtUltima = $this->conn->conexion->prepare($sqlUltima);
                $stmtUltima->bindParam(':id_contrato', $id_contrato);
                $stmtUltima->execute();
                $ultima = $stmtUltima->fetch(PDO::FETCH_ASSOC);

                // Determinar fecha base
                $ultima_fecha = $ultima ? $ultima['fecha_vencimiento'] : $fecha_contrato;
                $proxima_fecha = date('Y-m-d', strtotime("+1 month", strtotime($ultima_fecha)));

                // Verifica si ya pasó la fecha de vencimiento (solo si toca generar la nueva)
                $hoy = date('Y-m-d');
                if ($proxima_fecha <= $hoy) {

                    // Verifica que no exista duplicado
                    $sqlExiste = "SELECT COUNT(*) 
                              FROM mensualidades 
                              WHERE id_contrato = :id_contrato 
                              AND fecha_vencimiento = :fecha_vencimiento";
                    $stmtExiste = $this->conn->conexion->prepare($sqlExiste);
                    $stmtExiste->bindParam(':id_contrato', $id_contrato);
                    $stmtExiste->bindParam(':fecha_vencimiento', $proxima_fecha);
                    $stmtExiste->execute();

                    if ($stmtExiste->fetchColumn() == 0) {
                        // Insertar mensualidad
                        $sqlInsert = "INSERT INTO mensualidades (
                                      id_contrato, monto, 
                                      fecha_vencimiento, 
                                      estado, fecha_inicio, 
                                      cargo_extra) 
                                 VALUES (:id_contrato, 
                                         :monto, :fecha_vencimiento, 
                                         :estado, :fecha_inicio, 
                                         :cargo_extra)";
                        $stmtInsert = $this->conn->conexion->prepare($sqlInsert);
                        $stmtInsert->bindParam(':id_contrato', $id_contrato);
                        $stmtInsert->bindParam(':monto', $monto);
                        $stmtInsert->bindParam(':fecha_vencimiento', $proxima_fecha);
                        $stmtInsert->bindParam(':estado', $estado);
                        $stmtInsert->bindParam(':fecha_inicio', $ultima_fecha);
                        $stmtInsert->bindParam(':cargo_extra', $cargo_extra);
                        $stmtInsert->execute();
                    }
                }
            }
        } catch (PDOException $e) {
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
        try {
            $sql = "SELECT m.id_mensualidad, m.fecha_vencimiento, cs.cargo_extra, cs.dias_mas
                FROM mensualidades m
                INNER JOIN contratos_servicio cs ON m.id_contrato = cs.id_contrato
                WHERE m.estado = 'pendiente'
                  AND m.mora_aplicada = 0";

            $stmt = $this->conn->conexion->prepare($sql);
            $stmt->execute();
            $mensualidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $fecha_actual = date('Y-m-d');

            foreach ($mensualidades as $m) {
                // Validar que dias_mas y cargo_extra sean numéricos
                $dias_mas = is_numeric($m['dias_mas']) ? (int)$m['dias_mas'] : 0;
                $cargo_extra = is_numeric($m['cargo_extra']) ? (float)$m['cargo_extra'] : 0;

                // Calcular la fecha límite: vencimiento + días de gracia
                $fecha_limite = date('Y-m-d', strtotime($m['fecha_vencimiento'] . " +{$dias_mas} days"));

                // Si ya pasó la fecha límite y hay un cargo extra válido
                if ($fecha_actual > $fecha_limite && $cargo_extra > 0) {
                    $sqlUpdate = "UPDATE mensualidades 
                              SET monto = monto + :cargo_extra, 
                                  mora_aplicada = 1 
                              WHERE id_mensualidad = :id";
                    $stmtUpdate = $this->conn->conexion->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':cargo_extra', $cargo_extra);
                    $stmtUpdate->bindParam(':id', $m['id_mensualidad']);
                    $stmtUpdate->execute();
                }
            }
        } catch (PDOException $e) {
            error_log("Error al aplicar mora: " . $e->getMessage());
        }
    }
}
