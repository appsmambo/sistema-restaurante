<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $vgCodUser = $_SESSION["user"];
    $id = $_POST["txtCodSec"];

    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "update pro_pedidos0 set estado=1, user_estado='$vgCodUser', fec_estado=now() where cod_secuencia='$id'");
    echo $consulta;
?>