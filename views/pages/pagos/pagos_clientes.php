<?php include("incluids/superior.php"); ?>
<div class="col-md-12">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Gestionar Pagos</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <br>
        <div class="box-body">
            <table id="tabla_pagos" class="display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Cliente</th>
                        <th>Plan Contratado</th>
                        <th>Cuota Mensual</th>
                        <th>Mensualidad Pendiente</th>
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
                <form id="frm_pago" method="POST">
                    <input type="hidden" id="id_mensualidad" name="id_mensualidad">
                    <input type="hidden" id="estado_pago" name="estado_pago">

                    <label>Cliente</label>
                    <input type="text" id="cliente" name="cliente" class="form-control" disabled><br>

                    <label>Mensualidad</label>
                    <input type="text" id="cuotas_mensual" name="cuotas_mensual" class="form-control" disabled><br>

                    <label>Mora</label>
                    <!-- Input visible pero deshabilitado -->
                    <input type="text" id="mora_mostrar" class="form-control" disabled><br>
                    <!-- Input oculto que se enviará al backend -->
                    <input type="hidden" id="mora" name="mora">

                    <label>Fecha de pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" value="<?= date('Y-m-d') ?>" disabled><br>

                    <label>Método de pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                    </select><br>

                    <label>Referencia de pago (si aplica)</label>
                    <input type="text" id="referencia_pago" name="referencia_pago" class="form-control"><br>

                    <label>Observaciones</label>
                    <textarea id="observaciones" name="observaciones" class="form-control" rows="2"></textarea><br>

                    <label>Total a pagar</label>
                    <input type="text" id="monto_total_pagar" name="monto_total_pagar" class="form-control" disabled><br>
                    <input type="hidden" id="monto_total" name="monto_total"><!-- importante -->

                    <label>Efectivo recibido</label>
                    <input type="number" id="efectivo" name="efectivo" class="form-control" placeholder="Entrada de dinero"><br>

                    <label>Devuelta</label>
                    <input type="text" id="devuelta" name="devuelta" class="form-control" readonly><br>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="registrar_pagos()" class="btn btn-warning">
                    <i class="fa fa-check"><b>&nbsp;Cobrar</b></i>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"><b>&nbsp;Cancelar</b></i>
                </button>
            </div>
        </div>
    </div>
</div>


<!--====================================-->
<script>
    $(document).ready(function() {
        $(".metodo_pago").select2();
        listar_pagos_ajax();
        calcular_devuelta();
    });
</script>


<script>
    //----------- Calcular Devuelta ---------------------
    function calcular_devuelta() {
        const efectivoInput = document.getElementById("efectivo");
        if (efectivoInput) {
            efectivoInput.addEventListener("input", function() {
                const efectivo = parseFloat(this.value) || 0;
                const total = parseFloat(document.getElementById("monto_total").value) || 0;
                const devuelta = efectivo - total;

                document.getElementById("devuelta").value = devuelta >= 0 ? devuelta.toFixed(2) : "0.00";
            });
        }

    }
</script>
<?php include("incluids/inferior.php"); ?>