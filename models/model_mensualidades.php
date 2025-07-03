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
            return false;
        }

        try {
            for ($i = 0; $i < $cantidad; $i++) {
                $fecha_vencimiento = date('Y-m-d', strtotime("+" . ($i + 1) . " month", strtotime($fecha_inicio)));

                // Verificar si ya existe una mensualidad para evitar duplicados
                $sqlCheck = "SELECT COUNT(*) FROM mensualidades WHERE id_contrato = :id_contrato AND fecha_vencimiento = :fecha_vencimiento";
                $stmtCheck = $this->conn->conexion->prepare($sqlCheck);
                $stmtCheck->bindParam(':id_contrato', $id_contrato);
                $stmtCheck->bindParam(':fecha_vencimiento', $fecha_vencimiento);
                $stmtCheck->execute();
                if ($stmtCheck->fetchColumn() > 0) {
                    // Ya existe la mensualidad para esa fecha, saltar a la siguiente
                    continue;
                }

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
        } catch (PDOException $e) {
            error_log("Error en crearMensualidades: " . $e->getMessage());
            return false;
        }
    }

    //Obtener precio del plan asociado al contrato
    public function obtenerPrecioPlanPorContrato($id_contrato)
    {
        $sql = "SELECT p.precio 
                FROM contratos_servicio c
                JOIN planes p ON c.id_plan = p.id_plan
                WHERE c.id_contrato = :id_contrato";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_contrato', $id_contrato);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ? $resultado['precio'] : 0;
    }

    // 🔹 Obtener cargo extra (mora) del contrato
    public function obtenerCargoExtraContrato($id_contrato)
    {
        $sql = "SELECT c.cargo_extra 
            FROM contratos_servicio c
            WHERE c.id_contrato = :id_contrato";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':id_contrato', $id_contrato);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ? $resultado['cargo_extra'] : 0;
    }


    // Aplicar mora y marcar mensualidades como vencidas después del período de gracia
    public function aplicarMoraYActualizarEstado()
    {
        try {
            $sql = "UPDATE mensualidades m
                    JOIN contratos_servicio c ON m.id_contrato = c.id_contrato
                    SET m.estado = 'vencido',
                        m.mora_aplicada = 1
                    WHERE m.estado = 'pendiente'
                      AND DATE_ADD(m.fecha_vencimiento, INTERVAL c.dias_mas DAY) < CURDATE()
                      AND m.mora_aplicada = 0";

            $stmt = $this->conn->conexion->prepare($sql);
            $stmt->execute();

            $afectadas = $stmt->rowCount();
            error_log("Se aplicó mora y estado vencido a $afectadas mensualidades.");
        } catch (PDOException $e) {
            error_log("Error al aplicar mora y actualizar estado: " . $e->getMessage());
        }
    }

    //Generar nueva mensualidad solo si el cliente tiene menos de 2 vencidas
    public function generarMensualidadSiguiente()
    {
        $estado = "pendiente";

        try {
            // PASO 1: Aplicar mora y actualizar estados vencidos
            $this->aplicarMoraYActualizarEstado();

            // PASO 2: Obtener contratos activos
            $sqlContratos = "SELECT id_contrato, fecha_contrato 
                             FROM contratos_servicio 
                             WHERE estado = 'activo'";
            $stmtContratos = $this->conn->conexion->prepare($sqlContratos);
            $stmtContratos->execute();
            $contratos = $stmtContratos->fetchAll(PDO::FETCH_ASSOC);

            foreach ($contratos as $contrato) {
                $id_contrato = $contrato['id_contrato'];
                $fecha_contrato = $contrato['fecha_contrato'];

                // Verificar cuántas mensualidades vencidas tiene
                $sqlVencidas = "SELECT COUNT(*) 
                                FROM mensualidades 
                                WHERE id_contrato = :id_contrato 
                                AND estado = 'vencido'";
                $stmtVencidas = $this->conn->conexion->prepare($sqlVencidas);
                $stmtVencidas->bindParam(':id_contrato', $id_contrato);
                $stmtVencidas->execute();
                $vencidas = $stmtVencidas->fetchColumn();

                // Saltar si hay 2 o más mensualidades vencidas
                if ($vencidas >= 2) {
                    continue;
                }

                // Obtener monto y mora del plan
                $monto = $this->obtenerPrecioPlanPorContrato($id_contrato);
                $cargo_extra = $this->obtenerCargoExtraContrato($id_contrato);

                // Calcular próxima fecha de vencimiento
                $sqlUltima = "SELECT fecha_vencimiento 
                              FROM mensualidades 
                              WHERE id_contrato = :id_contrato 
                              ORDER BY fecha_vencimiento DESC 
                              LIMIT 1";
                $stmtUltima = $this->conn->conexion->prepare($sqlUltima);
                $stmtUltima->bindParam(':id_contrato', $id_contrato);
                $stmtUltima->execute();
                $ultima = $stmtUltima->fetch(PDO::FETCH_ASSOC);

                $ultima_fecha = $ultima ? $ultima['fecha_vencimiento'] : $fecha_contrato;
                $proxima_fecha = date('Y-m-d', strtotime("+1 month", strtotime($ultima_fecha)));

                // Solo generar si la fecha de la próxima mensualidad ya llegó
                if ($proxima_fecha <= date('Y-m-d')) {
                    // Verificar si ya existe una mensualidad con esa fecha
                    $sqlExiste = "SELECT COUNT(*) 
                                  FROM mensualidades 
                                  WHERE id_contrato = :id_contrato 
                                  AND fecha_vencimiento = :fecha_vencimiento";
                    $stmtExiste = $this->conn->conexion->prepare($sqlExiste);
                    $stmtExiste->bindParam(':id_contrato', $id_contrato);
                    $stmtExiste->bindParam(':fecha_vencimiento', $proxima_fecha);
                    $stmtExiste->execute();

                    if ($stmtExiste->fetchColumn() == 0) {
                        // Insertar nueva mensualidad
                        $sqlInsert = "INSERT INTO mensualidades (
                                          id_contrato, monto, fecha_vencimiento, estado, 
                                          fecha_inicio, cargo_extra) 
                                     VALUES (:id_contrato, :monto, :fecha_vencimiento, 
                                             :estado, :fecha_inicio, :cargo_extra)";
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
}
