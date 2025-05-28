<?php include("incluids/superior.php"); ?>
<div class="col-md-10">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Crear Plan</h3>
        </div>
        <br>

        <div class="box-body">
            <form method="POST" autocomplete="off" id="frm" onsubmit="return false">
                <!--f01-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" name="nombrePlan"
                            placeholder="Nombre del Plan" id="nombrePlan">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="descripcionPlan" name="descripcionPlan" placeholder="Descripcion del Plan">
                    </div>
                </div>
                <!--f02-->
                <div class="row">
                    <div class="form-group col-md-5">
                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Costo del plan">

                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="crear_plan()"><i class="fa  fa-check"><b>&nbsp;Crear Plan</b></i></button>
                    <a href="" class="btn btn-danger"><i class="fa fa-close"><b>&nbsp;Cancelar</b></i></a>
                </div>
            </form>
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

        $('#nombres').focus();
    });
</script>

<?php include("incluids/inferior.php"); ?>