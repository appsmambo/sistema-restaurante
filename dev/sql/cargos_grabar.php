<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	
	$grabar	= $_POST[grabar];
	$oculto	= $_POST[oculto];
	
	if($oculto=="41846788"){
		$txtNombre = strtoupper($_POST[txtNombre]);
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_cargos set nombre='$txtNombre', user_modifica='$vgCodUser', fec_modifica=now() where id_cargo='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_cargos(nombre, user_registro, fec_registro) values('$txtNombre', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_cargos0.php"</script>';
			exit();
		}else{
			echo '<script>alert("Problemas con el servidor, Vuelva a intentarlo mas tarde.");</script>';
			echo '<script>location.href = "../php/cerrar_sesion.php"</script>';
			exit();
		}
	}else{
		echo '<script>alert("Enlace roto.");</script>';
		echo '<script>location.href = "../php/cerrar_sesion.php"</script>';
		exit();
	}
?>