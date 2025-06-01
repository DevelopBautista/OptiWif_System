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
            <table id="tabla_usuarios" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Plan</th>
                        <th>Mensualidad</th>
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



<!--Pago del cliente-->
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
                            <input type="" id="id_usu">
                            <input type="text" class="form-control" name="nom_up" id="nom_up" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="dir_up" name="dir_up"
                                placeholder="Direccion">
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" id="tel_up" name="tel_up"
                                placeholder="Telefono">
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" id="user_up" name="user_up"
                                placeholder="Usuario">
                        </div>
                    </div>
                    <!--f03-->
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="rol_usuarios_up" name="cmb_rol" id="cmb_rol_up" style="width: 100%;">
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="password" class="form-control" id="pswd_up" name="pswd_up"
                                placeholder="Contraseña(nueva)">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="password" class="form-control" id="pswd2_up" name="pswd2_up"
                                placeholder="Repetir Contraseña">
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" id="ced_up" name="ced_up" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="update_Usuario()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;actualizar</b></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
            </div>
        </div>
    </div>
</div>

<!--====================================-->
<script>
    $(document).ready(function() {
       
    });
</script>
<?php include("incluids/inferior.php"); ?>