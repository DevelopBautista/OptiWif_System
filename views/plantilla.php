<?php
include_once("incluids/superior.php");
require_once("../models/modelo_contador_infoBox.php"); // Ajusta si tu modelo tiene otro nombre

$MC = new modelo_contador_infoBox();
$total_clientes = $MC->contar_clientes();
$total_servicios = $MC->contar_servicos();
$total_pagos = $MC->contar_pagos_realizados();
$total_facturas = $MC->contar_facturas_realizadas();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row" id="contenido_principal">

            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua">
                            <i class="ion ion-ios-people-outline"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Clientes</span>
                            <span class="info-box-number"><?php echo $total_clientes; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red">
                            <i class="ion ion-ios-gear-outline"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Servicios</span>
                            <span class="info-box-number"><?php echo $total_servicios; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Fix for small devices -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pagos realizados</span>
                            <span class="info-box-number"><?php echo $total_pagos; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Facturas</span>
                            <span class="info-box-number"><?php echo $total_facturas; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- Aquí puedes agregar una gráfica o tabla adicional -->
            <!-- Ejemplo:
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Reporte Mensual</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="grafico_mensual"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            -->

        </div>
    </section>
</div>

<!-- /.content-wrapper -->

<?php include_once("incluids/inferior.php"); ?>