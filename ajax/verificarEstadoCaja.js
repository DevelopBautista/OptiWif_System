function verificarEstadoCaja() {
    $.ajax({
        url: '../controllers/caja/controlador_caja.php',
        type: 'POST',
        data: { accion: 'verificar' },
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta.status === 'ok' && respuesta.caja_abierta) {
                $('#btn_abrir_caja').prop('disabled', true);
                $('#btn_cerrar_caja').prop('disabled', false);
            } else {
                $('#btn_abrir_caja').prop('disabled', false);
                $('#btn_cerrar_caja').prop('disabled', true);
            }
        },
        error: function () {
            // Opcional: manejar error aquí
            console.error('Error verificando estado de caja');
        }
    });
}
