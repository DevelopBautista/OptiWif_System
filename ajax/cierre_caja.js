function obtenerTotalSistema() {
    $.ajax({
        url: '../controllers/caja/controlador_caja.php',
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
        url: '../controllers/caja/controlador_caja.php',
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
                    verificarEstadoCaja();
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
    var fecha = $('#fecha_busqueda').val();

    if (!fecha) {
        return Swal.fire("Advertencia", "Debe seleccionar una fecha.", "warning");
    }

    $.ajax({
        url: '../controllers/caja/controlador_caja.php',
        type: 'POST',
        data: { accion: 'buscar_cierre', fecha: fecha },
        dataType: 'json',
        success: function (resp) {
            if (resp.exito && resp.datos) {
                const datos = resp.datos;




                let montoInicial = parseFloat(datos.monto_apertura);
                let montoFinal = parseFloat(datos.total_sistema);
                let total_movimientos = parseFloat(datos.total_cerrados || 0);
                let total_real = parseFloat(datos.total_real || 0);

                montoInicial = isNaN(montoInicial) ? 0 : montoInicial;
                montoFinal = isNaN(montoFinal) ? 0 : montoFinal;
                total_movimientos = isNaN(total_movimientos) ? 0 : total_movimientos;
                total_real = isNaN(total_real) ? 0 : total_real;
                console.log(total_real);
                console.log(total_movimientos);
                console.log(montoInicial);
                console.log(montoFinal);
                let diferencia = (total_real + montoInicial) - montoFinal;

                let estadoCaja = datos.estado_caja || '';
                let color = 'black';
                let icono = '';

                // Define estadoCaja según diferencia
                if (Math.abs(diferencia) < 0.01) {
                    estadoCaja = 'Caja cuadrada';
                } else if (diferencia < 0) {
                    estadoCaja = 'Falta dinero';
                } else {
                    estadoCaja = 'Sobra dinero';
                }

                if (estadoCaja === 'Caja cuadrada') {
                    color = 'green';
                    icono = '✅';
                } else if (estadoCaja === 'Falta dinero') {
                    color = 'red';
                    icono = '⚠️';
                } else if (estadoCaja === 'Sobra dinero') {
                    color = 'blue';
                    icono = '💰';
                }

                let diferenciaTexto = `
                    <p style="color: ${color};">
                        <strong>${icono} ${estadoCaja}`;

                if (estadoCaja !== 'Caja cuadrada') {
                    diferenciaTexto += `: RD$ ${Math.abs(diferencia).toFixed(2)}`;
                }

                diferenciaTexto += `</strong></p>`;

                let mensaje = `
                    <div style="border: 1px solid #ccc; border-radius: 10px; padding: 15px; background-color: #f9f9f9; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6;">
                        <p><strong>👤 Usuario:</strong> ${datos.nombre_usuario}</p>
                        <p><strong>💰 Monto Inicial:</strong> RD$ ${montoInicial.toFixed(2)}</p>
                        <p><strong>📥 Total de pagos procesados:</strong> RD$ ${total_movimientos.toFixed(2)}</p>
                        <p><strong>💵 Monto contado en caja:</strong> RD$ ${total_real.toFixed(2)}</p>
                        <p><strong>👥 Clientes que realizaron pagos: </strong>${datos.total_pagos}</p>
                        <p><strong>🧮 Monto esperado (sistema):</strong> RD$ ${montoFinal.toFixed(2)}</p>
                        <p><strong>📅 Fecha de Cierre:</strong> ${datos.fecha_cierre ?? '---'}</p>
                        ${diferenciaTexto}
                    </div>
                `;

                Swal.fire({
                    title: "📋 Cierre encontrado",
                    html: mensaje,
                    icon: "success"
                });
            } else {
                Swal.fire("Sin resultados", resp.mensaje || "No se encontró información para esa fecha.", "info");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo buscar el cierre.", "error");
        }
    });
}



function generarReporte() {
    var fecha = $('#fecha_busqueda').val();

    if (!fecha) {
        return Swal.fire("Advertencia", "Debe seleccionar una fecha.", "warning");
    }

    $.ajax({
        url: '../controllers/caja/controlador_caja.php',
        type: 'GET',
        data: { accion: 'verificar_reporte', fecha: fecha },
        dataType: 'json',
        success: function (resp) {
            if (resp.exito) {
                window.open('../views/libreporte/reports/reportes/reportes.php?fecha=' + encodeURIComponent(fecha), '_blank');
            } else {
                Swal.fire("Sin datos", "No se encontró información para la fecha " + fecha, "warning");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo verificar el reporte.", "error");
        }
    });
}
