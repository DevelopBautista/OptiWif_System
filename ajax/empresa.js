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
        console.log(resp);

        if (resp.existe) {
            const empresa = resp.empresa;

            // Rellenar campos
            $("#id_empresa").val(empresa.id_empresa);
            $("#nombre_empresa").val(empresa.nombre);
            $("#direccion").val(empresa.direccion);
            $("#tel").val(empresa.telefono);
            $("#rnc").val(empresa.rnc);

            if (empresa.logo) {
                $("#preview_logo_edit").attr("src", "../views/logos/" + empresa.logo).show();
            } else {
                $("#preview_logo_edit").hide();
            }

            // Cambiar título
            $("#titulo_form").text("Editar Empresa ISP");

            // Mostrar botón editar y ocultar botón registrar
            $("#btn_editar").show();
            $("#btn_registar").hide();
        } else {
            // No existe empresa, limpiar campos
            $("#frm")[0].reset();
            $("#id_empresa").val("");
            $("#preview_logo_edit").hide();

            // Cambiar título
            $("#titulo_form").text("Registro de Empresa ISP");

            // Mostrar botón registrar y ocultar botón editar
            $("#btn_editar").hide();
            $("#btn_registar").show();
        }

    }).fail(function () {
        Swal.fire("Error", "No se pudo obtener la información de la empresa.", "error");
    });
}


// Actualizar datos
function update_Empresa() {
    var id_empresa = $("#id_empresa").val();
    var nombre_empresa = $("#nombre_empresa").val();
    var direccion = $("#direccion").val();
    var tel = $("#tel").val();

    // Buscar el input de logo (puede ser 'logo' o 'logo_edit')
    var logoInput = document.getElementById('logo') || document.getElementById('logo_edit');
    var logo = (logoInput && logoInput.files.length > 0) ? logoInput.files[0] : null;

    if (!direccion || !tel) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    var formData = new FormData();
    formData.append("nombre_empresa", nombre_empresa);
    formData.append("id_empresa", id_empresa);
    formData.append("direccion", direccion);
    formData.append("tel", tel);

    if (logo) {
        formData.append("logo", logo);
    }

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