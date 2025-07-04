function registrarApertura() {
    const monto = $('#monto_inicial').val();

    if (!monto || isNaN(monto) || parseFloat(monto) < 0) {
        Swal.fire("Advertencia", "Ingrese un monto válido.", "warning");
        return;
    }

    $.ajax({
        url: '../controllers/caja/controlador_caja.php', // controlador unificado
        type: 'POST',
        data: {
            accion: 'abrir',       // lo manejamos en el controlador
            monto_inicial: monto
        },
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'ok') {
                Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                    $('#monto_inicial').val('');
                    verificarEstadoCaja(); // refresca estado de caja
                });
            } else {
                Swal.fire("Error", resp.mensaje, "error");
            }
        },
        error: function () {
            Swal.fire("Error", "Error en el servidor.", "error");
        }
    });
}
