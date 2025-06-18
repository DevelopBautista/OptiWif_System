<?php include("incluids/superior.php"); ?>
<div class="col-md-7">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Apertura de Caja</h3>
        </div>
        <div class="box-body">
            <form method="POST" autocomplete="off" id="frm_caja" onsubmit="return false">
                <!--f01-->
                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="number" step="0.01" min="0" id="monto_inicial" class="form-control" name="monto_inicial"
                            placeholder="Monto Inicial">
                    </div>
                </div>
                <!--f02-->
                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="registrarApertura()"><i class="fa  fa-check"><b>&nbsp;Abrir Caja</b></i></button>
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

        $('#monto_inicial').focus();
    });
</script>

<?php include("incluids/inferior.php"); ?>