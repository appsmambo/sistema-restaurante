<?php
session_start();

include("sys_seguridad.php");
include("../sql/conexion.php");

$correo = $_POST["correo"];
$id     = $_POST["id"];

$vgCodEmp = $_SESSION["vgCodEmp"];
$vgCodSuc = $_SESSION["vgCodSuc"];

//$correo = "dangeldones@lari.pe,diego_angeldones@outlook.com";
//$id     = 39;

if($id>0){

  $consulta0 = mysqli_query($con, "select * from man_empresas where id_empresa='$vgCodEmp'");
  $rowEmp    = mysqli_fetch_array($consulta0);

  $vlDetalle = "";
  $suma = 0; $itm = 0;
  //$consulta = mysqli_query($con, "select a.dcantidad, a.dcodlib, b.nombre as dnomlib, a.dpdscto, a.dpreuni from detalle as a left join man_titulos as b on a.dcodlib = b.codigo where a.didc='$id'");
  $consulta = mysqli_query($con, "select a.dcantidad, a.dcodigo as dcodlib, b.nombre as dnomlib, a.dpdscto, a.dpreuni, a.dsubtot, a.digv as igv, a.dtotal as total from detalle as a left join man_productos as b on a.dcodigo = b.id_producto where a.didc='$id' and a.id_empresa='$vgCodEmp' and b.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and b.id_sucursal='$vgCodSuc'");
  while ($rowDet = mysqli_fetch_array($consulta)){
    $itm = $itm + 1;
    $total = $rowDet['dcantidad'] * $rowDet['dpreuni'];
    $suma = $suma + $total;
    $vlDetalle = $vlDetalle."<tr>
                              <td valign='top'>".$rowDet['dcantidad']."</td>
                              <td valign='top'>".substr($rowDet['dnomlib'],0,33)."</td>
                              <td align='right' valign='top'>".number_format($total, 2, '.', ',')."</td>
                            </tr>";
  }

      //-
      $consulta1 = mysqli_query($con, "select * from cabecera where cid='$id'");
      $rowCab    = mysqli_fetch_array($consulta1);
      $tipitodoc = $rowCab["ctipdoc"];

      $cnomdoc = "";
      $consulta = mysqli_query($con, "select * from man_parametros where pcodtabla='COMPR' and pcodigo='$tipitodoc'");
      $row = mysqli_fetch_array($consulta);
      $cnomdoc = trim($row["pnombre"]);
      $informa_sunat = $row["panexo2"];
      $electronico   = $row["panexo3"];

      if(strpos($cnomdoc, "FACTURA") !== false) {
          $lbl01 = "RUC";
          $lbl02 = "CLIENTE";
      }else{
          $lbl01 = "DNI";
          $lbl02 = "CLIENTE";
      }


      $linea_rara = $lbl02.": ". $rowCab['cnombre'];
      if($rowCab["cdniruc"]!=""){
        $linea_rara = $lbl01.": ". $rowCab['cdniruc']."<br>".$lbl02.": ". $rowCab['cnombre'];
      }

      $linea_sunat = "";
      if($informa_sunat=="SI"){
        $linea_sunat = "<tr>
          <td style='height:10px'><table width='100%' border='0' cellpadding='0' cellspacing='0'>
            <tbody>
              <tr>
                <td width='13%'>&nbsp;</td>
                <td align='right'>Sub-Total S/</td>
                <td align='right'>".number_format($rowCab["subtotal"], 2, '.', ',')."</td>
              </tr>
              <tr>
                <td width='13%'>&nbsp;</td>
                <td align='right' width='58%'>Igv S/</td>
                <td align='right' width='29%'>".number_format($rowCab["igv"], 2, '.', ',')."</td>
              </tr>
              <tr>
                <td width='13%'>&nbsp;</td>
                <td align='right' width='58%'>Total S/</td>
                <td align='right' width='29%'>".number_format($rowCab["total"], 2, '.', ',')."</td>
              </tr>

            </tbody>
          </table></td>
        </tr>
        <tr>
          <td>==================================</td>
        </tr>";
      }


$vlListaDestinatarios = $correo;

require("class.phpmailer.php");
$mail = new phpMailer();
$mail->IsSMTP(); // telling the class to use SMTP

$mail->SMTPAuth = true;                      // Enable SMTP authentication
$mail->Username = 'facturacionelectronica@iep.org.pe';        // SMTP username
$mail->Password = 'Yuh01313';               // SMTP password|

$mail->Host = "outlook.office365.com";              // SMTP server
$mail->From = "facturacionelectronica@iep.org.pe";
$mail->FromName = "MUELLE 69 :: ".$ctipdoc." ".$rowCab['cnrodoc'];
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted ''tls
$mail->Port = 587;                                    // TCP port to connect to '465' '587'

$mail->AddAddress($regCab["correo"], $regCab["correo"]);
$correos = explode(",",$vlListaDestinatarios);
foreach($correos as $valor){
	$mail->AddAddress($valor, $valor);
}

$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = "Envio del Comprobante de su consumo";
$qrImg = "../temp/".$rowCab["ctipdoc"]."-".$rowCab['cnrodoc'].".png";
$logo  = "../dist/img/".$rowEmp["logo"];
if($electronico=="SI"){
  $mail->AddEmbeddedImage($qrImg,"qr");
}
$mail->AddEmbeddedImage($logo,"imagen");

$linea_autorizacion = "";
if($electronico=="SI"){
  $linea_autorizacion = "<tr align='center'>
    <td>".$rowEmp["autorizacion"]."</td>
  </tr>
  <tr>
    <td style='height:3px'></td>
  </tr>
  <tr align='center'>
    <td><img src='cid:qr' /></td>
  </tr>
  <tr>
    <td style='height:5px'></td>
  </tr>";
}

//$mail->Body = "Hola ! \n\n correo enviado usando phpMailer !';
$mail->Body = "<meta charset='utf-8'/>
<style type='text/css'>
.lblticket {
    font-family: verdana;
    font-size:10px;
    background-color: #FFFFFF;
}
</style>
<table width='290' border='0' align='center' class='lblticket'>
<tbody>
<tr>
  <td><table width='90%' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tbody>
    <tr>
    <td style='height:10px'></td>
  </tr>
  <tr align='center'>
    <td style='font-weight:bold'><img src='cid:imagen' width='50%' height='auto' alt=''/></td>
  </tr>
      <tr>
        <td style='height:10px'></td>
      </tr>
      <tr align='center'>
        <td style='font-weight:bold'>".$rowEmp["nombre"]."</td>
      </tr>
      <tr align='center'>
        <td>RUC ".$rowEmp["ruc"]."</td>
      </tr>
      <tr align='center'>
        <td>".$rowEmp["direccion"]."</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr align='center'>
        <td style='font-weight:bold'>".$cnomdoc."</td>
      </tr>
      <tr align='center'>
        <td>".$rowCab['cnrodoc']."</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>".$linea_rara."</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width='100%' border='0'>
          <tbody>
            <tr>
              <td width='50%' align='left'>Fecha: ".substr($rowCab['cfechad'],8,2).'/'.substr($rowCab['cfechad'],5,2).'/'.substr($rowCab['cfechad'],0,4)."</td>
              <td width='50%' align='right'>Hora: ".substr($rowCab['cfcrea'],11,5)."</td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td><table width='100%' border='0' cellpadding='0' cellspacing='0'>
          <tbody>
            <tr>
              <td colspan='3'>==================================</td>
            </tr>
            <tr>
              <td width='11%'>Cant</td>
              <td width='72%'>Descripcion</td>
              <td width='17%' align='right'>Total</td>
            </tr>
            <tr>
              <td colspan='3'>==================================</td>
            </tr>".$vlDetalle."<tr>
              <td colspan='3'>==================================</td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>  
        <td><table width='100%' border='0' cellpadding='0' cellspacing='0'>
          <tbody>
            <tr>
              <td width='26%'>".$itm." items</td>
              <td align='right' width='58%'>Total a Pagar S/</td>
              <td align='right' width='29%' style='font-weight:bold'>".number_format($suma, 2, '.', ',')."</td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td>==================================</td>
      </tr>".$linea_sunat."<tr>
    <td><table width='100%' border='0' cellpadding='0' cellspacing='0'>
      <tbody>
        <tr>
          <td width='13%'>&nbsp;</td>
          <td align='right' width='58%'>Recibido S/</td>
          <td align='right' width='29%'>".number_format($rowCab['crecibido'], 2, '.', ',')."</td>
        </tr>
        <tr>
          <td width='13%'>&nbsp;</td>
          <td align='right' width='58%'>Vuelto  S/</td>
          <td align='right' width='29%'>".number_format(abs($rowCab['csaldo']), 2, '.', ',')."</td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr>
    <td>==================================</td>
  </tr>
  <tr>
    <td>MESA: ".str_pad($rowCab["id_mesa"], 2, "0", STR_PAD_LEFT)."</td>
  </tr>
    <tr>
      <td style='height:5px'></td>
    </tr>".$linea_autorizacion."
      <tr>
        <td style='height:5px'></td>
      </tr>
      <tr align='center'>
        <td style='font-weight:bold'>! Gracias por su visita &#105;</td>
      </tr>
      <tr>
        <td style='height:15px'></td>
      </tr>
    </tbody>
  </table></td>
</tr>
</tbody>
</table>";
$mail->WordWrap = 50;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if(!$mail->Send()){
    echo 0;
  }else{
    echo 1;
  }
}
?>