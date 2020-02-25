<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $sec = $_POST["sec"];
    $can = $_POST["can"];

    if($sec>0){
        $vlSqlCad = "update tmp set cantidad ='$can' where cod_secuencia='$sec'";
        $rpta = mysqli_query($con, $vlSqlCad);

    }
    echo $rpta;
?>