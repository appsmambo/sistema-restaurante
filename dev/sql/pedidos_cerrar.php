<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];
	$vgFlujo  = $_SESSION["flujo"];

	$variable = $_POST['variable'];
	$vlMesa	  = $_POST["mesa"];

	
	if($vgFlujo==1){
		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		$vlSqlCad = "update tmp_pedidos set estado=3, user_cierre='$vgCodUser', fec_cierre=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=2";
		mysqli_query($con, $vlSqlCad);
	
		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		$vlSqlCad = "update man_mesas set ocupado=4 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and ocupado=3";
		mysqli_query($con, $vlSqlCad);
	}

	if($vgFlujo==2){
		//-Actualizando el estado de la mesa------------------------------------
		$vlSqlCad = "update man_mesas set ocupado=0, user_ocupado='', fec_ocupado='0000-00-00' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
		mysqli_query($con, $vlSqlCad);
		//----------------------------------------------------------------------
	
		
		//-Eliminando Carrito compra Temporal-----------------------------------
		$vlSqlCad = "delete from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
		mysqli_query($con, $vlSqlCad);
		//----------------------------------------------------------------------
	}

	if($vgFlujo==1){
		echo "<script language='JavaScript'> location.href='pro_pedidos1.php?m=$vlMesa'; </script>";
	}else{
		echo "<script language='JavaScript'> location.href='pro_pedidos0.php'; </script>";
	}
?>