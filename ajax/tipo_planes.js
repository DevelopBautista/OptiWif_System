
//listar planes
function tipo_plan() {
    $.ajax({
        url: "../controllers/plan/controlador_listar_cmbPlanes.php",
        type: "POST"
    }).done(function (resp) {
        var data = JSON.parse(resp);
        var cadena = "<option value=''>Seleccione Un Plan</option>"; //Placeholder;
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += "<option value='" + data[i][0] + "'>" + data[i][1] + "</option>";
            }

        } else {
            cadena += "<option value=''>No se encontraron registros</option>";

        }
        $("#cmb_planes").html(cadena);


    });
}




