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
            <table id="tablaFacturas" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Numero de Factura</th>
                        <th>Cliente</th>
                        <th>Fecha de Emision</th>
                        <th>Acciones</th>
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
        listar_facturas_ajax();
    });
</script>
<?php include("incluids/inferior.php"); ?>