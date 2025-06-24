
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
let $url_factura = "../views/libreporte/reports/facturas";
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
                "defaultContent": "<button class='btn btn-info btn-sm' title='Pagar Mensualidad'><i class='fa-solid fa-file-invoice-dollar'></i></button>"
            }
        ],
        "language": idioma_espanol,
        "destroy": true,
        "responsive": true //Asegura que soporte vista móvil
    });

    //Adaptado para funcionar en modo móvil también
    $('#tabla_pagos').on('click', '.btn-info', function () {
        var tr = $(this).closest('tr');
        var row = tabla.row(tr);

        // Si es una fila .child, buscar la anterior (la principal)
        if (tr.hasClass('child')) {
            row = tabla.row(tr.prev());
        }

        var data = row.data();

        if (!data) {
            Swal.fire("Error", "No se pudo obtener los datos de la fila.", "error");
            return;
        }

        $("#id_mensualidad").val(data.id_mensualidad);
        $("#cliente").val(data.cliente);
        $("#monto_total_pagar").val(data.monto);
        $("#fecha_pago").val(data.fecha_pagos);
        $("#estado_pago").val(data.estado);
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
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copiar',
                className: 'btn-export-copy',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-export-excel',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn-export-csv',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-export-pdf',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn-export-print',
                exportOptions: { columns: ':not(:last-child)' }
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });



}

//funcion para registrar un pago
function registrar_pagos() {
    let formData = {// agrupo todo en una sola variable(no sabia eso)
        id_mensualidad: $("#id_mensualidad").val(),
        monto_total_pagar: $("#monto_total_pagar").val(),
        fecha_pago: $("#fecha_pago").val(),
        metodo_pago: $("#metodo_pago").val(),
        referencia_pago: $("#referencia_pago").val(),
        observaciones: $("#observaciones").val(),
        dias_mas: $("#dias_mas").val(),
        cargo_extra: $("#cargo_extra").val()

    };
    // se parsea de text a number
    //var estado_paga = $("#estado_pago").val();
    var monto_total_pagar = parseFloat($("#monto_total_pagar").val());
    var efectivo = parseFloat($("#efectivo").val());
    // se evalua que no sea vacio O menor a 0
    if (isNaN(efectivo) || efectivo <= 0) {
        Swal.fire("Advertencia", "Ingresa un valor válido de efectivo", "warning");
        return;
    }
    // me aseguro que el monto introducido sea mayor O igual a la mensualidad
    if (efectivo < monto_total_pagar) {
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
            Swal.fire({
                title: "mensaje de confirmación",
                text: "Exito" + resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000

            }).then(function () {
                $('#modal_pago').modal('hide');
                $('#frm_pago')[0].reset();
                if (typeof tabla !== "undefined") {
                    tabla.ajax.reload(null, false);
                }
                var url = '../controllers/pago/controlador_imprimir_ticket.php?num_factura=' + resp.nfactura;
                window.open(url, '_blank');
            });

        } else {
            Swal.fire("mensaje de error", resp.mensaje || "No se pudo registrar el pago", "error");
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








