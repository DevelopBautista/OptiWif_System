
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


//-----------listar los servicios---------------------
function listar_servicios_ajax() {
    tabla = $('#tabla_detalle_servicio').DataTable({
        "ajax": {
            "url": "../controllers/servicio/controlador_listar_servicios.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_contrato", "visible": false },
            { "data": "cliente" },
            { "data": "servicio" },
            { "data": "nombre_plan" },
            { "data": "velocidad" },
            { "data": "precio" },
            { "data": "tipo_conexion" },
            { "data": "fecha_contrato" },
            {
                "data": "estado",
                "render": function (data) {
                    if (data == "Activo") {
                        return "<span class='label label-success'>" + data + "</span>";
                    } else if (data == "Cancelado") {
                        return "<span class='label label-danger'>" + data + "</span>";
                    } else {
                        return data;
                    }
                }
            },
            {
                "defaultContent": `
                <button class='btn btn-info btn-sm' title='Ver Detalles'><i class='fa-solid fa-eye'></i></button>
                <button class='btn btn-warning btn-sm btn-edit' title='Editar'><i class='fa-solid fa-edit'></i></button>
                <button class='btn btn-success btn-sm' title='Activar/Desactivar'><i class='fa-solid fa-check'></i></button>
            `
            }
        ],

        "language": idioma_espanol,
        "destroy": true,

        //funcion para desactivar el btn edit
        "rowCallback": function (row, data, index) {
            if (data.estado === "Cancelado") {
                // Desactiva todos los botones de esta fila
                $(row).find("button.btn-edit").prop("disabled", true);
            }
        }
    });



    //cambiar estatus del servicio
    $('#tabla_detalle_servicio').on('click', '.btn-success', function () {
        var data = obtenerDataDeFila(this);
        if (!data) return Swal.fire("Error", "No se pudo obtener la data.", "error");

        var nuevo_estatus = data.estado === "Activo" ? "Cancelado" : "Activo";
        cambiar_estatus_servicio(data.id_contrato, nuevo_estatus);
    });


    //para cuando este en modo celular para que no de error de undefine
    $('#tabla_detalle_servicio').on('click', '.btn-info', function () {
        let fila = $(this).closest('tr');
        let data = tabla.row(fila.hasClass('child') ? fila.prev() : fila).data();

        if (!data) {
            console.error("No se pudo obtener la fila de datos");
            return;
        }

        var observaciones = data.observaciones;
        var acceso_cliente = data.acceso_cliente;
        ver_datos_servicio(observaciones, acceso_cliente);    // Pasa los datos si tu función los usa
    });


    // obtener datos del servicio
    $('#tabla_detalle_servicio ').on('click', '.btn-warning', function () {
        let fila = $(this).closest('tr');
        let data = tabla.row(fila.hasClass('child') ? fila.prev() : fila).data();

        if (!data) {
            console.error("No se pudo obtener la fila de datos");
            return;
        }
        var id_contrato = data.id_contrato;
        var IdCliente = data.id_cliente;
        var id_plan = data.id_plan;
        var id_tipo_conexion = data.id_tipo_conexion;
        var acceso_cliente = data.acceso_cliente;

        actualizar_datos_servicio(IdCliente, id_plan, id_tipo_conexion, acceso_cliente, id_contrato);



    });



}


