<?php include("incluids/superior.php"); ?>
<div class="col-md-10">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Registro de Empresa ISP</h3>
        </div>
        <br>

        <div class="box-body">
            <form method="POST" enctype="multipart/form-data" autocomplete="off" id="frm" onsubmit="return false">
                <!--f01-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" name="nombre_empresa"
                            placeholder="Nombre de la empresa" id="nombre_empresa">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion">
                    </div>
                </div>
                <!--f02-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Telefono">
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" id="rnc" name="rnc" placeholder="RNC">
                    </div>
                </div>
                <!--f03-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="logo">Logo de la Empresa (JPG/PNG):</label><br>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" id="btn_registar" class="btn btn-primary" onclick="ingresar_datos_empresa()"><i class="fa  fa-check"><b>&nbsp;Registrar Empresa</b></i></button>
                    <button type="button" id="btn_editar" class="btn btn-warning" onclick="get_datos_empresa()"><i class="fa fa-edit"><b>&nbsp;Editar Datos</b></i></button>

                    <a href="" class="btn btn-danger"><i class="fa fa-close"><b>&nbsp;Cancelar</b></i></a>
                </div>
            </form>
        </div>

    </div>


    <!--modal editar empresa-->
    <div class="modal" tabindex="-1" role="dialog" id="modal_editar_empresa" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Actualizar Datos</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" autocomplete="off" id="frm" onsubmit="return false">
                        <!--f01-->
                        <div class="row">
                            <div class="form-group col-md-5">
                                <input type="hidden" id="id_empresa">
                                <input type="text" class="form-control" name="nombre_empresa"
                                    placeholder="Nombre de la empresa" id="nombre_empresa" disabled>
                            </div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion">
                            </div>
                        </div>
                        <!--f02-->
                        <div class="row">
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" id="tel" name="tel" placeholder="Telefono">
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" id="rnc" name="rnc" placeholder="RNC" disabled>
                            </div>
                        </div>
                        <!--f03-->
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label for="logo_edit">Logo de la Empresa (JPG/PNG):</label><br>
                                <input type="file" class="form-control" id="logo_edit" name="logo" accept="image/*">
                                <br>
                                <img id="preview_logo_edit" src="" alt="Logo actual" style="max-width: 150px; display: none;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="update_Empresa()" class="btn btn-warning"><i class="fa  fa-check"><b>&nbsp;actualizar</b></i></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-close"><b>&nbsp;Cancelar</b></i></button>
                </div>
            </div>
        </div>
    </div>
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
        $('#nombre_empresa').focus();
    });
</script>

<?php include("incluids/inferior.php"); ?>