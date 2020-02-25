<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];

	$grabar			= $_POST[grabar];
	$oculto			= $_POST[oculto];
	
	if($oculto=="41846788"){
		$txtNombre		  = strtoupper($_POST[txtNombre]);
		$txtDireccion	  = strtoupper($_POST[txtDireccion]);
		$txtDistrito	  = strtoupper($_POST[txtDistrito]);
		$txtTelefono	  = $_POST[txtTelefono];
		$txtAdministrador = $_POST[txtAdministrador];
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_sucursales set nombre='$txtNombre', direccion='$txtDireccion', distrito='$txtDistrito', telefono='$txtTelefono', id_administrador='$txtAdministrador', user_modifica='$vgCodUser', fec_modifica=now() where id_sucursal='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_sucursales(id_empresa, nombre, direccion, distrito, telefono, id_administrador, user_registro, fec_registro) values('$vgCodEmp', '$txtNombre', '$txtDireccion', '$txtDistrito', '$txtTelefono', '$txtAdministrador', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_sucursales0.php"</script>';
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