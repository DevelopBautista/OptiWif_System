<?php include("incluids/superior.php"); ?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Pagos Realizados</h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <br>

        <div class="box-body">
            <table id="tabla_pagos_realizados" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Cliente</th>
                        <th>Cuota Mensual</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>

</div>
<script>
    $(document).ready(function() {
        listar_pagos_realizados_ajax();
    });
</script>
<?php include("incluids/inferior.php"); ?>