<?php
include_once("../config/config.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    header('location: ../views/login/login.php');
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo COMAPANY; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--DataTables css-->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/plugins/dataTables/datatables.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/plugins/dataTables/datatables.min.css">
    <!--bootstrap css-->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/dist/css/AdminLTE.min.css">
    <!--skin-->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/dist/css/skins/skin-blue.min.css">
    <!--select2-->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>template/plugins/select2/select2.min.css">

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>OW</b>S</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>OptiWiF</b>System</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="<?php echo SERVERURL; ?>template/dist/img/salir.png" class="user-image" alt="User Image">
                                <span class="hidden-xs">Salir del sistema</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo SERVERURL; ?>template/dist/img/logoNew.svg" class="img-circle" alt="User Image">
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn  btn-primary">Cancelar</a>
                                    </div>
                                    <div class="pull-right">
                                        <!--aqui deberia ir la url base-->
                                        <a href="#" onclick="cerrar_sesion()" class="btn  btn-danger">Cerrar Sesion</a>

                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo SERVERURL; ?>template/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $_SESSION['user']; ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Activo</a>
                    </div>
                </div>
                <br>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">
                        <h4>Menu</h4>
                    </li>
                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu clientes -->
                        <li class="treeview">
                            <a href=""><i class="fa fa-users"></i> <span>Clientes</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/clientes/registrar_cliente.php')">Nuevo Cliente</a></li>
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/clientes/listar_clientes.php')">Clientes</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu servios -->
                        <li class="treeview">
                            <a href=""><i class="fa-solid fa-paste"></i> <span>Servicios</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/servicios/crear_servicio.php')">Nuevo Servicio</a></li>
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/servicios/listar_servicio.php')">Servicios</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu planes -->
                        <li class="treeview">
                            <a href=""><i class="fa-solid fa-table-list"></i></i> <span>Planes</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/planes/crear_plan.php')">Nuevo Plan</a></li>
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/planes/listar_planes.php')">Planes</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!-- menu pagos pendientes -->
                    <li class="treeview">
                        <a href=""><i class="fa fa-money-bill"></i> <span>Pagos</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#" onclick="loaderPages('contenido_principal','pages/pagos/pagos_clientes.php')">Mensualidades</a></li>
                            <li><a href="#" onclick="loaderPages('contenido_principal','pages/pagos/pagos_realizados.php')">Pagos Realizados</a></li>

                        </ul>
                    </li>
                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu factuas -->
                        <li class="treeview">
                            <a href=""><i class="fa-solid fa-file-invoice"></i> <span>facturas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/facturas/listar_facturas.php')">Facturas Realizadas</a></li>


                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu usuarios -->
                        <li class="treeview">
                            <a href=""><i class="fa fa-user-secret"></i> <span>Usuarios</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/usuarios/registrar_usuario.php')">Nuevo Usuario</a></li>
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/usuarios/listar_usuarios.php')">Usuarios</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu caja -->
                        <li class="treeview">
                            <a href=""><i class="fa-solid fa-cash-register"></i> <span>Caja</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/caja/apertura_caja.php')">Abrir Caja</a></li>
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/caja/cierre_caja.php')">Cerrar Caja</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 1): ?>
                        <!-- menu Configuraciones -->
                        <li class="treeview">
                            <a href=""><i class="fa fa-gears"></i> <span>Configuraciones</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#" onclick="loaderPages('contenido_principal','pages/empresa/datos_empresa.php')">Configuracion</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <!--aqui  van scripts ajax-->
        <script src="<?php echo SERVERURL ?>ajax/main.js"></script>