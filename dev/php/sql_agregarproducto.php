<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $cod = $_POST["cod"];
    $nom = $_POST["nom"];
    $pre = $_POST["pre"];
    $ses = $_POST["ses"];
    $prelib = $_POST["prelib"];

	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

    if($cod!=""){
        $vlSqlCad = "insert into tmp(sesion, codigo, descripcion, cantidad, precio, precio_libreria, user_registro, fec_registro) values ('$ses', '$cod', '$nom', 1, '$pre', '$prelib', '$vlUser', now())";
        $rpta = mysqli_query($con, $vlSqlCad);

        /*if($rpta>0){
            $pago_total = 0; $contador = 0;
            $consulta = mysql_query("select cantidad, precio from tmp_shopping where ip='$ip'", $vgDnsCon);
            while ($row = mysql_fetch_array($consulta)){
                $total = $row['cantidad'] * $row['precio'];
                $pago_total = $pago_total + $total;
                $contador = $contador + 1;
            }
        }

        $pago_total = number_format($pago_total, 2, '.', ',');*/

    }
    echo $rpta;
?>