<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../models/modelo_cierre_caja.php';

$accion = $_POST['accion'] ?? '';

$cierre = new modelo_cierre_caja();

switch ($accion) {
    case 'obtener_total':
        $resultado = $cierre->totalDelDia();
        $total = $resultado['total'] ?? 0;
        echo json_encode(['status' => 'ok', 'total' => number_format($total, 2, '.', '')]);
        break;

    case 'registrar':
        $esperado = $cierre->totalDelDia()['total'] ?? 0;
        $contado = floatval($_POST['total_contado']);
        $diferencia = $contado - $esperado;
        $observaciones = htmlspecialchars(trim($_POST['observaciones'] ?? ''));
        $usuario = $_SESSION['id_user'] ?? 0;


        $data = [
            'fecha' => date('Y-m-d'),
            'esperado' => $esperado,
            'contado' => $contado,
            'diferencia' => $diferencia,
            'obs' => $observaciones,
            'usuario' => $usuario
        ];

        $registrado = $cierre->registrarCierre($data);

        if ($registrado) {
            // Aquí actualizamos el estado de la caja abierta
            $cierre->cerrarCajaAbierta($usuario);
            $cierre->marcarPagosComoCerrados();
            echo json_encode(['status' => 'ok', 'mensaje' => 'Cierre registrado y caja cerrada correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar cierre']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
        break;
}
