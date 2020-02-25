<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	
	$grabar			= $_POST[grabar];
	$oculto			= $_POST[oculto];
	
	if($oculto=="41846788"){
		$cboTipoDoc		= $_POST[cboTipoDoc];
		$txtNroDoc		= $_POST[txtNroDoc];
		$txtNombre		= strtoupper($_POST[txtNombre]);
		$txtFecNac		= $_POST[txtFecNac];
		$txtDireccion	= strtoupper($_POST[txtDireccion]);
		$txtDistrito	= strtoupper($_POST[txtDistrito]);
		$txtTelefono	= $_POST[txtTelefono];
		$txtCorreo		= strtolower($_POST[txtCorreo]);
		$cboRecomendado	= strtolower($_POST[cboRecomendado]);
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_clientes set id_tipodoc='$cboTipoDoc', nro_doc='$txtNroDoc', nombre='$txtNombre', fecha_nac='$txtFecNac', direccion='$txtDireccion',  distrito='$txtDistrito', telefono='$txtTelefono', correo='$txtCorreo', id_recomendado='$cboRecomendado', user_modifica='$vgCodUser', fec_modifica=now() where id_cliente='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_clientes(id_tipodoc, nro_doc, nombre, fecha_nac, direccion, distrito, telefono, correo, id_recomendado, user_registro, fec_registro) values('$cboTipoDoc', '$txtNroDoc', '$txtNombre', '$txtFecNac', '$txtDireccion', '$txtDistrito', '$txtTelefono', '$txtCorreo', '$cboRecomendado', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_clientes0.php"</script>';
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