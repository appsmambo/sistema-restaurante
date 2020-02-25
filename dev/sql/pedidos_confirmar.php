<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];
	$vgFlujo   = $_SESSION["flujo"];

	$variable = $_POST['variable'];
	$vlMesa	  = $_POST["mesa"];

	
	//-DIA DE SEMANA--------------
	if($vgFlujo==1){
		if($variable==0){
			$ocupa = 1;
		}
		if($variable==1){
			$ocupa = 1;
		}
		if($variable==2){
			$ocupa = 2;
		}
		if($variable==3){
			$ocupa = 2;
		}

		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		$vlSqlCad = "update tmp_pedidos set estado=1, user_confirma='$vgCodUser', fec_confirma=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=0";
		mysqli_query($con, $vlSqlCad);
	
		if($variable==0){	
			@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
			$vlSqlCad = "update man_mesas set ocupado=1, user_ocupado='$vgCodUser', fec_ocupado=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and ocupado=0";
		}else{
			@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
			$vlSqlCad = "update man_mesas set ocupado='$ocupa' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and (ocupado>0 and ocupado<4)";
		}
		mysqli_query($con, $vlSqlCad);
	}
	//----------------------------

	//-SABADO, DOMINGO, FERIADOS--
	if($vgFlujo==2){
		if($variable==0){
			$ocupa = 4;
		}
		if($variable==1){
			$ocupa = 1;
		}
		if($variable==2){
			$ocupa = 2;
		}
		if($variable==3){
			$ocupa = 2;
		}

		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		$vlSqlCad = "update tmp_pedidos set estado=3, user_confirma='$vgCodUser', fec_confirma=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=0";
		mysqli_query($con, $vlSqlCad);
	
		if($variable==0){	
			@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
			$vlSqlCad = "update man_mesas set ocupado=4, user_ocupado='$vgCodUser', fec_ocupado=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and ocupado=0";
		}else{
			@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
			$vlSqlCad = "update man_mesas set ocupado=4 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and (ocupado>0 and ocupado<4)";
		}
		mysqli_query($con, $vlSqlCad);
	}
	//----------------------------
	


	echo "<script language='JavaScript'> location.href='pro_pedidos0.php'; </script>";
?>