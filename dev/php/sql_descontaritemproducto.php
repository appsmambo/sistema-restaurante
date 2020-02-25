<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $sec = $_POST["sec"];
    $can = $_POST["can"];
    $pre = $_POST["pre"];
    $por = $_POST["por"];
    
    $campo1 = "cantidad=cantidad";
    $campo2 = ", precio=precio";
    $campo3 = ", descuento=descuento";

    if($can>0){
        $campo1 = "cantidad='$can'";
    }
    if($pre>0){
        $campo2 = ", precio='$pre'";
    }
    if($por>0){
        $campo3 = ", descuento='$por'";
    }

    if($sec>0){
        $vlSqlCad = "update tmp set ".$campo1.$campo2.$campo3." where cod_secuencia='$sec'";
        $rpta = mysqli_query($con, $vlSqlCad);
    }
    echo $rpta;
?>