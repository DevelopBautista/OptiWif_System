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

        "language": idioma_espanol,
        "destroy": true
    });

    //Imprimir factura
    //Adaptado para funcionar en modo móvil también
    $('#tablaFacturas').on('click', '.btn-info', function () {
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

        var nfactura = data.nfactura;
        var url = '../controllers/pago/controlador_imprimir_ticket.php?num_factura=' + nfactura;
        if (nfactura) {
            window.open(url, '_blank');
        } else {
            Swal.fire("Mensaje de advertencia", "No se encotro registros.", "warning");
        }

    });



}