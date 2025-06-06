<?php include("incluids/superior.php"); ?>
<div class="col-md-9">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Crear Servicio</h3>
        </div>
        <br>

        <div class="box-body">
            <form method="POST" autocomplete="off" id="frm" onsubmit="return false">
                <!--f01-->
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        <input type="text" class="form-control" name="nombreCliente"
                            placeholder="Cliente" id="nombreCliente">
                    </div>
                    <div class="form-group col-md-5">
                        <button type="button" class="btn btn-primary btn-md" onclick="bsucar_cliente_modal()">Buscar Clientes <i class="fa-solid fa-magnifying-glass">
                            </i>
                        </button>
                    </div>
                </div>
                <!--f02-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="observaciones" name="observaciones"
                            placeholder="observaciones">
                    </div>
                </div>
                <!--f03-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <select class="planes js-states form-control" name="state" id="cmb_planes" style="width: 100%;">

                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="conexion js-states form-control" name="state" id="cmb_conexion" style="width: 100%;">

                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <select class="servicio js-states form-control" name="state" id="cmb_servicio" style="width: 100%;">

                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="acceso_cliente" name="acceso_cliente"
                            placeholder="Acceso cliente (Ip,Usuario)">
                    </div>
                </div>
                <!--f04-->
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="fecha_contrato">Fecha de contrato:</label>
                        <input type="date" class="form-control" id="fecha_contrato" name="fecha_contrato" require>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="fecha_contrato">Dias de Gracias:</label>
                        <input type="number" class="form-control" id="dias_mas" name="dias_mas" require>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="cargo_extra">Cargo Extra:</label>
                        <input type="number" class="form-control" id="cargo_extra" name="cargo_extra" require>
                    </div>

                </div>


                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="crearServicio()"><i class="fa  fa-check"><b>&nbsp;Crear Servicio</b></i></button>
                    <a href="" class="btn btn-danger"><i class="fa fa-close"><b>&nbsp;Cancelar</b></i></a>
                </div>
            </form>
        </div>

    </div>

    <!--modal para buscar cliente-->
    <div class="modal" tabindex="-1" role="dialog" id="modal_ver_clientes" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Datos del Cliente</h5>
                </div>

                <div class="modal-body">
                    <div class="box-body">
                        <table id="tablaClientes" class="display responsive nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre Completo</th>
                                    <th>Cedula</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-close"></i>&nbsp;Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--/-->


    <style type="text/css">
        .box-body {
            /* Estilos generales de la caja */
            /* ... */
            margin-left: 20px;
            /* Agrega 20 píxeles de espacio a la izquierda */
        }
    </style>

</div>

<script>
    $(document).ready(function() {
        $('.planes').select2({
            placeholder: "Seleccione Un Plan",
            allowClear: true,
            width: 'resolve'
        });
        //--------------------------
        $('.conexion').select2({
            placeholder: "Seleccione tipo de conexión",
            allowClear: true,
            width: 'resolve'
        });
        //-----------------------------
        $('.servicio').select2({
            placeholder: "Seleccione Un Servicio",
            allowClear: true,
            width: 'resolve'
        });
        //-----------------------------
        tipo_plan();
        tipo_conexion();
        tipo_servicio();

    });
</script>

<?php include("incluids/inferior.php"); ?>