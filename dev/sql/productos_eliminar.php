<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];

	$tipo	   = $_GET[tipo];
	$nomtipo   = $_GET[nomtipo];
	$id	= $_GET[id];
	
	if($id > 0){
		$consulta = mysqli_query($con, "delete from man_productos where id_producto='$id'");
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