<?php include("incluids/superior.php"); ?>
<div class="col-md-10">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title" id="titulo_form">Registro de Empresa ISP</h3>
        </div>
        <br>

        <div class="box-body">
            <form method="POST" enctype="multipart/form-data" autocomplete="off" id="frm" onsubmit="return false">

                <!-- Fila 1: Nombre y Dirección -->
                <div class="row">
                    <input type="hidden" id="id_empresa">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" name="nombre_empresa"
                            placeholder="Nombre de la empresa" id="nombre_empresa">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
                    </div>
                </div>

                <!-- Fila 2: Teléfono y RNC -->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Teléfono">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="rnc" name="rnc" placeholder="RNC">
                    </div>
                </div>

                <!-- Fila 3: Logo -->
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="logo">
                            Logo de la Empresa (formato JPG o PNG, sin fondo, tamaño recomendado 550x550 px):
                        </label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    </div>
                </div>

                <!-- Vista previa del logo -->
                <div class="row">
                    <div class="form-group col-md-5">
                        <label>Vista previa del logo:</label><br>
                        <img id="preview_logo_edit" src="" alt="Logo actual" style="max-width: 150px; display: none;">
                    </div>
                </div>

                <!-- Botones -->
                <div class="form-group">
                    <button type="button" id="btn_registar" class="btn btn-primary" style="display:none;" onclick="ingresar_datos_empresa()">
                        <i class="fa fa-check"><b>&nbsp;Registrar Empresa</b></i>
                    </button>

                    <button type="button" id="btn_editar" class="btn btn-warning" style="display:none;" onclick="update_Empresa()">
                        <i class="fa fa-edit"><b>&nbsp;Editar Datos</b></i>
                    </button>

                    <a href="" class="btn btn-danger">
                        <i class="fa fa-close"><b>&nbsp;Cancelar</b></i>
                    </a>
                </div>

            </form>
        </div>
    </div>
    <style>
        .box-body {
            margin-left: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</div>

<script>
    $(document).ready(function() {
        get_datos_empresa();
        $('#nombre_empresa').focus();
    });
</script>

<?php include("incluids/inferior.php"); ?>