<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        Todo lo que quieras
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 By DeveloperBautista.</strong> All rights reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Inicio</h3>
            <p>Contenido de la pestaña Inicio</p>
        </div>
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <h3 class="control-sidebar-heading">Configuración</h3>
            <p>Contenido de la pestaña Configuración</p>
        </div>
    </div>
</aside>

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<!--sweetalert2.js-->
<script src="<?php echo SERVERURL; ?>template/plugins/sweetalert2/sweetalert2.js"></script>


<!-- jQuery 3 -->
<script src="<?php echo SERVERURL; ?>template/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SERVERURL; ?>template/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo SERVERURL; ?>template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo SERVERURL; ?>template/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo SERVERURL; ?>template/dist/js/adminlte.min.js"></script>
<!--DataTables js-->
<script src="<?php echo SERVERURL; ?>template/plugins/dataTables/datatables.js"></script>
<script src="<?php echo SERVERURL; ?>template/plugins/dataTables/datatables.min.js"></script>
<!--select2-->
<script src="<?php echo SERVERURL; ?>template/plugins/select2/select2.min.js"></script>
<!--script personal para iconos-->
<script src="https://kit.fontawesome.com/b6da622e9f.js" crossorigin="anonymous"></script>

<!--js para los btn del datatable-->
<script src="<?php echo SERVERURL; ?>template/dist/js/buttons.html5.min.js"></script>
<script src="<?php echo SERVERURL; ?>template/dist/js/buttons.print.min.js"></script>
<script src="<?php echo SERVERURL; ?>template/dist/js/dataTables.buttons.min.js"></script>

<!--este css sirve para que los <a> no esten sin bg en modo movil-->
<style>
    @media (max-width: 767px) {
        .user-footer .btn {
            display: inline-block;
            width: 100%;
            margin-bottom: 5px;
            text-align: center;
            color: #fff !important;
            font-weight: bold;
        }

        .user-footer .btn-primary {
            background-color: #3c8dbc !important;
            border-color: #367fa9 !important;
        }

        .user-footer .btn-danger {
            background-color: #dd4b39 !important;
            border-color: #d73925 !important;
        }

        .user-footer .btn i {
            margin-right: 5px;
        }
    }
</style>


<!--funcion para cargar los menues-->
<script>
    function loaderPages(contenedor, contenido) {
        $("#" + contenedor).load(contenido);
    }
</script>

</body>

</html>