<?php
	session_start();
	error_reporting(0);
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
			$consulta = mysqli_query($con, "select razon_social from man_proveedores where ruc = '$valor' and id_proveedor<>'$id'");
		}else{
			$consulta = mysqli_query($con, "select razon_social from man_proveedores where ruc = '$valor'");
		}
		if (mysqli_num_rows($consulta) > 0){
			echo "<span>El proveedor ya existe.</span>";
			echo "<script language='JavaScript'>document.form1.txtRuc.value = '';document.form1.txtRuc.focus();</script>";
		}
	}   
?>