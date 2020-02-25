<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp  = $_SESSION["vgCodEmp"];
	$vgCodSuc  = $_SESSION["vgCodSuc"];

	$grabar	= $_POST[grabar];
	$oculto	= $_POST[oculto];
	
	if($oculto=="41846788"){
		$txtNombre = $_POST[txtNombre];
	
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_mesas set id_mesa='$txtNombre', user_modifica='$vgCodUser', fec_modifica=now() where id='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_mesas(id_empresa, id_sucursal, id_mesa, user_registro, fec_registro) values('$vgCodEmp', '$vgCodSuc', '$txtNombre', '$vgCodUser', now())");
		}
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