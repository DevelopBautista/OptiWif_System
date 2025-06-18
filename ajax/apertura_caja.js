function registrarApertura() {
    const monto = $('#monto_inicial').val();

    if (!monto || isNaN(monto) || parseFloat(monto) < 0) {
        return alert("Ingrese un monto válido.");
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
                alert(resp.mensaje);
                $('#monto_inicial').val('');
            } else {
                alert("Error: " + resp.mensaje);
            }
        },
        error: function () {
            alert("Error en el servidor.");
        }
    });
}
