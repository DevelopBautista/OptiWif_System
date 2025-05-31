
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
function listar_servicios_ajax() {
    tabla = $('#tabla_detalle_servicio').DataTable({
        "ajax": {
            "url": "../controllers/servicio/controlador_listar_servicios.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_detalle", "visible": false },
            { "data": "nombre" },
            { "data": "servicio" },
            { "data": "plan" },
            { "data": "velocidad" },
            { "data": "precio" },
            { "data": "tipo_conexion" },
            { "data": "fecha_inicio" },
            { "data": "estado" },
            {
                "defaultContent": "<button class='btn btn-info btn-sm'><i class='fa-solid fa-eye'></i></button>&nbsp;<button  class=' btn btn-warning btn-sm'><i class='fa-solid fa-edit'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });


    // ver info del servicio
    $('#tabla_servicios ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();//obteniendo toda la data de la fila 
        //almacacenando la data de la fila por campos
        var id_servicio = data.id_servicio;
        var nom_cliente = data.nombre_completo;
        var plan = data.nombre;
        var ref_instal = data.referencia_direccion;
        var t_conexion = data.nombre_conexion;
        var datos_conexion = data.datos_conexion;
        var fecha = data.fecha_creacion;
        var estado = data.estado;
        ver_datos_servicio(id_servicio, nom_cliente, plan, ref_instal, t_conexion, datos_conexion, fecha, estado);
    });


    // obtener datos del servicio
    $('#tabla_servicios ').on('click', '.btn-warning', function () {
        var data = tabla.row($(this).parents('tr')).data();

        $("#modal_editar_servicio").modal("show");

    });



}


//funcion para crear un servicio
function crearServicio() {
    var id_cliente = $("#IdCliente").val();
    var id_plan = $("#cmb_planes").val();
    var id_tipo_conexion = $("#cmb_conexion").val();
    var id_servicio = $("#cmb_servicio").val();
    var direccion_referencia = $("#referenciaDir").val();
    var cliente = $("#nombreCliente").val();
    var dataConexion = $("#dataConexion").val();

    if (!cliente || !direccion_referencia || !dataConexion || !id_plan || !id_tipo_conexion || !id_servicio) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    $.ajax({
        url: "../controllers/servicio/controlador_crear_servicio.php",
        type: "POST",
        dataType: "json",
        data: {
            id_cliente: id_cliente,
            id_plan: id_plan,
            id_tipo_conexion: id_tipo_conexion,
            id_servicio: id_servicio,
            direccion_referencia: direccion_referencia
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
        console.log("Error:", error);
        console.log("Texto de respuesta:", xhr.responseText);
    });
}

function ver_datos_servicio(id_servicio, nom_cliente, plan, ref_instal, t_conexion, datos_conexion, fecha, estado) {
    $('#modal_showData_servicio').modal('show');
    //Pasandole los datos al modal_showData_servicio
    $("#id_servicio").val(id_servicio);
    $("#nom_show").val(nom_cliente);
    $("#plan_show").val(plan);
    $("#ri_show").val(ref_instal);
    $("#tconn_show").val(t_conexion);
    $("#Dconn_show").val(datos_conexion);
    $("#fecha_show").val(fecha);
    $("#estatus_show").val(estado);
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








