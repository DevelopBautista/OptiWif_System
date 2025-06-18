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
                alert('No se pudo obtener el total del día.');
            }
        },
        error: function () {
            alert('Error al consultar el total del día.');
        }
    });
}

function registrarCierre() {
    const total_contado = $('#total_contado').val();
    const observaciones = $('#observaciones').val();

    if (!total_contado || isNaN(total_contado)) {
        return alert("Debe ingresar el total contado correctamente.");
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
                alert(resp.mensaje);
                $('#total_contado').val('');
                $('#observaciones').val('');
                obtenerTotalSistema();
            } else {
                alert("Error: " + resp.mensaje);
            }
        },
        error: function () {
            alert("Error al registrar cierre.");
        }
    });
}

