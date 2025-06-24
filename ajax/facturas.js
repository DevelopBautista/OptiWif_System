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
function listar_facturas_ajax() {
    tabla = $('#tablaFacturas').DataTable({
        "ajax": {
            "url": "../controllers/facturas/controlador_listar_facturas.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_factura", "visible": false },
            { "data": "nfactura" },
            { "data": "cliente" },
            { "data": "fecha_emision" },
            {
                "defaultContent": "<button class='btn btn-info btn-sm' title='imprimir factura'><i class='fa-solid fa-print'></i></button>"
            }
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

    //Imprimir factura
    //Adaptado para funcionar en modo móvil también
    $('#tablaFacturas').on('click', '.btn-info', function () {
        var tr = $(this).closest('tr');
        var row = tabla.row(tr);

        if (tr.hasClass('child')) {
            row = tabla.row(tr.prev());
        }

        var data = row.data();

        if (!data) {
            Swal.fire("Error", "No se pudo obtener los datos de la fila.", "error");
            return;
        }

        var nfactura = data.nfactura;

        if (!nfactura) {
            Swal.fire("Mensaje de advertencia", "No se encontró número de factura.", "warning");
            return;
        }

        $.ajax({
            url: '../controllers/facturas/verificar_pdf.php',
            type: 'POST',
            data: { nfactura: nfactura },
            dataType: 'json',
            success: function (resp) {
                if (resp.existe) {
                    window.open(resp.url, '_blank');
                } else {
                    Swal.fire("No disponible", "El archivo PDF de esta factura no existe.", "warning");
                }
            },
            error: function () {
                Swal.fire("Error", "No se pudo verificar la existencia del PDF.", "error");
            }
        });
    });



}