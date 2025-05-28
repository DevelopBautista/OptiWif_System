function verificar_usuario() {
    var user = $("#txt_usu").val().trim();
    var pass = $("#txt_con").val().trim();

    if (user.length === 0 || pass.length === 0) {
        return Swal.fire("Mensaje de advertencia", "Debe ingresar datos en los campos.", "warning");
    }

    $.ajax({
        url: "../../controllers/usuario/controlador_verificar_usuario.php",
        type: "POST",
        data: {
            user: user,
            pass: pass
        }
    })
        .done(function (resp) {
            if (resp == 0) {
                Swal.fire("Acceso Denegado", "Usuario o contraseña incorrectos.", "error");
            } else {
                var data = JSON.parse(resp);
                if (data[0][7] === 'inactivo') {
                    return Swal.fire("Acceso Denegado", "Lo sentimos el  usuario  " + user + " se encuentra suspendido comuniquese con el Admin",
                        "warning");
                }
                $.ajax({
                    url: "../../controllers/usuario/controlador_crear_session.php",
                    type: "POST",
                    data: {
                        id_user: data[0][0],
                        user: data[0][1],
                        rol: data[0][6]
                    }
                }).done(function (resp) {
                    let timerInterval;
                    Swal.fire({
                        title: "Bienvenido a OptiWiF System",
                        html: "Usted esta siento redireccionado...",
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                })

            }
        });
}



function cerrar_sesion() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Tu sesión se cerrará.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cerrar sesión",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Sesión cerrada",
                text: "Has cerrado sesión correctamente.",
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '../controllers/usuario/controlador_cerrar_session.php';
                console.log(window.location.href);
            });
        }
    });
}



//dataTables
var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sSearch": "Buscar:",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    }
};
//variable para luego ser usada en otros lugares
var tabla;
//---------------------------------------
function listar_usuario() {
    tabla = $('#tabla_usuarios').DataTable({
        "ajax": {
            "url": "../controllers/usuario/controlador_usuario_listar.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_usuario", "visible": false },
            { "data": "usuario_nombres" },
            { "data": "usuario_tel" },
            { "data": "rol_nombre" },
            {
                "data": "usuario_estatus",
                "render": function (data) {
                    if (data === "activo") {
                        return "<span class='label label-success'>" + data + "</span>";
                    } else if (data === "inactivo") {
                        return "<span class='label label-danger'>" + data + "</span>";
                    }
                }
            },
            {
                "defaultContent": "<button class='btn btn-info btn-sm'><i class='fa-solid fa-eye'></i></button>&nbsp;<button  class=' btn btn-warning btn-sm'><i class='fa-solid fa-edit'></i></button>&nbsp;<button class='btn btn-success btn-sm'><i class='fa-solid fa-check'></i></button>&nbsp;<button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });
    //eliminar usuario
    $('#tabla_usuarios ').on('click', '.btn-danger', function () {
        var data = tabla.row($(this).parents('tr')).data();
        var id = data.id_usuario;
        eliminar_usuario(id);
    });

    //ver datos del usuario
    $('#tabla_usuarios ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();//obteniendo toda la data de la fila 
        //almacacenando la data de la fila por campos
        var id = data.id_usuario;
        var nom = data.usuario_nombres;
        var ced = data.usuario_ced;
        var tel = data.usuario_tel;
        var dir = data.usuario_direccion;
        var usu = data.usuario_usu;
        var rol = data.rol_nombre;
        var id_rol = data.id_rol;
        var estatus = data.usuario_estatus;
        ver_datos_usuario(nom, ced, tel, dir, usu, rol, estatus, id_rol);
    });

    //cambiar estatus del usuario
    $('#tabla_usuarios ').on('click', '.btn-success', function () {
        var data = tabla.row($(this).parents('tr')).data();
        var id = data.id_usuario;
        var estatus = data.usuario_estatus;

        if (estatus === "activo") {
            estatus = "inactivo"
        } else {
            estatus = "activo"
        }
        cambiar_estatus_usuario(id, estatus);

    });

    // actualziar datos del usuario
    $('#tabla_usuarios ').on('click', '.btn-warning', function () {
        var data = tabla.row($(this).parents('tr')).data();

        var id = data.id_usuario;
        var nombre = data.usuario_nombres;
        var dire = data.usuario_direccion;
        var tel = data.usuario_tel;
        var usuario = data.usuario_usu;
        var rol = data.id_rol;
        var ced = data.usuario_ced;

        actualizar_datos_usuario(id, nombre, dire, tel, usuario, rol, ced);

    });



    // Listener para botón de editar en DataTable
    $('#tabla_usuarios').on('click', '.btn-warning', function () {
        const data = tabla.row($(this).parents('tr')).data();

        if (!data) {
            console.error("No se pudo obtener los datos de la fila.");
            return;
        }

        const id = data.id_usuario;
        const nom = data.usuario_nombres;
        const ced = data.usuario_ced;
        const tel = data.usuario_tel;
        const dir = data.usuario_direccion;
        const usu = data.usuario_usu;
        const rol = data.id_rol;
        const pswd = ""; // No enviar el hash, dejar campo vacío
    });

}

function listar_roles() {
    $.ajax({
        url: "../controllers/usuario/controlador_listar_roles.php",
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
        $("#cmb_rol").html(cadena);
        $("#cmb_rol_up").html(cadena);


    });
}

function ingresar_usuario() {
    var nombres = $('#nombres').val();
    var dir = $('#dir').val();
    var tel = $('#tel').val();
    var user = $('#user').val();
    var rol = $('#cmb_rol').val();
    var pswd = $('#pswd').val();
    var pswd2 = $('#pswd2').val();
    var ced = $('#ced').val();

    if (!nombres || !dir || !tel || !user || !rol || !pswd || !pswd2 || !ced) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    if (pswd !== pswd2) {
        return Swal.fire("Mensaje de advertencia", "Las contraseñas no coinciden. Deben coincidir", "warning");
    }

    $.ajax({
        url: "../controllers/usuario/controlador_insert_usuario.php",
        type: "POST",
        dataType: "json",
        data: {
            nombres: nombres,
            dir: dir,
            tel: tel,
            user: user,
            id_rol: rol,
            pswd: pswd,
            ced: ced
        }
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                document.getElementById('frm').reset();
                if (typeof table !== "undefined") {
                    table.ajax.reload();
                }
            });
        } else if (resp.status === "existe") {
            Swal.fire("Advertencia", resp.mensaje, "warning");
        } else {
            Swal.fire("Error", resp.mensaje || "No se pudo realizar el registro.", "error");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire("Error", "Error de servidor: " + textStatus, "error");
        console.error("AJAX error:", errorThrown);
    });
}

