<?php include("incluids/superior.php"); ?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Listado de Pagos</h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <br>

        <div class="box-body">
            <table id="tabla_pagos" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Plan</th>
                        <th>Mensualidad</th>
                        <th>Fecha de Pago</th>
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



<!--form cobro-->
<div class="modal" tabindex="-1" role="dialog" id="modal_pago" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Pago del Cliente</h4>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <!--f01-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="" id="id_pago">
                            <input type="text" class="form-control" name="nom_up" id="nom_up" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <select class="metodo_pago js-states form-control" name="state" id="cmb_metodoPago" style="width: 100%;">

                            </select>
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" id="referecnia" name="referecnia"
                                placeholder="Referencia (Opcional)">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cobrar()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;Cobrar</b></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
            </div>
        </div>
    </div>
</div>

<!--====================================-->
<script>
    $(document).ready(function() {
        $(".metodo_pago").select2();
        listar_pagos_ajax();
    });
</script>
<?php include("incluids/inferior.php"); ?>