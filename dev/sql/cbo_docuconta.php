<?php
session_start();
error_reporting(0);
include("conexion.php");

$vgCodEmp = $_SESSION["vgCodEmp"];
$vgCodSuc = $_SESSION["vgCodSuc"];

$id_category = $_POST['id_category'];
@mysqli_query($con, "SET NAMES 'utf8'");

$vlCampo = " where id_docuconta=4";
if($id_category=="CONS"){
	$vlCampo = " where id_docuconta<>4";
}

$consulta = mysqli_query($con, "select id_docuconta, nombre from man_docuconta".$vlCampo." order by id_docuconta");
if (mysqli_num_rows($consulta) > 0){
	$html .= '<option value=""></option>';
	while ($row = mysqli_fetch_array($consulta)){
        $html .= '<option value="'.$row['id_docuconta'].'">'.strtoupper($row[nombre]).'</option>';
    }
}
echo $html;
?>