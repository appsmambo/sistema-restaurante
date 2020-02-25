<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];

    $fecha    = $_POST["fecha"];

    $rpta = 0;
    if($fecha != ""){
        $vlSqlCad = "update pro_aperturacajas set user_cierre='$vlUser', fec_cierre=now(), estado=1 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and  user_apertura='$vlUser' and fecha='$fecha'";
        $rpta = mysqli_query($con, $vlSqlCad);
    }
    echo $rpta;
?>