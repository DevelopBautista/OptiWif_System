<?php include("incluids/superior.php"); ?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Servicios</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <br>

        <div class="box-body">
            <table id="tabla_detalle_servicio" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Plan</th>
                        <th>Velocidad</th>
                        <th>Precio</th>
                        <th>Tipo Conexion</th>
                        <th>Fecha Contrato</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>

</div>


<!-- Modal Mostrar info servicios -->
<div class="modal" tabindex="-1" role="dialog" id="modalInfoServicio" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Datos extras del Servicio</h5>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="tel_show">Referencia de instalacion</label>
                    <input type="text" class="form-control" id="modal_RefIns" readonly>
                </div>
                <div class="form-group">
                    <label for="usu_show">Tipo de acceso del cliente</label>
                    <input type="text" class="form-control" id="modal_accesoCliente" readonly>
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


<!--actualizad datos del servicio-->
<div class="modal" tabindex="-1" role="dialog" id="modalUpdateServicio" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Actualizar Datos del Servicio</h4>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off" id="frm" onsubmit="return false">
                    <!--f01-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="hidden" id="id_cliente" name="id_cliente">
                            <input type="hidden" id="id_contrato">
                        </div>
                        <div class="form-group col-md-4">
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <select class="planes js-states form-control" name="state" id="cmb_planes" style="width: 100%;">

                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <select class="conexion js-states form-control" name="state" id="cmb_conexion" style="width: 100%;">
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" id="dataConexion" name="dataConexion"
                                placeholder="Acceso cliente (Ip,Usuario)">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="update_servicio()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;actualizar</b></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
            </div>
        </div>
    </div>
</div>

<!--====================================-->
<script>
    $(document).ready(function() {
        listar_servicios_ajax();
        tipo_plan();
        tipo_conexion();
        tipo_servicio();
    });
</script>

<script>
    $('#modalUpdateServicio, #modalInfoServicio').on('hidden.bs.modal', function() {
        // Mover foco a un botón visible o al body
        $('body').focus();
    });
</script>
<?php include("incluids/inferior.php"); ?>