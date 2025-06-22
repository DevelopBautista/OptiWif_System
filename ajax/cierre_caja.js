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


function buscarCierrePorFecha() {
    var fecha = document.getElementById('fecha_busqueda').value;

    if (!fecha) {
        return Swal.fire("Advertencia", "Debe seleccionar una fecha.", "warning");
    }

    $.ajax({
        url: '../controllers/caja/controlador_buscar_cierre_fecha.php',
        type: 'POST',
        data: { fecha: fecha },
        dataType: 'json',
        success: function (resp) {
            if (resp.exito && resp.datos) {
                const datos = resp.datos;
                let montoInicial = parseFloat(datos.monto_inicial);
                let montoFinal = parseFloat(datos.total_caja);
                let total_movimientos = parseFloat(datos.total_movimientos);

                montoInicial = isNaN(montoInicial) ? 0 : montoInicial;
                montoFinal = isNaN(montoFinal) ? 0 : montoFinal;

                let mensaje = `<b>Usuario:</b> ${datos.nombre_usuario} <br>
                               <b>Monto Inicial:</b> RD$ ${montoInicial.toFixed(2)} <br>
                               <b>Total de pagos ralizados:</b> RD$ ${total_movimientos.toFixed(2)} <br>
                               <b>Monto Final:</b> RD$ ${montoFinal.toFixed(2)} <br>
                               <b>Fecha de Cierre:</b> ${datos.fecha_cierre ?? '---'}`;

                Swal.fire({
                    title: "Cierre encontrado",
                    html: mensaje,
                    icon: "success"
                });
            } else {
                Swal.fire("Sin resultados", resp.mensaje, "info");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo buscar el cierre.", "error");
        }
    });

}


function generarReporte() {
    var fecha = document.getElementById('fecha_busqueda').value;
    if (!fecha) {
        return Swal.fire("Advertencia", "Debe seleccionar una fecha.", "warning");
    }

    window.open('../views/libreporte/reports/reportes/reportes.php?fecha=' + encodeURIComponent(fecha), '_blank');
}

