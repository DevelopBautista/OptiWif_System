function verificarEstadoCaja() {
    $.ajax({
        url: '../controllers/caja/controlador_verificar_caja_abierta.php',
        type: 'GET',
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta.caja_abierta) {
                $('#btn_abrir_caja').prop('disabled', true);
                $('#btn_cerrar_caja').prop('disabled', false);
            } else {
                $('#btn_abrir_caja').prop('disabled', false);
                $('#btn_cerrar_caja').prop('disabled', true);
            }
        }
    });
}
