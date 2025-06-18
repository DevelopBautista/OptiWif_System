<?php include("incluids/superior.php"); ?>
<div class="col-md-9">
    <div class="box box-info box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Gestion de Caja</h3>
        </div>
        <div class="box-body">
            <p>Total registrado en el sistema: <strong id="total_sistema">0.00</strong></p>

            <div>
                <label>Total contado en caja:</label><br>
                <input type="number" id="total_contado" step="0.01">
            </div>

            <div>
                <label>Observaciones:</label><br>
                <textarea id="observaciones" rows="3" cols="40"></textarea>
            </div>

            <br>
            <button onclick="registrarCierre()">Cerrar Caja</button>
        </div>

    </div>
</div>

<?php include("incluids/inferior.php"); ?>
<script>
    $(document).ready(function() { //funcion para obtener los pagos realizados del dia
        obtenerTotalSistema();
    });
</script>