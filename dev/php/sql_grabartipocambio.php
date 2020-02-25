<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $sec = $_POST["sec"];
    $fec = $_POST["fec"];
    $com = $_POST["com"];
    $ven = $_POST["ven"];

    if($sec>0){
        $vlSqlCad = "update man_tipocambio set fecha ='$fec', compra ='$com', venta ='$ven' where cod_secuencia='$sec'";
        $rpta = mysqli_query($con, $vlSqlCad);
    }else{
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta = mysqli_query($con, "select cod_secuencia from man_tipocambio where fecha='$fec'");
        if (mysqli_num_rows($consulta) <= 0){
            $vlSqlCad = "insert into man_tipocambio(fecha, compra, venta) values('$fec', '$com', '$ven')";
            $rpta = mysqli_query($con, $vlSqlCad);
        }else{
            $rpta = -100;
        }
    }
    
    echo $rpta;
?>