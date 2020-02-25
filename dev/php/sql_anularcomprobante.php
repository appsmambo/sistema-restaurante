<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];

    $cid = $_POST["cid"];

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    $rpta = 0;
    if($cid > 0){
        $vlSqlCad1 = "delete from pagos where fidc='$cid'";
        $rpta1 = mysqli_query($con, $vlSqlCad1);
        if($rpta1>0){
            $vlSqlCad2 = "delete from detalle where didc='$cid'";
            $rpta2 = mysqli_query($con, $vlSqlCad2);
            if($rpta2>0){
                $vlSqlCad = "update cabecera set cimportev=0, crecibido=0, csaldo=0, cestado='A', cuanula='$vlUser', cfanula=now() where cid='$cid'";
                $rpta = mysqli_query($con, $vlSqlCad);
            }
        }
    }
    echo $rpta;
?>