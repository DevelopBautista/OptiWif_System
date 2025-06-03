
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
//-----------se lista los pagos---------------------
function listar_pagos_ajax() {
    tabla = $('#tabla_pagos').DataTable({
        "ajax": {
            "url": "../controllers/pago/controlador_listar_pagos.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_mensualidad", "visible": false },
            { "data": "cliente" },
            { "data": "plan" },
            { "data": "monto" },
            { "data": "fecha_generada" },
            {
                "data": "estado",
                //si el cliente pago sera verde sino roja
                "render": function (data) {
                    if (data === "pagado") {
                        return "<span class='label label-success'>" + data + "</span>";
                    } else if (data === "pendiente") {
                        return "<span class='label label-warning'>" + data + "</span>";
                    }else if(data==='vencido'){
                        return "<span class='label label-danger'>" + data + "</span>";
                    }
                }


            },
            {
                "defaultContent": "<button  class=' btn btn-info btn-sm'><i class='fa-solid fa-file-invoice-dollar'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });



    // obtener datos del servicio
    $('#tabla_pagos ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();
        var id_mensualidad=data.id_mensualidad;
        var cliente=data.cliente;

        $("#id_mensualidad").val(id_mensualidad);
         $("#cliente").val(cliente);

        $("#modal_pago").modal("show");

    });



}


//funcion para crear un servicio
function crearServicio() {
    var id_cliente = $("#IdCliente").val();
    var id_plan = $("#cmb_planes").val();
    var id_tipo_conexion = $("#cmb_conexion").val();
    var id_servicio = $("#cmb_servicio").val();
    var acceso_cliente = $("#acceso_cliente").val();
    var observaciones = $("#observaciones").val();
    var cliente = $("#nombreCliente").val();

    if (!cliente || !acceso_cliente || !id_plan || !id_tipo_conexion || !id_servicio || !observaciones) {
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
            acceso_cliente: acceso_cliente,
            observaciones: observaciones
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








