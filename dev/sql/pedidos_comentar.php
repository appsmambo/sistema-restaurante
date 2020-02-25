<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];

	$variable = $_POST['variable'];		// cantidad
	$vlCodSec = $_POST["secuencia"];	// cod seecuencia
	
	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	$vlSqlCad = "update tmp_pedidos set comentario='$variable' where id='$vlCodSec'";
	mysqli_query($con, $vlSqlCad);

?>