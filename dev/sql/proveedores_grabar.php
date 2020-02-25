<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	
	$grabar			= $_POST[grabar];
	$oculto			= $_POST[oculto];
	
	if($oculto=="41846788"){
		$txtRuc			= $_POST[txtRuc];
		$txtRazonSocial	= strtoupper($_POST[txtRazonSocial]);
		$txtDireccion	= strtoupper($_POST[txtDireccion]);
		$txtDistrito	= strtoupper($_POST[txtDistrito]);
		$txtTelefono	= $_POST[txtTelefono];
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_proveedores set ruc='$txtRuc', razon_social='$txtRazonSocial', direccion='$txtDireccion', distrito='$txtDistrito', telefono='$txtTelefono', user_modifica='$vgCodUser', fec_modifica=now() where id_proveedor='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_proveedores(ruc, razon_social, direccion, distrito, telefono, user_registro, fec_registro) values('$txtRuc', '$txtRazonSocial', '$txtDireccion', '$txtDistrito', '$txtTelefono', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_proveedores0.php"</script>';
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