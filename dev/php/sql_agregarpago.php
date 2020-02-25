<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $codpago = $_POST["codpago"];
    $nompago = $_POST["nompago"];
    $tippago = $_POST["tippago"];
    $tcambio = $_POST["tcambio"];
    $mensaje = strtoupper($_POST["mensaje"]);
    $ses = $_POST["ses"];

    if($codpago=="ED"){
        $importe_dolares = $_POST["importe"];
        $importe_soles   = $_POST["importe"] * $tcambio;
    }else{
        $importe_soles   = $_POST["importe"];
        $importe_dolares = $_POST["importe"] / $tcambio;
    }
    

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    $rpta = 0;
    if($importe_soles > 0){
        $vlSqlCad = "insert into tmp_pagos(sesion, tipo_pago, cod_pago, nom_pago, tcambio, importe_soles, importe_dolares, observacion) values ('$ses', '$tippago', '$codpago', '$nompago', '$tcambio', '$importe_soles', '$importe_dolares', '$mensaje')";
        $rpta = mysqli_query($con, $vlSqlCad);
    }
    echo $rpta;
?>