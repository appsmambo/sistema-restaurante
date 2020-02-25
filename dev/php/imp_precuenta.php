<?php
    //session_start();
    //include("sys_seguridad.php");
    //include("../sql/conexion.php");

    //$vgCodEmp = $_SESSION["vgCodEmp"];
    //$vgCodSuc = $_SESSION["vgCodSuc"];
    //$vgFlujo  = $_SESSION["flujo"];

    $entra = 1;

    if($entra==1){
      $consulta0 = mysqli_query($con, "select *, now() as cfecha from man_empresas where id_empresa='$vgCodEmp'");
      $rowEmp    = mysqli_fetch_array($consulta0);
?>
<style type="text/css">
    .lblticket {
        font-family: verdana;
        font-size:10px;
        background-color: #FFFFFF;
    }
    @media print {
      .lblticket {
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
<table width="220" border="0" align="center" class="lblticket">
  <tbody>
    <tr>
      <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold;height:17px"><?php echo $rowEmp["nombre"];?></td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold">PRE-CUENTA</td>
          </tr>
          <tr align="center">
            <td style="font-weight:bold">MESA: <?php echo str_pad($vlMesa, 2, "0", STR_PAD_LEFT);?></td>
          </tr>
          <tr>
            <td><table width="100%" border="0">
              <tbody>
                <tr>
                  <td width="50%" align="left">Fecha: <?php echo substr($rowEmp["cfecha"],8,2).'/'.substr($rowEmp["cfecha"],5,2).'/'.substr($rowEmp["cfecha"],0,4);?></td>
                  <td width="50%" align="right">Hora: <?php echo substr($rowEmp["cfecha"],11,5);?></td>
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
                    foreach($detalle as $rowDet){
                    //while ($rowDet = mysqli_fetch_array($consulta)){
                      $itm = $itm + 1;

                      $precio = $rowDet["cant"];

                      $total = $rowDet["cant"] * $rowDet["precio"];
                      $suma = $suma + $total;

                ?>
                <tr>
                  <td valign="top"><?php echo $rowDet["cant"];?></td>
                  <td valign="top"><?php echo substr($rowDet["descripcion"],0,33);?></td>
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
          <tr>
            <td style="height:30px"></td>
          </tr>
          <tr>
            <td>RUC ó DNI</td>
          </tr>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <tr>
            <td style="height:30px">__________________</td>
          </tr>
          <tr>
            <td style="height:30px"></td>
          </tr>
          <tr>
            <td>RAZON SOCIAL ó NOMBRE</td>
          </tr>
          <tr>
            <td style="height:10px"></td>
          </tr>
          <tr>
            <td style="height:30px">_________________________________</td>
          </tr>
          <tr>
            <td style="height:30px">_________________________________</td>
          </tr>
          <tr>
            <td style="height:30px">_________________________________</td>
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