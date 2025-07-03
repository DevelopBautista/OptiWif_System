// Configuración de idioma para DataTables
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

var tabla;
let $url_factura = "../views/libreporte/reports/facturas";

//----------- Listar pagos pendientes ---------------------
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
                    return data;
                }
            },
            {
                "defaultContent": "<button class='btn btn-info btn-sm' title='Pagar Mensualidad'><i class='fa-solid fa-file-invoice-dollar'></i></button>"
            }
        ],
        "language": idioma_espanol,
        "destroy": true,
        "responsive": true
    });

    $('#tabla_pagos').on('click', '.btn-info', function () {
        var tr = $(this).closest('tr');
        var row = tabla.row(tr.hasClass('child') ? tr.prev() : tr);
        var data = row.data();

        if (!data) {
            Swal.fire("Error", "No se pudo obtener los datos de la fila.", "error");
            return;
        }

        let monto = parseFloat(data.monto) || 0;
        let mora = 0;
        let total = monto;

        if (data.estado === 'vencido') {
            mora = parseFloat(data.mora) || 0;
            total += mora;
        }

        $("#id_mensualidad").val(data.id_mensualidad);
        $("#cliente").val(data.cliente);
        $("#cuotas_mensual").val(monto.toFixed(2));
        $("#fecha_pago").val(data.fecha_pagos);
        $("#estado_pago").val(data.estado);

        $("#mora").val(mora.toFixed(2));
        $("#mora_mostrar").val(mora.toFixed(2)); // si usas input visible para mostrar
        $("#monto_total_pagar").val(total.toFixed(2));
        $("#monto_total").val(total.toFixed(2)); // campo oculto para enviar al backend

        $("#modal_pago").modal("show");
    });

}

//----------- Listar pagos realizados ---------------------
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
            { extend: 'copyHtml5', text: '<i class="fas fa-copy"></i> Copiar', className: 'btn-export-copy', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn-export-excel', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i> CSV', className: 'btn-export-csv', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn-export-pdf', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir', className: 'btn-export-print', exportOptions: { columns: ':not(:last-child)' } }
        ],
        "language": idioma_espanol,
        "destroy": true
    });
}

//----------- Registrar pagos ---------------------
function registrar_pagos() {
    var efectivo = parseFloat($("#efectivo").val());
    var total = parseFloat($("#monto_total").val());

    if (isNaN(efectivo) || efectivo <= 0) {
        Swal.fire("Advertencia", "Ingresa un valor válido de efectivo", "warning");
        return;
    }

    if (efectivo < total) {
        Swal.fire("Advertencia", "El efectivo debe ser mayor o igual al monto total a pagar", "warning");
        return;
    }

    let formData = {
        id_mensualidad: $("#id_mensualidad").val(),
        monto_total_pagar: $("#monto_total").val(),
        fecha_pago: $("#fecha_pago").val(),
        metodo_pago: $("#metodo_pago").val(),
        referencia_pago: $("#referencia_pago").val(),
        observaciones: $("#observaciones").val(),
        mora: $("#mora").val()
    };

    $.ajax({
        url: "../controllers/pago/controlador_registrar_pago.php",
        type: "POST",
        dataType: "json",
        data: formData
    }).done(function (resp) {
        if (resp.exito) {
            Swal.fire({
                title: "Éxito",
                text: resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000
            }).then(function () {
                $('#modal_pago').modal('hide');
                $('#frm_pago')[0].reset();
                if (typeof tabla !== "undefined") {
                    tabla.ajax.reload(null, false);
                }
                window.open('../controllers/pago/controlador_imprimir_ticket.php?num_factura=' + resp.nfactura, '_blank');
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


//----------- Buscar cliente desde modal -------------
function bsucar_cliente_modal() {
    $("#modal_ver_clientes").modal("show");
    listar_clientes_servicio();
}



