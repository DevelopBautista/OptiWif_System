<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../models/modelo_caja_diaria.php';

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
$caja = new modelo_caja_diaria();
$id_usuario = $_SESSION['id_user'] ?? 0;

header('Content-Type: application/json');

switch ($accion) {
    case 'abrir':
        $monto = $_POST['monto_inicial'] ?? null;
        if ($monto === null || !is_numeric($monto) || $monto < 0) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Monto inválido.']);
            exit;
        }

        if ($caja->existeCajaAbierta($id_usuario)) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Ya hay una caja abierta hoy.']);
            exit;
        }

        if ($caja->abrirCaja($monto, $id_usuario)) {
            echo json_encode(['status' => 'ok', 'mensaje' => 'Caja abierta correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo abrir la caja.']);
        }
        break;

    case 'obtener_total':
        $resultado = $caja->totalDelDia();
        $total = $resultado['total'] ?? 0;
        echo json_encode(['status' => 'ok', 'total' => number_format($total, 2, '.', '')]);
        break;

    case 'registrar':
        $total_real = floatval($_POST['total_contado'] ?? 0);
        $observaciones = htmlspecialchars(trim($_POST['observaciones'] ?? ''));

        if (!$caja->existeCajaAbierta($id_usuario)) {
            echo json_encode(['status' => 'error', 'mensaje' => 'No hay caja abierta para cerrar.']);
            exit;
        }

        $registrado = $caja->cerrarCaja($total_real, $observaciones, $id_usuario);

        if ($registrado) {
            $datosCierre = $caja->buscarCierrePorFecha(date('Y-m-d'));

            $diferencia = $datosCierre['diferencia'] ?? 0;
            $estado_caja = $diferencia > 0 ? 'Sobra dinero' : ($diferencia < 0 ? 'Falta dinero' : 'Caja cuadrada');

            echo json_encode([
                'status' => 'ok',
                'mensaje' => 'Cierre registrado y caja cerrada correctamente.',
                'total_sistema' => $datosCierre['total_sistema'] ?? 0,
                'total_real' => $datosCierre['total_real'] ?? 0,
                'diferencia' => $diferencia,
                'estado_caja' => $estado_caja
            ]);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar cierre']);
        }
        break;

    case 'verificar':
        $status = $caja->existeCajaAbierta($id_usuario);
        echo json_encode(['status' => 'ok', 'caja_abierta' => $status]);
        break;

    case 'buscar_cierre':
        $fecha = $_POST['fecha'] ?? null;
        if (!$fecha) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fecha no proporcionada']);
            exit;
        }
        $datosCierre = $caja->buscarCierrePorFecha($fecha);

        if ($datosCierre) {
            $diferencia = $datosCierre['diferencia'] ?? 0;
            $estado_caja = $diferencia > 0 ? 'Sobra dinero' : ($diferencia < 0 ? 'Falta dinero' : 'Caja cuadrada');
            $datosCierre['estado_caja'] = $estado_caja;

            // 👇 Agregar esta parte
            require_once '../../models/modelo_BuscarFecha_caja.php'; // Ajusta la ruta si es necesario
            $MC = new modelo_BuscarFecha_caja();
            $totalPagos = $MC->contador_pagos($fecha);
            $datosCierre['total_pagos'] = $totalPagos['total_pagos'] ?? 0;

            echo json_encode(['exito' => true, 'datos' => $datosCierre]);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'No se encontró cierre para esa fecha']);
        }
        break;
    case 'verificar_reporte':
        $fecha = $_GET['fecha'] ?? null;

        if (!$fecha) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fecha no válida.']);
            exit;
        }

        $datos = $caja->buscarCierrePorFecha($fecha);
        if ($datos) {
            echo json_encode(['exito' => true]);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'No hay datos para esa fecha.']);
        }
        break;


    default:
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
        break;
}
