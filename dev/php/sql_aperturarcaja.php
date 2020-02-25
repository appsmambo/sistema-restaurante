<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];

    $importe = $_POST["importe"];
    $fecha   = $_POST["fecha"];

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    $rpta = 0;
    if($importe > 0){
        $vlSqlCad = "insert into pro_aperturacajas(id_empresa, id_sucursal, fecha, importe_apertura, user_apertura, fec_apertura) values ('$vgCodEmp', '$vgCodSuc', '$fecha', '$importe', '$vlUser', now())";
        $rpta = mysqli_query($con, $vlSqlCad);
    }
    echo $rpta;
?>