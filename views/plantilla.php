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
            <section class="content">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Clientes</span>
                                <span class="info-box-number"><?php echo $total_clientes; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Servicios</span>
                                <span class="info-box-number"><?php echo $total_servicios; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa-solid fa-hand-holding-dollar"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pagos realizados</span>
                                <span class="info-box-number"><?php echo $total_pagos; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa-solid fa-file-invoice-dollar"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">facturas</span>
                                <span class="info-box-number"><?php echo $total_facturas; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

            </section>
        </div>
        <!--aqui va la graficas de reporte mensual-->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once("incluids/inferior.php"); ?>