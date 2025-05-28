
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
//-----------se lista los cllientes para obtener sus datos---------------------
function listar_clientes_servicio() {
    tabla = $('#tablaClientes').DataTable({
        "ajax": {
            "url": "../controllers/clientes/controlador_listar_clientes.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_cliente", "visible": false },
            { "data": "nombre_completo" },
            { "data": "numero_cedula" },
            {
                "defaultContent": "<button class='btn btn-info btn-sm'><i class='fa-solid fa-check'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });


    // obtener datos del cliente
    $('#tablaClientes ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();

        var id = data.id_cliente;
        var nom = data.nombre_completo;
        get_datos_cliente(id, nom);

    });



}


//-----------se lista los servicios---------------------
function listar_servicios() {
    tabla = $('#tabla_servicios').DataTable({
        "ajax": {
            "url": "../controllers/servicio/controlador_listar_servicios.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_servicio", "visible": false },
            { "data": "nombre_completo" },
            { "data": "nombre" },
            { "data": "nombre_conexion" },
            { "data": "fecha_creacion" },
            { "data": "estado" },
            {
                "defaultContent": "<button class='btn btn-info btn-sm'><i class='fa-solid fa-eye'></i></button>&nbsp;<button  class=' btn btn-warning btn-sm'><i class='fa-solid fa-edit'></i></button>&nbsp;<button class='btn btn-success btn-sm'><i class='fa-solid fa-check'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });


    // ver info del servicio
    $('#tabla_servicios ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();

        alert("viendo info del servicio");

    });


    // obtener datos del servicio
    $('#tabla_servicios ').on('click', '.btn-warning', function () {
        var data = tabla.row($(this).parents('tr')).data();

        $("#modal_editar_servicio").modal("show");

    });



}


//funcion para crear un servicio
function crearServicio() {
    var IdCliente = $('#IdCliente').val();
    var cliente = $('#nombreCliente').val();
    var referenciaDir = $('#referenciaDir').val();
    var cmb_planes = $('#cmb_planes').val();
    var cmb_conexion = $('#cmb_conexion').val();
    var datos_conexion = $("#dataConexion").val();

    if (!cliente || !referenciaDir || !datos_conexion || !cmb_planes || !cmb_conexion) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    $.ajax({
        url: "../controllers/servicio/controlador_crear_servicio.php",
        type: "POST",
        dataType: "json",
        data: {
            IdCliente: IdCliente,
            referenciaDir: referenciaDir,
            cmb_planes: cmb_planes,
            cmb_conexion: cmb_conexion,
            datos_conexion: datos_conexion
        }
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                document.getElementById('frm').reset();
                if (typeof table !== "undefined") {
                    table.ajax.reload();
                }
            });
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

function get_datos_cliente(id, nom) {

    $("#IdCliente").val(id);
    $("#nombreCliente").val(nom);
    $("#modal_ver_clientes").modal('hide');
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






//llamando modal modal_ver_clientes

function bsucar_cliente_modal() {
    $("#modal_ver_clientes").modal("show");
    listar_clientes_servicio();
}








