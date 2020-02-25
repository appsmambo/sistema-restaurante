<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $cod = $_POST["tipdoc"];

    $devo1 = ""; $devo2 = ""; $devo3 = ""; $devo4  = "";
    $enc = 0; $vuelta = 0;
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "select * from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1");
    while ($row = mysqli_fetch_array($consulta)){
        if($enc==1){
            $devo1 = trim($row["pcodigo"]);
            $devo2 = trim($row["pnombre"]);

            if (strpos($devo2, "FACTURA") !== false) {
                $devo3 = "RUC";
                $devo4 = "RAZON SOCIAL";
            }else{
                $devo3 = "DNI";
                $devo4 = "NOMBRE DEL CLIENTE";
            }
            $enc = 0;
        }else{
            if($row["pcodigo"]==$cod){
                $enc = 1;
            }
        }
    }
    if($devo1==""){
        $consulta = mysqli_query($con, "select * from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1 limit 1");
        while ($row = mysqli_fetch_array($consulta)){
            $devo1 = trim($row["pcodigo"]);
            $devo2 = trim($row["pnombre"]);

            if (strpos($devo2, "FACTURA") !== false) {
                $devo3 = "RUC";
                $devo4 = "RAZON SOCIAL";
            }else{
                $devo3 = "DNI";
                $devo4 = "NOMBRE DEL CLIENTE";
            }
        }
    }
    echo $devo1.'|'.$devo2.'|'.$devo3.'|'.$devo4;
?>