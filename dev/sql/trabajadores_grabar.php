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
		$txtPaterno		= strtoupper($_POST[txtPaterno]);
		$txtMaterno		= strtoupper($_POST[txtMaterno]);
		$txtNombres		= strtoupper($_POST[txtNombres]);
		$cboTipoDoc		= $_POST[cboTipoDoc];
		$txtNroDoc		= $_POST[txtNroDoc];
		$cboSucursal	= $_POST[cboSucursal];
		$txtFecNac		= $_POST[txtFecNac];
		$txtDireccion	= strtoupper($_POST[txtDireccion]);
		$txtDistrito	= strtoupper($_POST[txtDistrito]);
		$txtTelefono	= $_POST[txtTelefono];
		$txtCorreo		= strtolower($_POST[txtCorreo]);
		$cboCargo		= $_POST[cboCargo];
		$cboEstado		= $_POST[cboEstado];
		$txtUsuario		= strtoupper($_POST[txtUsuario]);
		$txtClave		= strtoupper($_POST[txtClave]);
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_trabajadores set id_sucursal='$cboSucursal', paterno='$txtPaterno', materno='$txtMaterno', nombres='$txtNombres', fecha_nac='$txtFecNac', id_tipodoc='$cboTipoDoc', nro_doc='$txtNroDoc', direccion='$txtDireccion', distrito='$txtDistrito', telefono='$txtTelefono', correo='$txtCorreo', id_cargo='$cboCargo', estado='$cboEstado', usuario='$txtUsuario', clave='$txtClave', user_modifica='$vgCodUser', fec_modifica=now() where id_trabajador='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_trabajadores(id_empresa, id_sucursal, paterno, materno, nombres, fecha_nac, id_tipodoc, nro_doc, direccion, distrito, telefono, correo, id_cargo, estado, usuario, clave, user_registro, fec_registro) values('$vgCodEmp', '$cboSucursal', '$txtPaterno', '$txtMaterno', '$txtNombres', '$txtFecNac', '$cboTipoDoc', '$txtNroDoc', '$txtDireccion', '$txtDistrito', '$txtTelefono', '$txtCorreo', '$cboCargo', '$cboEstado', '$txtUsuario', '$txtClave', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_trabajadores0.php"</script>';
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