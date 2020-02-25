<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");

    $buscar = $_POST["buscar"];

    $hay = 0; $cod = ""; $nom = "";
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select nro_doc, nombre from man_clientes where nro_doc='$buscar'");
    while($row = mysqli_fetch_array($consulta1)){
        $hay = 1;
        $cod = strtoupper($row["nro_doc"]);
        $nom = utf8_decode(strtoupper($row["nombre"]));
    }

    echo $hay.'|'.$cod.'|'.$nom;
?>