<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $cod = $_POST["cod"];

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    $rpta = 0;
    if($cod!=""){
        $vlSqlCad = "delete from man_tipocambio where cod_secuencia='$cod'";
        $rpta = mysqli_query($con, $vlSqlCad);

    }
    echo $rpta;
?>