function ver_datos_usuario(nom, ced, tel, dir, user, rol, estatus) {
    $('#modal_showData').modal('show');
    //Pasandole los datos al modal_showData
    $("#nom_show").val(nom);
    $("#ced_show").val(ced);
    $("#tel_show").val(tel);
    $("#dir_show").val(dir);
    $("#usu_show").val(user);
    $("#rol_show").val(rol);
    $("#estatus_show").val(estatus);
}

function eliminar_usuario(id) {
    Swal.fire({
        title: "¿Esta seguro de eliminar a este usuario?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminalo!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../controllers/usuario/controlador_eliminar_usuario.php",
                type: "POST",
                data: {
                    id_usuario: id
                }
            }).done(function (resp) {
                if (resp === 'true') {
                    Swal.fire("Eliminado", "El usuario fue eliminado correctamente.", "success");
                    tabla.ajax.reload();
                } else {
                    Swal.fire("Advertencia", "No se pudo eliminar el usuario", "warning");
                }
            }).fail(function () {
                Swal.fire("Error", "Error en el servidor o en la conexión", "error");

            });
        }
    })
}

function cambiar_estatus_usuario(id, estatus) {
    var advertencia = "";
    $.ajax({
        url: "../controllers/usuario/controlador_cambiarEstatus_usuario.php",
        type: "POST",
        dataType: "json",
        data: {
            id_usuario: id,
            estatus: estatus
        },
        success: function (response) {
            if (response.success) {
                if (estatus === "inactivo") {
                    advertencia = "Se restringirá el acceso al sistema para este usuario."
                } else {
                    advertencia = "Se restaurará el acceso al sistema para este usuario."
                }
                Swal.fire({
                    title: "¿Estas seguro ?",
                    icon: "warning",
                    text: advertencia,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Éxito",
                            text: `El usuario fue ${estatus === "activo" ? "activado" : "desactivado"} correctamente.`,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {//funcion anonima para recargar la tabla por ajax
                            tabla.ajax.reload();
                        });
                    }
                });
            } else {
                Swal.fire("Error", response.message || "No se pudo cambiar el estatus.", "error");
                tabla.ajax.reload();
            }
        },
        error: function () {
            Swal.fire("Error", "Error en el servidor", "error");
        }
    });
}

function actualizar_datos_usuario(id, nombre, dire, tel, usuario, rol, ced) {

    $("#id_usu").val(id);
    $("#nom_up").val(nombre);
    $("#dir_up").val(dire);
    $("#tel_up").val(tel);
    $("#user_up").val(usuario);
    $("#cmb_rol_up").val(rol).trigger('change.select2');
    $("#ced_up").val(ced);
    $("#modal_editar").modal('show');
}

function update_Usuario() {
    var id_up = $("#id_usu").val();
    var dir_up = $("#dir_up").val();
    var tel_up = $("#tel_up").val();
    var user_up = $("#user_up").val();
    var rol_up = $("#cmb_rol_up").val();
    var pwsd_up = $("#pswd_up").val();
    var pswd2_up = $("#pswd2_up").val();

    // Validación de campos obligatorios
    if (!dir_up || !tel_up || !user_up || !rol_up) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos obligatorios.", "warning");
    }

    // Validación de contraseña solo si se va a cambiar
    if (pwsd_up !== "" || pswd2_up !== "") {
        if (pwsd_up !== pswd2_up) {
            return Swal.fire("Mensaje de advertencia", "Las contraseñas no coinciden.", "warning");
        }
    }

    $.ajax({
        url: "../controllers/usuario/controlador_actualizar_datos_usuario.php",
        type: "POST",
        dataType: "JSON",
        data: {
            id_up: id_up,
            dir_up: dir_up,
            tel_up: tel_up,
            user_up: user_up,
            rol_up: rol_up,
            pwsd_up: pwsd_up // si va vacío, el backend sabrá que no debe actualizar
        }
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                $("#modal_editar").modal("hide");
                tabla.ajax.reload();
            });
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    })
}










