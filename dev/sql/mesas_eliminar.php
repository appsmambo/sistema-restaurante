<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp  = $_SESSION["vgCodEmp"];
	$vgCodSuc  = $_SESSION["vgCodSuc"];

	$id	= $_GET[id];
	
	if($id > 0){
		$consulta = mysqli_query($con, "delete from man_mesas where id='$id'");
		if($consulta){
			echo '<script>location.href = "../php/man_mesas0.php"</script>';
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