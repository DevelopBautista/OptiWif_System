<?php include("incluids/superior.php"); ?>
<div class="col-md-9">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Gestión de Caja</h3>
        </div>

        <div class="box-body">
            <div class="row">

                <div class="col-md-6">
                    <p>
                    <h4>Total registrado en el sistema:</h4> <strong id="total_sistema">0.00</strong></p>

                    <div>
                        <label for="total_contado">Total contado en caja:</label><br>
                        <input type="number" id="total_contado" step="0.01" min="0" class="form-control" required>
                    </div>

                    <div>
                        <label for="observaciones">Observaciones:</label><br>
                        <textarea id="observaciones" rows="3" class="form-control"></textarea>
                    </div>

                    <br>
                    <h5 class="card-title"><i class="fa fa-cash-register"></i> <b>Cierre de Caja Diario</b></h5>
                    <button id="btn_cerrar_caja" class="btn btn-success" onclick="registrarCierre()">
                        <i class="fa fa-check"></i> <b>&nbsp;Cerrar Caja</b>
                    </button>
                </div>

                <div class="col-md-6">
                    <form method="POST" autocomplete="off" id="frm_caja" onsubmit="return false">
                        <div class="form-group">
                            <label for="monto_inicial">Monto Inicial</label>
                            <input type="number" step="0.01" min="0" id="monto_inicial" class="form-control" name="monto_inicial" placeholder="Monto Inicial" required>
                        </div>

                        <div class="form-group">
                            <button type="button" id="btn_abrir_caja" class="btn btn-primary" onclick="registrarApertura()">
                                <i class="fa fa-check"></i> <b>&nbsp;Abrir Caja</b>
                            </button>

                            <button type="reset" class="btn btn-danger">
                                <i class="fa fa-close"></i> <b>&nbsp;Cancelar</b>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="card shadow p-3 mb-4 bg-white rounded border">
                        <div class="card-body">
                            <div class="form-group mt-3">
                                <label for="fecha_busqueda"><i class="fa fa-calendar"></i> <b>Fecha del reporte</b></label>
                                <input type="date" id="fecha_busqueda" class="form-control">
                            </div>

                            <div class="form-group mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-primary w-100 me-2" onclick="buscarCierrePorFecha()">
                                    <i class="fa fa-search"></i> <b>&nbsp;Ver Detalle</b>
                                </button>

                                <button type="button" class="btn btn-success w-100 ms-2" onclick="generarReporte()">
                                    <i class="fa fa-file-pdf"></i> <b>&nbsp;Generar PDF</b>
                                </button>
                            </div>
                        </div>
                    </div>
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