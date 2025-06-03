<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Admin@132081');
define('DB_NAME', 'sistema_wisper');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die(json_encode([
        "status" => "error",
        "mensaje" => "Error de conexión: " . $mysqli->connect_error
    ]));
}

$hoy = date('Y-m-d');
$mes = date('n');
$anio = date('Y');

$sql = "SELECT cs.id_contrato, cs.id_plan FROM contratos_servicio cs WHERE cs.estado = 'Activo'";
$result = $mysqli->query($sql);
$generadas = 0;

if ($result && $result->num_rows > 0) {
    while ($contrato = $result->fetch_assoc()) {
        $id_contrato = $contrato['id_contrato'];
        $id_plan = $contrato['id_plan'];

        $check = $mysqli->prepare("SELECT COUNT(*) AS total FROM mensualidades WHERE id_contrato = ? AND periodo_mes = ? AND periodo_anio = ?");
        $check->bind_param("iii", $id_contrato, $mes, $anio);
        $check->execute();
        $check_result = $check->get_result()->fetch_assoc();

        if ($check_result['total'] == 0) {
            $plan_query = $mysqli->prepare("SELECT precio FROM planes WHERE id_plan = ?");
            $plan_query->bind_param("i", $id_plan);
            $plan_query->execute();
            $plan_data = $plan_query->get_result()->fetch_assoc();
            $monto = $plan_data['precio'];

            $fecha_vencimiento = date('Y-m-25', strtotime('+1 month'));

            $insert = $mysqli->prepare("INSERT INTO mensualidades (id_contrato, periodo_mes, periodo_anio, monto, fecha_vencimiento, estado, fecha_generada) VALUES (?, ?, ?, ?, ?, 'pendiente', ?)");
            $insert->bind_param("iiidss", $id_contrato, $mes, $anio, $monto, $fecha_vencimiento, $hoy);
            $insert->execute();
            $generadas++;
        }
    }

    echo json_encode([
        "status" => "ok",
        "mensaje" => "Mensualidades generadas correctamente.",
        "total_generadas" => $generadas
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "ok",
        "mensaje" => "No hay contratos activos.",
        "total_generadas" => 0
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

$mysqli->close();
