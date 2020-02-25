<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];

	$vlMesa	   = $_POST['variable'];
	$vlTipoDoc = $_POST["tipodoc"];
//	$vlNroDoc  = explode("-", $vlTipoDoc);
//	$serie  = $vlNroDoc[0];
//	$numero = $vlNroDoc[1]+1;DDD

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	$vlSqlCad = "update man_correlativos set numero=numero+1 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_docuconta='$vlTipoDoc'";
	mysqli_query($con, $vlSqlCad);

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	$vlSqlCad = "update man_mesas set ocupado=0, user_ocupado='', fec_ocupado='0000-00-00' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
	mysqli_query($con, $vlSqlCad);

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	$vlSqlCad = "delete from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
	mysqli_query($con, $vlSqlCad);

	echo "<script language='JavaScript'> location.href='pro_caja0.php'; </script>";
?>