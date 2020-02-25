<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];

    $fecha    = $_POST["fecha"];

    //-Apertura y cierre de caja------------------------
    $codAper = 0;
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select id_apertura, importe_apertura, estado from pro_aperturacajas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and fecha='$fecha' and user_apertura='$vlUser'");
    while($row = mysqli_fetch_array($consulta1)){
        $codAper = $row["id_apertura"];
        $impAper = $row["importe_apertura"];
        $estAper = $row["estado"];
    }
    //-------------------------------------------------

    echo $codAper.'|'.$impAper.'|'.$estAper;
?>