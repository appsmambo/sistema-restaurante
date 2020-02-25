<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	//Valida que los campos de usuario y contraseña tengan datos para su validacion
	@mysqli_query($con, "SET NAMES 'utf8'");
	$tipo = strtolower(mysqli_real_escape_string($con, $_POST['tipo']));
	$valor = mysqli_real_escape_string($con, $_POST['valor']);
	$id = mysqli_real_escape_string($con, $_POST['id']);
	if ($tipo == null || $valor == null || $id == null)
	{

	}
	else
	{
		if($id>0){
			$consulta = mysqli_query($con, "select nombres from man_trabajadores where id_tipodoc = '$tipo' AND nro_doc = '$valor' and id_trabajador<>'$id'");
		}else{
			$consulta = mysqli_query($con, "select nombres from man_trabajadores where id_tipodoc = '$tipo' AND nro_doc = '$valor'");
		}
		if (mysqli_num_rows($consulta) > 0){
			echo "<span>El trabajador ya existe.</span>";
			echo "<script language='JavaScript'>document.form1.txtNroDoc.value = '';document.form1.txtNroDoc.focus();</script>";
		}
	}   
?>