
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
            { "data": "fecha_pagos" },
            {
                "data": "estado",
                //si el cliente pago sera verde sino roja
                "render": function (data) {
                    if (data === "pagado") {
                        return "<span class='label label-success'>" + data + "</span>";
                    } else if (data === "pendiente") {
                        return "<span class='label label-warning'>" + data + "</span>";
                    } else if (data === 'vencido') {
                        return "<span class='label label-danger'>" + data + "</span>";
                    }
                }


            },
            {
                "defaultContent": "<button  class=' btn btn-info btn-sm title='Pagar Mensualidad'><i class='fa-solid fa-file-invoice-dollar'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });



    // obtener datos del servicio para llevar el modal_pago
    $('#tabla_pagos ').on('click', '.btn-info', function () {
        var data = tabla.row($(this).parents('tr')).data();
        var id_mensualidad = data.id_mensualidad;
        var monto = data.monto;
        var cliente = data.cliente
        var fecha_pagos = data.fecha_pagos;

        $("#id_mensualidad").val(id_mensualidad);
        $("#cliente").val(cliente);
        $("#monto").val(monto);
        $("#fecha_pago").val(fecha_pagos);
        $("#modal_pago").modal("show");

    });



}

//-----------se lista los pagos realizados---------------------
function listar_pagos_realizados_ajax() {
    tabla = $('#tabla_pagos_realizados').DataTable({
        "ajax": {
            "url": "../controllers/pago/controlador_pagos_realizados.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id", "visible": false },
            { "data": "cliente" },
            { "data": "mensualidad" },
            { "data": "fecha_pago" }
        ],

        "language": idioma_espanol,
        "destroy": true
    });

}

//funcion para registrar un pago
function registrar_pagos() {
    let formData = {// agrupo todo en una sola variable(no sabia eso)
        id_mensualidad: $("#id_mensualidad").val(),
        monto_pagado: $("#monto").val(),
        fecha_pago: $("#fecha_pago").val(),
        metodo_pago: $("#metodo_pago").val(),
        referencia_pago: $("#referencia_pago").val(),
        observaciones: $("#observaciones").val()
    };
    // se parsea de text a number
    var monto = parseFloat($("#monto").val());
    var efectivo = parseFloat($("#efectivo").val());
    // se evalua que no sea vacio O menor a 0
    if (isNaN(efectivo) || efectivo <= 0) {
        Swal.fire("Advertencia", "Ingresa un valor válido de efectivo", "warning");
        return;
    }
    // me aseguro que el monto introducido sea mayor O igual a la mensualidad
    if (efectivo < monto) {
        Swal.fire("Advertencia", "El efectivo debe ser mayor o igual al monto de la mensualidad", "warning");
        return;
    }
    $.ajax({
        url: "../controllers/pago/controlador_registrar_pago.php",
        type: "POST",
        dataType: "json",
        data: formData
    }).done(function (resp) {
        if (resp.exito) {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                $('#modal_pago').modal('hide');
                $('#frm_pago')[0].reset();
                if (typeof tabla !== "undefined") {
                    tabla.ajax.reload(null, false);
                }
            });
        } else {
            Swal.fire("Error", resp.mensaje || "No se pudo registrar el pago", "error");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire("Error", "Error de servidor: " + textStatus, "error");
        console.error("AJAX error:", errorThrown);
        console.log("Respuesta del servidor:", jqXHR.responseText);
    });
}


//llamando modal modal_ver_clientes

function bsucar_cliente_modal() {
    $("#modal_ver_clientes").modal("show");
    listar_clientes_servicio();
}








