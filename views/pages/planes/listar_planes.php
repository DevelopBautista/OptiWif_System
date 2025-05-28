<?php include("incluids/superior.php"); ?>
<div class="col-md-9">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Listado De planes</h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <br>

        <div class="box-body">
            <table id="tabla_plan" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Plan</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Acciones</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>

</div>

<!--actualizad plan-->
<div class="modal" tabindex="-1" role="dialog" id="modal_editar_plan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Actualizar Plan</h4>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <!--f01-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="hidden" id="id_plan">
                            <input type="text" class="form-control" name="nom_plan_up" id="nom_plan_up"
                                placeholder="Nombre del Plan">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="descripcion_plan_up" name="descripcion_plan_up"
                                placeholder="Descripcion">
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" id="precio_plan_up" name="precio_plan_up"
                                placeholder="Precio">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="update_Plan()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;actualizar</b></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
            </div>
        </div>
    </div>
</div>

<!--====================================-->
<script>
    $(document).ready(function() {
        listar_planes_datatable();
    });
</script>
<?php include("incluids/inferior.php"); ?>