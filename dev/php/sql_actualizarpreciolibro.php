<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $codsec = $_POST["codsec"];
    $precio_new = $_POST["precio_new"];

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    $rpta=0;
    if($codsec>0){
        $vlSqlCad = "update man_titulos set pv_soles='$precio_new', user_modifica='$vlUser', fec_modifica=now() where cod_secuencia='$codsec'";
        $rpta = mysqli_query($con, $vlSqlCad);

    }
    echo $rpta;
?>