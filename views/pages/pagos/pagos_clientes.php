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
                <h4 class="modal-title">Cobro de Mensualidad</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                   <input type="" id="id_mensualidad" name="id_mensualidad">
                   <br><br>
                     <input type="text" id="cliente" name="cliente">
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