<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	
	$grabar	 = $_POST[grabar];
	$oculto	 = $_POST[oculto];
	$tipo	 = $_POST[tipo];
	$nomtipo = $_POST[nomtipo];
	
	if($oculto=="41846788"){
		$txtNombre = strtoupper($_POST[txtNombre]);
		$txtPrecio = $_POST[txtPrecio];
	
		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		@mysqli_query($con, "SET NAMES 'utf8'");
		if($grabar>0){
			$consulta = mysqli_query($con, "update man_productos set nombre='$txtNombre', precio='$txtPrecio', user_modifica='$vgCodUser', fec_modifica=now() where id_producto='$grabar'");
		}else{
			$consulta = mysqli_query($con, "insert into man_productos(id_empresa, id_sucursal, id_tipoproducto, nombre, precio, user_registro, fec_registro) values('$vgCodEmp', '$vgCodSuc', '$tipo', '$txtNombre', '$txtPrecio', '$vgCodUser', now())");
		}
		if($consulta){
			echo '<script>location.href = "../php/man_productos1.php?tipo='.$tipo.'&nomtipo='.$nomtipo.'"</script>';
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