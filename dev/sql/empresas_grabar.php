<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	
	$grabar			= $_POST[grabar];
	$oculto			= $_POST[oculto];
	
	if($oculto=="41846788"){
		$txtRuc			= $_POST[txtRuc];
		$txtNombre		= strtoupper($_POST[txtNombre]);
		$txtDireccion	= strtoupper($_POST[txtDireccion]);
		$txtDistrito	= strtoupper($_POST[txtDistrito]);
		$txtTelefono	= $_POST[txtTelefono];
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_empresas set ruc='$txtRuc', nombre='$txtNombre', direccion='$txtDireccion', distrito='$txtDistrito', telefono='$txtTelefono', user_modifica='$vgCodUser', fec_modifica=now() where id_empresa='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_empresas(ruc, nombre, direccion, distrito, telefono, user_registro, fec_registro) values('$txtRuc', '$txtNombre', '$txtDireccion', '$txtDistrito', '$txtTelefono', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_empresas0.php"</script>';
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