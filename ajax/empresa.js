function ingresar_datos_empresa() {
    var nombre_empresa = $('#nombre_empresa').val();
    var direccion = $('#direccion').val();
    var tel = $('#tel').val();
    var rnc = $('#rnc').val();
    var logo = $('#logo')[0].files[0];

    if (!nombre_empresa || !direccion || !tel || !rnc) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

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
        contentType: false,
        processData: false,
        dataType: "json"
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                location.reload(); // recarga para aplicar cambios visuales
            });
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    }).fail(function (jqXHR, textStatus) {
        console.error("AJAX error:", textStatus);
        Swal.fire("Error", "Error de servidor: " + textStatus, "error");
    });
}

// Detecta si hay una empresa registrada y controla los botones
function get_datos_empresa() {
    $.ajax({
        url: "../controllers/empresa/controlador_get_empresa.php",
        type: "GET",
        dataType: "json"
    }).done(function (resp) {
        controlarBotonesEmpresa(resp.existe);//para ocultar O mostrar los btns

        if (resp.existe) {
            const empresa = resp.empresa;
            $("#modal_editar_empresa #id_empresa").val(empresa.id_empresa);
            $("#modal_editar_empresa #nombre_empresa").val(empresa.nombre);
            $("#modal_editar_empresa #direccion").val(empresa.direccion);
            $("#modal_editar_empresa #tel").val(empresa.telefono);
            $("#modal_editar_empresa #rnc").val(empresa.rnc);

            if (empresa.logo) {
                $("#preview_logo_edit")
                    .attr("src", "../views/logos/" + empresa.logo)
                    .show();
            } else {
                $("#preview_logo_edit").hide();
            }

            // Mostrar modal
            $("#modal_editar_empresa").modal("show");
            Swal.fire("Aviso", "No hay empresa registrada aún.", "info");

        }
    }).fail(function () {
        Swal.fire("Error", "No se pudo obtener la información de la empresa.", "error");
    });
}

// Actualizar datos
function update_Empresa() {
    var id_empresa = $("#modal_editar_empresa #id_empresa").val();
    var direccion = $("#modal_editar_empresa #direccion").val();
    var tel = $("#modal_editar_empresa #tel").val();
    var logo = $('#modal_editar_empresa #logo_edit')[0].files[0];

    if (!direccion || !tel) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    var formData = new FormData();
    formData.append("id_empresa", id_empresa);
    formData.append("direccion", direccion);
    formData.append("tel", tel);
    if (logo) formData.append("logo", logo);

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
                title: "Mensaje de confirmación",
                text: "Éxito: " + resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                didClose: () => {
                    $("#modal_editar_empresa").modal("hide");
                    location.reload();
                }
            });
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    }).fail(function () {
        Swal.fire("Error", "Error de servidor.", "error");
    });
}



function controlarBotonesEmpresa(existeEmpresa) {
    if (existeEmpresa) {
        $("#btn_registar").hide();
        $("#btn_editar").show();
    } else {
        Swal.fire("Advertencia", "No hay ninguna empresa registrada actualmente.", "warning");
        $("#btn_registar").show();
        $("#btn_editar").hide();
    }
}