//listar tipo_servio
function tipo_servicio() {
    $.ajax({
        url: "../controllers/servicio/controlador_listar_Tipo_servicio_cmb.php",
        type: "POST"
    }).done(function (resp) {
        var data = JSON.parse(resp);
        var cadena = "<option value=''>Seleccione Un Servicio</option>"; //Placeholder;
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += "<option value='" + data[i][0] + "'>" + data[i][1] + "</option>";
            }

        } else {
            cadena += "<option value=''>No se encontraron registros</option>";

        }

        $("#cmb_servicio").html(cadena);


    });
}
