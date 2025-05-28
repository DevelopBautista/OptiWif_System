<?php
session_start();
if (isset($_SESSION['id_user'])) {
	header('location: ../plantilla.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>OptiWif System Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100" style="min-height: 100vh;">
			<div class="row w-100 m-0">

				<!-- Columna de la imagen -->
				<div class="col-md-6 d-flex align-items-center justify-content-center p-0">
					<div class="wrap-login100 p-0" style="width: 100%; height: 100%; overflow: hidden;">
						<img src="images/logoNew.svg" alt="Imagen Login" style="width: 100%; height: 100%; object-fit: cover;">
					</div>
				</div>

				<!-- Columna del formulario -->
				<div class="col-md-6 d-flex align-items-center justify-content-center">
					<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
						<span class="login100-form-title p-b-49">
							Login
						</span>

						<div class="wrap-input100 validate-input m-b-23" data-validate="Username is required">
							<span class="label-input100">Usuario</span>
							<input class="input100" type="text" name="username" placeholder="Escriba el Usuario" id="txt_usu" autocomplete="new-password">
							<span class="focus-input100" data-symbol="&#xf206;"></span>
						</div>

						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<span class="label-input100">Contrase&ntilde;a</span>
							<input class="input100" type="password" name="pass" placeholder="Escriba la contrase&ntilde;a" id="txt_con">
							<span class="focus-input100" data-symbol="&#xf190;"></span>
						</div>
						<br>

						<div >
							<div>
								<div></div>
								<button class="btn btn-primary btn-lg btn-block" onclick="verificar_usuario()">
									Iniciar Sesion
								</button>
							</div>
						</div><br>
					</div>
				</div>

			</div>
		</div>

	</div>


	<div id="dropDownSelect1"></div>

	<!--===============================================================================================-->
	<script src="vendor/sweetalert2/sweetalert2.js"></script>
	<!--===============================================================================================-->

	<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script src="../../ajax/usuario.js"></script>
</body>

<script>
	txt_usu.focus();
</script>

</html>