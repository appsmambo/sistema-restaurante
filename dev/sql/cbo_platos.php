<?php
session_start();
error_reporting(0);
include("conexion.php");

$vgCodEmp = $_SESSION["vgCodEmp"];
$vgCodSuc = $_SESSION["vgCodSuc"];

$id_category = $_POST['id_category'];
@mysqli_query($con, "SET NAMES 'utf8'");
if($id_category==0){
	$consulta = mysqli_query($con, "select a.id_producto, a.nombre, a.precio, b.destino from man_productos as a left join man_tipoproductos as b on a.id_tipoproducto = b.id_tipoproducto where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and a.estado=0 order by a.nombre");
}else{
	$consulta = mysqli_query($con, "select a.id_producto, a.nombre, a.precio, b.destino from man_productos as a left join man_tipoproductos as b on a.id_tipoproducto = b.id_tipoproducto where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and a.id_tipoproducto='$id_category' and a.estado=0 order by a.nombre");
}
if (mysqli_num_rows($consulta) > 0){
	$html .= '<option value=""></option>';
	while ($row = mysqli_fetch_array($consulta)){
        $html .= '<option value="'.$row['id_producto'].'|'.$row['nombre'].'|'.$row['precio'].'|'.$row['destino'].'">'.strtoupper($row[nombre]).'&nbsp&nbsp&nbsp(S/ '.$row["precio"].')</option>';
    }
}
echo $html;
?>