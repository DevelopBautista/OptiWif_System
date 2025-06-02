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
                <form action="procesar_pago.php" method="POST">
                    <label>Cliente:</label><br>
                    <select name="id_contrato" required>
                        <!-- Opción cargada dinámicamente desde la base de datos -->
                        <option value="1">Juan Pérez - Plan 10MB</option>
                        <option value="2">Ana Gómez - Plan 20MB</option>
                    </select><br><br>

                    <label>Mensualidad pendiente:</label><br>
                    <select name="id_mensualidad" required>
                        <!-- Mostrar solo mensualidades con estado = pendiente -->
                        <option value="101">Febrero 2025 - $20.00</option>
                        <option value="102">Marzo 2025 - $20.00</option>
                    </select><br><br>

                    <label>Monto pagado:</label><br>
                    <input type="number" step="0.01" name="monto" required><br><br>

                    <label>Método de pago:</label><br>
                    <select name="metodo_pago" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Pago Móvil">Pago Móvil</option>
                    </select><br><br>

                    <label>Referencia del pago (si aplica):</label><br>
                    <input type="text" name="referencia_pago"><br><br>

                    <label>Observaciones:</label><br>
                    <textarea name="observaciones" rows="3"></textarea><br><br>

                    <button type="submit">Registrar Pago</button>
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