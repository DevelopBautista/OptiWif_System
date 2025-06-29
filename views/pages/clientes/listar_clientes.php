<?php
    session_start();
    include("incluids/superior.php");

?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Gestionar Clientes</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <!--hare put your view-->
        <br>
        <div class="box-body">
            <table id="tabla_clientes" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>Telefono</th>
                        <th>Acciones</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>
<!-- Modal Mostrar Datos del Clientes -->
<div class="modal" tabindex="-1" role="dialog" id="modal_showData" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Datos del Cliente</h5>
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"></i>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!--actualizad datos del usuario-->
<div class="modal" tabindex="-1" role="dialog" id="modal_editar" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Actualizar Datos del Cliente</h4>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <!--f01-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <input type="hidden" id="id_cliente">
                            <label for="nom_show">Nombre Completo</label>
                            <input type="text" class="form-control" name="nom_up" id="nom_up">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nom_show">Direccion</label>
                            <input type="text" class="form-control" id="dir_up" name="dir_up"
                                placeholder="Direccion">
                        </div>
                    </div>
                    <!--f02-->
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="nom_show">Telefono</label>
                            <input type="text" class="form-control" id="tel_up" name="tel_up"
                                placeholder="Telefono">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nom_show">Cedula</label>
                            <input type="text" class="form-control" id="ced_up" name="ced_up">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="update_cliente()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;actualizar</b></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        listar_clientes();
    });
</script>

<script>
    //esto es para enviar datos al js
    const usuarioActual = "<?php echo $_SESSION['rol']; ?>";
    console.log("desde listar_clientes: "+usuarioActual);
</script>
<?php include("incluids/inferior.php"); ?>