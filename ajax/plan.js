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
function listar_planes_datatable() {
    tabla = $('#tabla_plan').DataTable({
        "ajax": {
            "url": "../controllers/plan/controlador_listar_planes.php",
            "type": "POST"
        },
        "columns": [
            { "data": "id_plan", "visible": false },
            { "data": "nombre_plan" },
            { "data": "velocidad" },
            { "data": "precio" },
            {
                "defaultContent": "<button  class=' btn btn-warning btn-sm'><i class='fa-solid fa-edit'></i></button>"
            }
        ],

        "language": idioma_espanol,
        "destroy": true
    });

    // actualziar plan
    $('#tabla_plan ').on('click', '.btn-warning', function () {
        var data = tabla.row($(this).parents('tr')).data();
        var id = data.id_plan;
        var nombre_plan = data.nombre_plan;
        var velocidad = data.velocidad;
        var precio = data.precio;
        actualizar_planes(id, nombre_plan, velocidad, precio);

    });

    // Listener para botón de editar en DataTable
    $('#tabla_plan').on('click', '.btn-warning', function () {
        const data = tabla.row($(this).parents('tr')).data();

        if (!data) {
            console.error("No se pudo obtener los datos de la fila.");
            return;
        }

        const id = data.id_plan;
        const nombre_plan = data.nombre_plan;
        const velocidad = data.velocidad;
        const precio = data.precio;
    });



}

function crear_plan() {
    var nombre_plan = $('#nombre_plan').val();
    var velocidad = $('#velocidad').val();
    var precio = $('#precio').val();

    if (!nombre_plan || !velocidad || !precio) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos.", "warning");
    }

    $.ajax({
        url: "../controllers/plan/controlador_crear_plan.php",
        type: "POST",
        dataType: "json",
        data: {
            nombre_plan: nombre_plan,
            velocidad: velocidad,
            precio: precio
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


function cambiar_estatus_usuario(id, estatus) {
    var advertencia = "";
    $.ajax({
        url: "../controllers/usuario/controlador_cambiarEstatus_usuario.php",
        type: "POST",
        dataType: "json",
        data: {
            id_usuario: id,
            estatus: estatus
        },
        success: function (response) {
            if (response.success) {
                if (estatus === "inactivo") {
                    advertencia = "Se restringirá el acceso al sistema para este usuario."
                } else {
                    advertencia = "Se restaurará el acceso al sistema para este usuario."
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
                            text: `El usuario fue ${estatus === "activo" ? "activado" : "desactivado"} correctamente.`,
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

function actualizar_planes(id, nombre, velocidad, precio) {
    $("#id_plan").val(id);
    $("#nom_plan_up").val(nombre);
    $("#velocidad_up").val(velocidad);
    $("#precio_plan_up").val(precio);

    $("#modal_editar_plan").modal('show');
}

function update_Plan() {
    var id = $("#id_plan").val();
    var nombre = $("#nom_plan_up").val();
    var velocidad = $("#velocidad_up").val();
    var precio = $("#precio_plan_up").val();
    // Validación de campos obligatorios
    if (!nombre || velocidad ==0 || !precio) {
        return Swal.fire("Mensaje de advertencia", "Debe llenar todos los campos obligatorios.", "warning");
    }

    $.ajax({
        url: "../controllers/plan/controlador_actualizar_plan.php",
        type: "POST",
        dataType: "JSON",
        data: {
            id: id,
            nombre: nombre,
            velocidad: velocidad,
            precio: precio
        }
    }).done(function (resp) {
        if (resp.status === "ok") {
            Swal.fire("Éxito", resp.mensaje, "success").then(() => {
                $("#modal_editar_plan").modal("hide");
                tabla.ajax.reload();
            });
        } else {
            Swal.fire("Error", resp.mensaje, "error");
        }
    })
}










