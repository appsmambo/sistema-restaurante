<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	//Valida que los campos de usuario y contraseña tengan datos para su validacion
	@mysqli_query($con, "SET NAMES 'utf8'");
	$user = strtoupper(mysqli_real_escape_string($con, $_POST['user']));
	$pass = strtoupper(mysqli_real_escape_string($con, $_POST['pass']));
	if ($user == null || $pass == null){
		echo '<span>Por favor complete todos los campos.</span>';
	}else{
		$consulta = mysqli_query($con, "select a.id_empresa, a.id_sucursal, a.id_trabajador, a.paterno, a.materno, a.nombres, b.nombre as cargo, DATE_FORMAT(a.fec_registro, '%d/%m/%Y') as fec_registro from man_trabajadores as a left join man_cargos as b on a.id_cargo = b.id_cargo where estado=0 and usuario = '$user' AND clave = '$pass'");
		if (mysqli_num_rows($consulta) > 0){
			$row = mysqli_fetch_array($consulta);
			$_SESSION["user"] 	  	  = strtoupper(trim($user));
			$_SESSION["usuario"] 	  = ucwords(strtolower(trim($row[nombres]))).' '.ucwords(strtolower(trim($row[paterno])));
			$_SESSION["cargo"]   	  = strtoupper(trim($row[cargo]));
			$_SESSION["fec_registro"] = $row[fec_registro];
			$_SESSION["vgCodEmp"] 	  = 1;
			$_SESSION["vgCodSuc"] 	  = 1;
			$_SESSION["iniciado"]	  = 1;
			$_SESSION["foto"]	  	  = strtoupper(trim($user));


			$consulta3 = mysqli_query($con, "select panexo1 as flujo from man_parametros where pcodtabla='FLUJO'");
			if (mysqli_num_rows($consulta3) > 0){
				$row3 = mysqli_fetch_array($consulta3);
				$flujo = $row3["flujo"];

				if($flujo==1){
					$_SESSION["flujo"]	  = 1;
					$_SESSION["nomflujo"] = "MOZO - COCINA - CAJA";
				}

				if($flujo==2){
					$_SESSION["flujo"]	  = 1;
					$_SESSION["nomflujo"] = "MOZO - COCINA - CAJA";

					setlocale(LC_TIME, 'spanish');
					$vlSesion = strftime("%d%m%Y%H%M%S");
					$vlFecha_formateada = strftime("%Y-%m-%d");
					$diaDeLaSemana = utf8_encode(strftime("%A"));
					$vlDiaa = strftime("%d");
					$vlMess = strftime("%m");

					if($diaDeLaSemana == "sábado" || $diaDeLaSemana == "domingo"){
						$_SESSION["flujo"] = 2;
						$_SESSION["nomflujo"] = "MOZO - CAJA - COCINA";
					}else{
						$consulta3 = mysqli_query($con, "select id from man_feriados where dia='$vlDiaa' and mes='$vlMess'");
						if (mysqli_num_rows($consulta3) > 0){
							$_SESSION["flujo"] = 2;
							$_SESSION["nomflujo"] = "MOZO - CAJA - COCINA";
						}
					}
				}

				if($_SESSION["flujo"]!=""){
					$nombre_foto = "../dist/img/user/".$_SESSION["foto"].".jpg";
					if(!file_exists($nombre_foto)) {
						$_SESSION["foto"]	  = "SINFOTO";
					}

					if($_SESSION["cargo"] == "GERENTE GENERAL" || $_SESSION["cargo"] == "ADMINISTRADOR" || $_SESSION["cargo"] == "SISTEMAS"){
						echo '<script>location.href = "php/home.php"</script>';
					}
					if($_SESSION["cargo"] == "ANFITRIONA"){
						echo '<script>location.href = "php/man_clientes0.php"</script>';
					}
					if($_SESSION["cargo"] == "MESERO" || $_SESSION["cargo"] == "JEFE DE COCINA" || $_SESSION["cargo"] == "JEFE DE SALA"){
						echo '<script>location.href = "php/pro_pedidos0.php"</script>';
					}
					if($_SESSION["cargo"] == "COCINERO" || $_SESSION["cargo"] == "JEFE DE SALA"){
						echo '<script>location.href = "php/pro_cocina0.php"</script>';
					}
					if($_SESSION["cargo"] == "BARMAN"){
						echo '<script>location.href = "php/pro_bar0.php"</script>';
					}
					if($_SESSION["cargo"] == "CAJERO"){
						echo '<script>location.href = "php/pro_caja0.php"</script>';
					}
				}else{
					echo '<span>El flujo no esta configurado, Comuniquese con el equipo de soporte.</span>';
				}
			}else{
				echo '<span>El flujo no esta configurado, Comuniquese con el equipo de soporte.</span>';
			}
		}else{
			echo '<span>El usuario y/o clave son incorrectas, vuelva a intentarlo.</span>';
		}
	}   
?>