//funcion para crear un servicio
function crearServicio() {
    var id_cliente = $("#id_cliente").val();
    var id_plan = $("#cmb_planes").val();
    var id_tipo_conexion = $("#cmb_conexion").val();
    var id_servicio = $("#cmb_servicio").val();
    var acceso_cliente = $("#acceso_cliente").val();
    var observaciones = $("#observaciones").val();
    var fecha_contrato = $("#fecha_contrato").val();
    var dias_mas = $("#dias_mas").val();
    var cargo_extra = $("#cargo_extra").val() || 0;
    var cliente = $("#nombreCliente").val();

    if (!cliente || !acceso_cliente || !id_plan || !id_tipo_conexion || !id_servicio || !observaciones || !fecha_contrato || !dias_mas || !cargo_extra) {
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
            observaciones: observaciones,
            fecha_contrato: fecha_contrato,
            dias_mas: dias_mas,
            cargo_extra: cargo_extra
        }
    }).done(function (resp) {
        if (resp.status === "ok") {

            Swal.fire({
                title: "mensaje de confirmación",
                text: "Éxito: " + resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000

            }).then(function () {
                document.getElementById('frm').reset();
                if (typeof tabla !== "undefined") {
                    tabla.ajax.reload();
                }

                back_to_dashbaord();
            });


        } else {
            Swal.fire("Error", resp.mensaje || "No se pudo realizar el registro.", "error");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire("Error", "Error de servidor: " + textStatus, "error");

        console.error("AJAX error:", errorThrown);
        console.log("Texto de respuesta:", jqXHR.responseText);
    });
}

function ver_datos_servicio(observaciones, acceso_cliente) {
    $('#modalInfoServicio').modal('show');
    //Pasandole los datos al modal_showData_servicio

    $("#modal_RefIns").val(observaciones);
    $("#modal_accesoCliente").val(acceso_cliente);


}

function get_datos_cliente(id, nom) {

    $("#id_cliente").val(id);
    $("#nombreCliente").val(nom);
    $("#modal_ver_clientes").modal('hide');
}

function actualizar_datos_servicio(id_cliente, id_plan, id_tipo_conexion, acceso_cliente, id_contrato) {


    $("#id_cliente").val(id_cliente);
    $("#cmb_planes").val(id_plan);
    $("#cmb_conexion").val(id_tipo_conexion);
    $("#dataConexion").val(acceso_cliente);
    $("#id_contrato").val(id_contrato);

    $("#modalUpdateServicio").modal("show");
}


function update_servicio() {
    var id_cliente = $("#id_cliente").val();
    var id_plan = $("#cmb_planes").val();
    var id_tipo_conexion = $("#cmb_conexion").val();
    var acceso_cliente = $("#dataConexion").val();
    var nueva_fecha_pago = $("#nueva_fecha_contrato").val();
    var id_contrato = $("#id_contrato").val();

    // Validación de campos obligatorios
    if (!id_plan || !id_tipo_conexion || !acceso_cliente) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos obligatorios.", "warning");
    }

    $.ajax({
        url: "../controllers/servicio/controlador_actualizar_datos_servicio.php",
        type: "POST",
        dataType: "JSON",
        data: {
            id_cliente: id_cliente,
            id_plan: id_plan,
            id_tipo_conexion: id_tipo_conexion,
            acceso_cliente: acceso_cliente,
            nueva_fecha_pago: nueva_fecha_pago,
            id_contrato: id_contrato,

        }
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire({
                title: "mensaje de confirmación",
                text: "Éxito: " + resp.mensaje,
                icon: "success",
                showConfirmButton: false,
                timer: 2000

            }).then(function () {
                $("#modalUpdateServicio").modal("hide");
                tabla.ajax.reload();
            });

        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    })
}

function cambiar_estatus_servicio(id, estatus) {
    var advertencia = "";
    $.ajax({
        url: "../controllers/servicio/controlador_cambiarEstatus_servicio.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            estatus: estatus
        },
        success: function (response) {
            if (response.success) {
                if (estatus === "Cancelado") {
                    advertencia = "Se retirará la conectividad a internet de este servicio."
                } else {
                    advertencia = "Se asignará conectividad a internet a este servicio."
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
                            text: `El servicio fue ${estatus === "Activo" ? "Activo" : "Cancelado"} correctamente.`,
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






//llamando modal modal_ver_clientes

function bsucar_cliente_modal() {
    $("#modal_ver_clientes").modal("show");
    listar_clientes_servicio();
}








