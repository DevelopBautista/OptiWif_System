<?php include("incluids/superior.php"); ?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Listado De Servicios</h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <br>

        <div class="box-body">
            <table id="tabla_servicios" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>plan</th>
                        <th>Tipo_Conexion</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
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
<div class="modal" tabindex="-1" role="dialog" id="modal_showData" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Datos del Usuario</h5>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="nom_show">Nombre Completo</label>
                    <input type="text" class="form-control" id="nom_show" readonly>
                </div>
                <div class="form-group">
                    <label for="ced_show">Cédula</label>
                    <input type="text" class="form-control" id="ced_show" readonly>
                </div>
                <div class="form-group">
                    <label for="tel_show">Teléfono</label>
                    <input type="text" class="form-control" id="tel_show" readonly>
                </div>
                <div class="form-group">
                    <label for="dir_show">Dirección</label>
                    <input type="text" class="form-control" id="dir_show" readonly>
                </div>
                <div class="form-group">
                    <label for="usu_show">Usuario</label>
                    <input type="text" class="form-control" id="usu_show" readonly>
                </div>
                <div class="form-group">
                    <label for="rol_show">Rol</label>
                    <input type="text" class="form-control" id="rol_show" readonly>
                </div>
                <div class="form-group">
                    <label for="estatus_show">Estatus</label>
                    <input type="text" class="form-control" id="estatus_show" readonly>
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
<div class="modal" tabindex="-1" role="dialog" id="modal_editar_servicio" data-backdrop="static" data-keyboard="false">
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
                            <input type="hidden" id="IdCliente" name="IdCliente">
                            <input type="text" class="form-control" name="nombreCliente"
                                placeholder="Cliente" id="nombreCliente">
                        </div>
                        <div class="form-group col-md-4">
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" id="referenciaDir" name="referenciaDir"
                                placeholder="Referencia de la instalacion del servicio">
                        </div>
                    </div>
                    <!--f03-->
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="planes js-states form-control" name="state" id="cmb_planes" style="width: 100%;">

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select class="conexion js-states form-control" name="state" id="cmb_conexion" style="width: 100%;">
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" id="dataConexion" name="dataConexion"
                                placeholder="Usuario Y/o Ip">
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
        listar_servicios();
    });
</script>
<?php include("incluids/inferior.php"); ?>