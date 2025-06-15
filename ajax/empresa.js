function ingresar_datos_empresa() {
    var nombre_empresa = $('#nombre_empresa').val();
    var direccion = $('#direccion').val();
    var tel = $('#tel').val();
    var rnc = $('#rnc').val();
    var logo = $('#logo')[0].files[0];

    if (!nombre_empresa || !direccion || !tel || !rnc || !logo) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    // Crear FormData para enviar archivo
    var formData = new FormData();
    formData.append("nombre_empresa", nombre_empresa);
    formData.append("direccion", direccion);
    formData.append("rnc", rnc);
    formData.append("tel", tel);
    formData.append("logo", logo);

    $.ajax({
        url: "../controllers/empresa/controlador_insert_empresa.php",
        type: "POST",
        data: formData,
        contentType: false, // Importante para enviar archivos
        processData: false, // Importante para evitar conversión automática
        dataType: "json"
    }).done(function (resp) {
        console.log("Respuesta del servidor:", resp);
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success");
            $('#frm')[0].reset();
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    }).fail(function (jqXHR, textStatus) {
        console.error("AJAX error:", textStatus);
        console.error("Respuesta del servidor:", jqXHR.responseText);
        Swal.fire("Error", "Error de servidor: " + textStatus, "error");
    });
}