<?php
	session_start();
	error_reporting(0);
	$vgCodEmp  = $_SESSION["vgCodEmp"];
	$vgCodSuc  = $_SESSION["vgCodSuc"];
	include("conexion.php");
	//Valida que los campos de usuario y contraseña tengan datos para su validacion
	@mysqli_query($con, "SET NAMES 'utf8'");
	$valor = mysqli_real_escape_string($con, $_POST['valor']);
	$id = mysqli_real_escape_string($con, $_POST['id']);
	if ($valor == null || $id == null)
	{

	}
	else
	{
		if($id>0){
			$consulta = mysqli_query($con, "select id_mesa from man_mesas where id_mesa = '$valor' and id<>'$id'");
		}else{
			$consulta = mysqli_query($con, "select id_mesa from man_mesas where id_mesa = '$valor'");
		}
		if (mysqli_num_rows($consulta) > 0){
			echo "<span>Mesa ya existe.</span>";
			echo "<script language='JavaScript'>document.form1.txtNombre.value = '';document.form1.txtNombre.focus();</script>";
		}
	}   
?>