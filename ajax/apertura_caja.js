function registrarApertura() {
    const monto = $('#monto_inicial').val();

    if (!monto || isNaN(monto) || parseFloat(monto) < 0) {
        Swal.fire("Advertencia", "Ingrese un monto válido.", "warning");
        return;
    }

    $.ajax({
        url: '../controllers/caja/controlador_apertura_caja.php',
        type: 'POST',
        data: {
            accion: 'abrir',
            monto_inicial: monto
        },
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'ok') {
                Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                    $('#monto_inicial').val('');
                    verificarEstadoCaja(); // Actualiza el estado de los botones
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
