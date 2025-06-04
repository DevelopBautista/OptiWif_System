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
        $stmt->execute();

        while ($contrato = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_contrato = $contrato['id_contrato'];
            $id_plan = $contrato['id_plan'];

            // 2. Verificar si ya existe una mensualidad
            $check = $this->conn->conexion->prepare("SELECT COUNT(*) AS total FROM mensualidades WHERE id_contrato = ? AND periodo_mes = ? AND periodo_anio = ?");
            $check->execute([$id_contrato, $mes, $anio]);
            $check_result = $check->fetch(PDO::FETCH_ASSOC);

            if ($check_result['total'] == 0) {
                // 3. Obtener precio del plan
                $plan_query = $this->conn->conexion->prepare("SELECT precio FROM planes WHERE id_plan = ?");
                $plan_query->execute([$id_plan]);
                $plan_data = $plan_query->fetch(PDO::FETCH_ASSOC);
                $monto = $plan_data['precio'];

                // 4. Calcular fecha de vencimiento
                $fecha_vencimiento = date('Y-m-25', strtotime('+1 month'));

                // 5. Insertar mensualidad
                $insert = $this->conn->conexion->prepare("INSERT INTO mensualidades (id_contrato, periodo_mes, periodo_anio, monto, fecha_vencimiento, estado, fecha_generada) VALUES (?, ?, ?, ?, ?, 'pendiente', ?)");
                $insert->execute([$id_contrato, $mes, $anio, $monto, $fecha_vencimiento, $hoy]);
            }
        }

        return "Mensualidades generadas correctamente.";
    }
}
