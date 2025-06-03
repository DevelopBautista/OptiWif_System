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


    public function generar_Mensualidades()
    {
        $hoy = date('Y-m-d');
        $mes = date('n'); // mes actual (1-12)
        $anio = date('Y');

        // 1. Obtener contratos activos
        $sql = "SELECT cs.id_contrato, cs.id_plan 
                FROM contratos_servicio cs 
                WHERE cs.estado = 'Activo'";
        $stmt = $this->conn->conexion->prepare($sql);

        while ($contrato = $stmt->fetch_assoc()) {
            $id_contrato = $contrato['id_contrato'];
            $id_plan = $contrato['id_plan'];

            // 2. Verificar si ya existe una mensualidad de este mes para ese contrato
            $check = $this->conn->conexion->prepare("SELECT COUNT(*) AS total FROM mensualidades WHERE id_contrato = ? AND periodo_mes = ? AND periodo_anio = ?");
            $check->bind_param("iii", $id_contrato, $mes, $anio);
            $check->execute();
            $check_result = $check->get_result()->fetch_assoc();

            if ($check_result['total'] == 0) {
                // 3. Obtener precio del plan
                $plan_query = $this->conn->conexion->prepare("SELECT precio FROM planes WHERE id_plan = ?");
                $plan_query->bind_param("i", $id_plan);
                $plan_query->execute();
                $plan_data = $plan_query->get_result()->fetch_assoc();
                $monto = $plan_data['precio'];

                // 4. Calcular fecha de vencimiento (ej: 10 del mes siguiente)
                $fecha_vencimiento = date('Y-m-25', strtotime('+1 month'));

                // 5. Insertar mensualidad
                $insert = $this->conn->conexion->prepare("INSERT INTO mensualidades (id_contrato, periodo_mes, periodo_anio, monto, fecha_vencimiento, estado, fecha_generada) VALUES (?, ?, ?, ?, ?, 'pendiente', ?)");
                $insert->bind_param("iiidss", $id_contrato, $mes, $anio, $monto, $fecha_vencimiento, $hoy);
                $insert->execute();
            }
        }

        return "Mensualidades generadas correctamente.";
    }
}
