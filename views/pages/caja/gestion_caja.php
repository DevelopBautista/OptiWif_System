<?php include("incluids/superior.php"); ?>
<div class="col-md-9">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Gestión de Caja</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <!-- Caja Cierre -->
                <div class="col-md-6">
                    <p>
                    <h4>Total registrado en el sistema:</h4> <strong id="total_sistema">0.00</strong></p>
                    <div>
                        <label>Total contado en caja:</label><br>
                        <input class="form-control" type="number" id="total_contado" step="0.01" class="form-control">
                    </div>
                    <div>
                        <label>Observaciones:</label><br>
                        <textarea class="form-control" id="observaciones" rows="3" class="form-control"></textarea>
                    </div>
                    <br>
                    <button id="btn_cerrar_caja" class="btn btn-success" onclick="registrarCierre()">
                        <i class="fa fa-check"></i> <b>&nbsp;Cerrar Caja</button>
                </div>

                <!-- Caja Apertura -->
                <div class="col-md-6">
                    <form method="POST" autocomplete="off" id="frm_caja" onsubmit="return false">
                        <div class="form-group">
                            <label for="monto_inicial">Monto Inicial</label>
                            <input type="number" step="0.01" min="0" id="monto_inicial" class="form-control" name="monto_inicial" placeholder="Monto Inicial">
                        </div>
                        <div class="form-group">
                            <button type="button" id="btn_abrir_caja" class="btn btn-primary" onclick="registrarApertura()">
                                <i class="fa fa-check"></i> <b>&nbsp;Abrir Caja</b>
                            </button>
                            <a href="" class="btn btn-danger">
                                <i class="fa fa-close"></i> <b>&nbsp;Cancelar</b>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include("incluids/inferior.php"); ?>

<script>
    $(document).ready(function() {
        verificarEstadoCaja(); 
        obtenerTotalSistema();
    });
</script>