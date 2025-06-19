function obtenerTotalSistema() {
    $.ajax({
        url: '../controllers/caja/controlador_cierre_caja.php',
        type: 'POST',
        data: { accion: 'obtener_total' },
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'ok') {
                $('#total_sistema').text(resp.total);
            } else {
                Swal.fire("Advertencia", "No se pudo obtener el total del día.", "warning");
            }
        },
        error: function () {
            Swal.fire("Error", "Error al consultar el total del día.", "error");
        }
    });
}

function registrarCierre() {
    const total_contado = $('#total_contado').val();
    const observaciones = $('#observaciones').val();

    if (!total_contado || isNaN(total_contado)) {
        Swal.fire("Advertencia", "Debe ingresar el total contado correctamente.", "warning");
        return;
    }

    $.ajax({
        url: '../controllers/caja/controlador_cierre_caja.php',
        type: 'POST',
        data: {
            accion: 'registrar',
            total_contado: total_contado,
            observaciones: observaciones
        },
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'ok') {
                Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                    $('#total_contado').val('');
                    $('#observaciones').val('');
                    obtenerTotalSistema();
                    verificarEstadoCaja(); // Desactiva boton de cierre y activa apertura

                });
            } else {
                Swal.fire("Error", resp.mensaje, "error");
            }
        },
        error: function () {
            Swal.fire("Error", "Error al registrar el cierre.", "error");
        }
    });
}
