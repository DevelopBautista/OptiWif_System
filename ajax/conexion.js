//listar tipo de conexion cliente
function tipo_conexion() {
    $.ajax({
        url: "../controllers/tipoConexion/controlador_tipo_conexion.php",
        type: "POST"
    }).done(function (resp) {
        var data = JSON.parse(resp);
        var cadena = "";
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += "<option value='" + data[i][0] + "'>" + data[i][1] + "</option>";
            }

        } else {
            cadena += "<option value=''>No se encontraron registros</option>";

        }
        $("#cmb_conexion").html(cadena);

    });

}
