
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
function listar_clientes() {
    tabla = $('#tabla_clientes').DataTable({
        "ajax": {
            "url": "../controllers/clientes/controlador_listar_clientes.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_cliente", "visible": false },
            { "data": "nombre_completo" },
            { "data": "telefono" },
            {
                "defaultContent": "<button class='btn btn-info btn-sm' title='Ver detalles'><i class='fa-solid fa-eye'></i></button>&nbsp;<button  class='btn btn-warning btn-sm' title='Editar'><i class='fa-solid fa-edit'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });

    //ver datos del cliente
    $('#tabla_clientes ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();//obteniendo toda la data de la fila 
        //almacacenando la data de la fila por campos
        var id = data.id_cliente;
        var nom = data.nombre_completo;
        var ced = data.numero_cedula;
        var dir = data.direccion;
        var tel = data.telefono;
        ver_datos_clientes(nom, ced, dir, tel);
    });

    // actualziar datos del cliente
    $('#tabla_clientes ').on('click', '.btn-warning', function () {
        var data = tabla.row($(this).parents('tr')).data();

        var id = data.id_cliente;
        var nom = data.nombre_completo;
        var ced = data.numero_cedula;
        var dir = data.direccion;
        var tel = data.telefono;

        actualizar_datos_cliente(id, nom, ced, dir, tel);

    });



    // Listener para botón de editar en DataTable
    $('#tabla_clientes').on('click', '.btn-warning', function () {
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

        //actualizar_datos_usuario(id, nom, ced, tel, dir, usu, rol, pswd);
    });

}


function ingresar_cliente() {
    var nombres = $('#nombres').val();
    var dir = $('#dir').val();
    var tel = $('#tel').val();
    var ced = $('#ced').val();

    if (!nombres || !dir || !tel || !ced) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    $.ajax({
        url: "../controllers/clientes/controlador_insert_cliente.php",
        type: "POST",
        dataType: "json",
        data: {
            nombres: nombres,
            ced: ced,
            dir: dir,
            tel: tel,

        }
    }).done(function (resp) {
        console.log(resp);
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

function ver_datos_clientes(nom, ced, dir, tel) {
    $('#modal_showData').modal('show');
    //Pasandole los datos al modal_showData
    $("#nom_show").val(nom);
    $("#ced_show").val(ced);
    $("#dir_show").val(dir);
    $("#tel_show").val(tel);
}

function actualizar_datos_cliente(id, nom, ced, dir, tel) {

    $("#id_cliente").val(id);
    $("#nom_up").val(nom);
    $("#ced_up").val(ced);
    $("#dir_up").val(dir);
    $("#tel_up").val(tel);
    $("#modal_editar").modal('show');
}

function update_cliente() {

    var id = $("#id_cliente").val();
    var dir = $("#dir_up").val();
    var tel = $("#tel_up").val();
    $("#modal_editar").modal('show');


    if (dir === "" || tel === "") {
        Swal.fire({
            title: "Mensaje de Advertencia ",
            text: "Hay campos vacios.",
            icon: "warning",
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        $.ajax({
            url: "../controllers/clientes/controlador_actualizar_datos_cliente.php",
            type: "POST",
            dataType: "JSON",
            data: {
                dir: dir,
                tel: tel,
                id: id
            }
        }).done(function (resp) {
            if (resp.status === "ok") {
                Swal.fire({
                    title: "Éxito",
                    text: resp.mensaje,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500 // opcional: cierra automáticamente después de 2 segundos
                }).then(() => {
                    $("#modal_editar").modal("hide");
                    tabla.ajax.reload();
                });
            } else {
                Swal.fire("Error", resp.mensaje, "error");
            }
        })
    }


}










