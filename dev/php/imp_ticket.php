<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");

    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    $vgFlujo  = $_SESSION["flujo"];

    $idc = $_GET["idc"];

    if($idc>0){
      $consulta0 = mysqli_query($con, "select * from man_empresas where id_empresa='$vgCodEmp'");
      $rowEmp    = mysqli_fetch_array($consulta0);


      //-
      $consulta1 = mysqli_query($con, "select * from cabecera where cid='$idc'");
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



      //-QR----------------------
      require '../phpqrcode/qrlib.php';

      $dir = "../temp/";
      
      if(!file_exists($dir)){
          mkdir($dir);
      }
      
      $filename = $dir.$rowCab["ctipdoc"]."-".$rowCab["cnrodoc"].".png";
      
      $tamanio = 4;
      $level = "M";
      $framesize = 3;
      $contenido = '20101268943 | '.$rowCab['ctipdoc'].' | '.$rowCab['cnrodoc'].' | '.$rowCab['cimportev'].' | '.$rowCab['cfechad'].' | '.$rowCab['cdniruc'].' | '.$rowCab['cnombre'];

      QRcode::png($contenido, $filename, $level, $tamanio, $framesize);
      //-------------------------

      /*$origen = $filename;
      $destino = "../dist/img/".$rowCab["ctipdoc"]."-".$rowCab["cnrodoc"].".png";
      copy($origen, $destino);
      */
?>
<style type="text/css">
    .lblticket2 {
        font-family: verdana;
        font-size:10px;
        background-color: #FFFFFF;
    }
    @media print {
      .lblticket2 {
        font-family: verdana;
        font-size:8px;
        background-color: #FFFFFF;
      }
      header, footer, nav, aside {
        display: none;
      }
    }
    @page 
    {
        size:  auto;   /* auto es el valor inicial */
        margin: 0mm;  /* afecta el margen en la configuración de impresión */
    }
</style>
<table width="220" border="0" align="center" class="lblticket2">
  <tbody>
    <tr>
      <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold"><img src="../dist/img/<?php echo $rowEmp["logo"];?>" width="30%" height="auto" alt=""/></td>
          </tr>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold"><?php echo $rowEmp["nombre"];?></td>
          </tr>
          <tr align="center">
            <td>RUC <?php echo $rowEmp["ruc"];?></td>
          </tr>
          <tr align="center">
            <td><?php echo $rowEmp["direccion"];?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold"><?php echo $cnomdoc;?></td>
          </tr>
          <tr align="center">
            <td><?php echo $rowCab["cnrodoc"];?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php if($rowCab["cdniruc"]!=""){?><?php echo $lbl01;?>: <?php echo $rowCab["cdniruc"];?><br><?php }?>
            <?php echo $lbl02;?>: <?php echo $rowCab["cnombre"];?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%" border="0">
              <tbody>
                <tr>
                  <td width="50%" align="left">Fecha: <?php echo substr($rowCab["cfechad"],8,2).'/'.substr($rowCab["cfechad"],5,2).'/'.substr($rowCab["cfechad"],0,4);?></td>
                  <td width="50%" align="right">Hora: <?php echo substr($rowCab["cfcrea"],11,5);?></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td colspan="3">==========================</td>
                </tr>
                <tr>
                  <td width="12%">Cant</td>
                  <td width="59%">Descripcion</td>
                  <td width="29%" align="right">Total</td>
                </tr>
                <tr>
                  <td colspan="3">==========================</td>
                </tr>
                <?php
                    $suma = 0; $itm = 0;
                    @mysqli_query($con, "SET NAMES 'utf8'");
                    if($vgFlujo==1){
                      $consulta = mysqli_query($con, "select a.dcantidad, a.dcodigo as dcodlib, b.nombre as dnomlib, a.dpdscto, a.dpreuni, a.dsubtot, a.digv as igv, a.dtotal as total from detalle as a left join man_productos as b on a.dcodigo = b.id_producto where a.didc='$idc' and a.id_empresa='$vgCodEmp' and b.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and b.id_sucursal='$vgCodSuc'");
                    }

                    if($vgFlujo==2){
                      $consulta = mysqli_query($con, "select a.dcantidad, a.dcodigo as dcodlib, b.nombre as dnomlib, a.dpdscto, a.dpreuni, a.dsubtot, a.digv as igv, a.dtotal as total from detalle as a left join man_productos as b on a.dcodigo = b.id_producto where a.didc='$idc' and a.id_empresa='$vgCodEmp' and b.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and b.id_sucursal='$vgCodSuc'");
                    }

                    while ($rowDet = mysqli_fetch_array($consulta)){
                      $itm = $itm + 1;

                      $precio = $rowDet["dpreuni"];

                      $total = $rowDet["dcantidad"] * $precio;
                      $suma = $suma + $total;

                ?>
                <tr>
                  <td valign="top"><?php echo $rowDet["dcantidad"];?></td>
                  <td valign="top"><?php echo substr($rowDet["dnomlib"],0,33);?></td>
                  <td align="right" valign="top"><?php echo number_format($total, 2, '.', ',');?></td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="3">==========================</td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>  
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="26%"><?php echo $itm;?> items</td>
                  <td align="right" width="45%">Total a Pagar S/</td>
                  <td align="right" width="29%" style="font-weight:bold"><?php echo number_format($suma, 2, '.', ',');?></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <?php if($informa_sunat=="SI"){?>
          <tr>
            <td style="height:10px"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                  <td align="right">Sub-Total S/</td>
                  <td align="right"><?php echo number_format($rowCab["subtotal"], 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td width="13%">&nbsp;</td>
                  <td align="right" width="58%">Igv S/</td>
                  <td align="right" width="29%"><?php echo number_format($rowCab["igv"], 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="right">Total S/</td>
                  <td align="right"><?php echo number_format($rowCab["total"], 2, '.', ',');?></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <?php }?>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="13%">&nbsp;</td>
                  <td align="right" width="58%">Recibido S/</td>
                  <td align="right" width="29%"><?php echo number_format($rowCab["crecibido"], 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td width="13%">&nbsp;</td>
                  <td align="right" width="58%">Vuelto  S/</td>
                  <td align="right" width="29%"><?php echo number_format(abs($rowCab["csaldo"]), 2, '.', ',');?></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <tr>
            <td>MESA: <?php echo str_pad($rowCab["id_mesa"], 2, "0", STR_PAD_LEFT);?></td>
          </tr>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <?php if($electronico=="SI"){?>
          <tr align="center">
            <td><?php echo $rowEmp["autorizacion"];?></td>
          </tr>
          <tr>
            <td style="height:3px"></td>
          </tr>
          <tr align="center">
            <td><img src="<?php echo $filename;?>" alt="qrr"/></td>
          </tr>
          <tr>
            <td style="height:5px"></td>
          </tr>
          <?php }?>
          <tr align="center">
            <td style="font-weight:bold">! Gracias por su visita ¡</td>
          </tr>
          <tr>
            <td style="height:15px"></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<?php }?>