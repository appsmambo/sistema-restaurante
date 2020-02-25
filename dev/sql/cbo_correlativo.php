<?php
session_start();
error_reporting(0);
include("conexion.php");

$vgCodEmp = $_SESSION["vgCodEmp"];
$vgCodSuc = $_SESSION["vgCodSuc"];

$id_category = $_POST['id_category'];
@mysqli_query($con, "SET NAMES 'utf8'");

$consulta = mysqli_query($con, "select serie, numero from man_correlativos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_docuconta='$id_category' and estado=0 order by id");
if (mysqli_num_rows($consulta) > 0){
	$row = mysqli_fetch_array($consulta);
	$numero = $row[serie].'-'.str_pad($row[numero]+1, 8, "0", STR_PAD_LEFT);
	echo "<script language='javascript'> document.form1.txtNroDoc.value = '".$numero."';</script>";
}
?>
