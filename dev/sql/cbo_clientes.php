<?php
session_start();
error_reporting(0);
include("conexion.php");

$vgCodEmp = $_SESSION["vgCodEmp"];
$vgCodSuc = $_SESSION["vgCodSuc"];

$id_category = $_POST['id_category'];
@mysqli_query($con, "SET NAMES 'utf8'");

$vlCampo = " where LENGTH(nro_doc)<11";
if($id_category==1){
	$vlCampo = " where LENGTH(nro_doc)=11";
}


$consulta = mysqli_query($con, "select 1 as tipo, id_cliente, id_tipodoc, nro_doc, nombre from man_clientes".$vlCampo." and nro_doc='00000000' union all select 2 as tipo, id_cliente, id_tipodoc, nro_doc, nombre from man_clientes".$vlCampo." and nro_doc<>'00000000' order by 1,5");
if (mysqli_num_rows($consulta) > 0){
	if($id_category==1){
		$html .= '<option value=""></option>';
	}
	while ($row = mysqli_fetch_array($consulta)){
		$nom = strtoupper($row[nombre]).' ('.strtoupper($row[nro_doc]).')';
		if($row[nro_doc]=="00000000"){
			$nom = strtoupper($row[nombre]);
		}
        $html .= '<option value="'.$row['nro_doc'].'">'.$nom.'</option>';
    }
}
echo $html;
?>