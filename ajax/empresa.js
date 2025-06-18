function ingresar_datos_empresa() {
    var nombre_empresa = $('#nombre_empresa').val();
    var direccion = $('#direccion').val();
    var tel = $('#tel').val();
    var rnc = $('#rnc').val();
    var logo = $('#logo')[0].files[0];

    if (!nombre_empresa || !direccion || !tel || !rnc) {
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

//funcion para obtener los datos y enviarlo a los inputs
function get_datos_empresa() {
    $.ajax({
        url: "../controllers/empresa/controlador_get_empresa.php",
        type: "GET",
        dataType: "json"
    }).done(function (resp) {
        if (resp.existe) {
            const empresa = resp.empresa;
            $("#modal_editar_empresa #id_empresa").val(empresa.id_empresa);
            $("#modal_editar_empresa #nombre_empresa").val(empresa.nombre);
            $("#modal_editar_empresa #direccion").val(empresa.direccion);
            $("#modal_editar_empresa #tel").val(empresa.telefono);
            $("#modal_editar_empresa #rnc").val(empresa.rnc);

            // Mostrar el logo actual si existe
            if (empresa.logo) {
                $("#preview_logo_edit")
                    .attr("src", "../views/logos/" + empresa.logo)
                    .show();
            } else {
                $("#preview_logo_edit").hide();
            }

            // Mostrar el modal
            $("#modal_editar_empresa").modal("show");
        } else {
            Swal.fire("Aviso", "No hay empresa registrada aún.", "info");
        }
    }).fail(function () {
        Swal.fire("Error", "No se pudo obtener la información de la empresa.", "error");
    });
}

//funcion actualizar los datos en la db
function update_Empresa() {
    var id_empresa = $("#modal_editar_empresa #id_empresa").val();
    var direccion = $("#modal_editar_empresa #direccion").val();
    var tel = $("#modal_editar_empresa #tel").val();
    var logo = $('#modal_editar_empresa #logo_edit')[0].files[0]; // archivo logo nuevo (opcional)

    if (!direccion || !tel) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    var formData = new FormData();
    formData.append("id_empresa", id_empresa);
    formData.append("direccion", direccion);
    formData.append("tel", tel);
    if (logo) formData.append("logo", logo); // solo si el usuario subió uno nuevo

    $.ajax({
        url: "../controllers/empresa/controlador_actualizar_empresa.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json"
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire({
                title: "mensaje de confirmación",
                text: "Exito  " + resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                didClose: () => {
                    $("#modal_editar_empresa").modal("hide");
                    back_to_dashbaord();
                }
            });
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    }).fail(function () {
        Swal.fire("Error", "Error de servidor.", "error");
    });
}
