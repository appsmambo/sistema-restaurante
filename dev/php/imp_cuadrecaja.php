<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");

    $vlUser   = $_SESSION["user"];
    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    $vgNomUse = $_SESSION["usuario"];

    $fecha = $_GET["fecha"];
    $fecha_formateada = substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
    $idaper  = $_GET["idaper"];
    $impaper = $_GET["impaper"];
    $estaper = $_GET["estaper"];

    $nom_estado = "Abierto";
    if($estaper==1){
      $nom_estado = "Cerrado";
    }
  
    $consulta0 = mysqli_query($con, "select * from man_empresas where id_empresa='$vgCodEmp'");
    $rowEmp    = mysqli_fetch_array($consulta0);

    if($fecha!=""){    
        //-Consultando tipo de cambio venta--------------
        $vltcambio = 0;
        $consulta = mysqli_query($con, "select venta as tcambio, now() as fecha from man_tipocambio where fecha='$fecha'");
        $rowTope = mysqli_fetch_array($consulta);
        $vltcambio = $rowTope["tcambio"];
        $vlHora    = substr($rowTope["fecha"],11,5);

        //-Cabecera de comprobantes------------------------
        $vuelto = 0;
        $cabecera = "";
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta1 = mysqli_query($con, "select ctipdoc, cnrodoc, cestado, csaldo from cabecera where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and cfechad='$fecha' and cucrea='$vlUser' order by ctipdoc, cnrodoc");
        while($row = mysqli_fetch_array($consulta1)){
            $cabecera[] = $row;
            $vuelto = $vuelto  + ABS($row["csaldo"]);
        }
        //-------------------------------------------------

        //-Pagos de comprobantes------------------------
        $pagos = "";
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta1 = mysqli_query($con, "select ffpago as tip_pago, cod_pago, nom_pago, fsoles as soles, fdolar as dolar, ftcambio as tcambio, fobserva as observacion from pagos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and ffechad='$fecha' and user_registro='$vlUser' order by cod_pago");
        while($row = mysqli_fetch_array($consulta1)){
            $pagos[] = $row;
        }
        //-------------------------------------------------

        //-Gastos de Caja------------------------
        $gastos = "";
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta1 = mysqli_query($con, "select concepto, importe, id from pro_gastoscaja_cab where id_empresa='$vgCodEmp' AND id_sucursal='$vgCodSuc' AND fecha='$fecha' and user_registro='$vlUser' order by id desc");
        while($row = mysqli_fetch_array($consulta1)){
            $gastos = $gastos  + $row["importe"];
        }
        //-------------------------------------------------
?>
<style type='text/css'>
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
<table width='220' border='0' align='center' class='lblticket'>
  <tbody>
    <tr>
      <td><table width='90%' border='0' align='center' cellpadding='0' cellspacing='0'>
        <tbody>
          <tr>
            <td style='height:10px'></td>
          </tr>
          <tr align='center'>
            <td style='font-weight:bold'><?php echo $rowEmp["nombre"];?></td>
          </tr>
          <tr align='center'>
            <td>RUC <?php echo $rowEmp["ruc"];?></td>
          </tr>
          <tr align='center'>
            <td><?php echo $rowEmp["direccion"];?></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <tr align='center'>
            <td style='font-weight:bold'>CUADRE DE CAJA DEL DIA</td>
          </tr>
          <tr align='center'>
            <td style='font-weight:bold'><?php echo $fecha_formateada;?></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <tr>
  <td><table width='100%' border='0'>
    <tbody>
      <!--<tr>
        <td colspan='2' align='left'>Etiquetera: S/N: 29111206060600062 C</td>
        </tr>-->
      <tr>
        <td width='42%' align='left'>Hora: <?php echo $vlHora;?></td>
        <td width='58%' align='right'>Tipo de Cambio: <?php echo $vltcambio;?></td>
      </tr>
      <tr>
        <td colspan='2' align='left'>Cajero: <?php echo $vgNomUse;?></td>
      </tr>
      <tr>
        <td colspan='2' align='left'>Estatus Caja: <b><?php echo $nom_estado;?></b></td>
      </tr>
    </tbody>
  </table></td>
          </tr>

<?php
    $consulta2 = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1");
    if (mysqli_num_rows($consulta2) > 0){
        while ($row = mysqli_fetch_array($consulta2)){
            $vlCodigo = $row["codigo"];
            $vlNombre = strtoupper($row["nombre"]);
            $vlSuma1 = 0; $vlSuma2 = 0; $vlSuma3 = 0; $vlNroIni = ""; $vlNroFin = "";

            if($cabecera!=""){
                foreach($cabecera as $arrayCab){
                    if($arrayCab["ctipdoc"] == $vlCodigo){
                        $vlSuma1 = $vlSuma1 + 1;

                        if($vlSuma1==1){
                            $vlNroIni = $arrayCab["cnrodoc"];
                        }
                        $vlNroFin = $arrayCab["cnrodoc"];

                        if($arrayCab["cestado"] == "A"){
                            $vlSuma3 = $vlSuma3 + 1;
                        }else{
                            $vlSuma2 = $vlSuma2 + 1;
                        }
                    }
                }
            }

            if($vlSuma1>0){
?>

          <tr>
            <td>==========================</td>
          </tr>
          <tr>
            <td align='center'><?php echo $vlNombre;?></td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <tr>
            <td><table width='100%' border='0'>
              <tbody>
                <tr>
                  <td width='28%'>Nro Inicio</td>
                  <td width='27%'>Nro Final</td>
                  <td width='15%' align='right'>Emit</td>
                  <td width='15%' align='right'>Valid</td>
                  <td width='15%' align='right'>Anul</td>
                </tr>
                <tr>
                  <td valign='top'><?php echo $vlNroIni;?></td>
                  <td valign='top'><?php echo $vlNroFin;?></td>
                  <td align='right' valign='top'><?php if($vlSuma1>0){ echo $vlSuma1;}?></td>
                  <td align='right' valign='top'><?php if($vlSuma2>0){ echo $vlSuma2;}?></td>
                  <td align='right' valign='top'><?php if($vlSuma3>0){ echo $vlSuma3;}?></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
<?php }}}?>

<?php
    $consulta2 = mysqli_query($con, "select pcodigo as codigo, panexo2 as nombre from man_parametros where pcodtabla='CAJ01' and pcodigo<>'00' order by panexo1");
    if (mysqli_num_rows($consulta2) > 0){
?>
          <tr>
            <td>==========================</td>
          </tr>
          <tr>  
            <td align='center'>VENTAS POR FORMA DE PAGOS</td>
          </tr>
          <tr>
            <td>==========================</td>
          </tr>
          <tr>
            <td><table width='100%' border='0'>
              <tbody>
              <?php
                    $vlSoles = 0; $vlDolar = 0; $cambiado = 0; $vlTotalIngreso = 0;

                    if($pagos!=""){
                        foreach($pagos as $arrayPago){
                            if($arrayPago["cod_pago"] == "ES"){
                                $vlSoles = $vlSoles + $arrayPago["soles"];
                            }
                            if($arrayPago["cod_pago"] == "ED"){
                                $vlDolar = $vlDolar + $arrayPago["dolar"];
                                $cambiado = $cambiado + $arrayPago["soles"];
                            }
                            $tipoCambio = $arrayPago["tcambio"];
                        }
                        $vlSoles = $vlSoles-$vuelto;
                    }
                    $vlTotalSoles = $vlSoles + $cambiado;
                    $vlTotalIngreso = $vlTotalIngreso + $vlTotalSoles;
                ?>
                <tr>
                  <td colspan="2"><b>APERTURA DE CAJA</b></td>
                  <td width='29%' align='right'><?php echo number_format($impaper, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                </tr>
                <tr>
                  <td width='29%'><b>EFECTIVO</b></td>
                  <td width='42%' align='right'>Neto S/</td>
                  <td width='29%' align='right'><?php echo number_format($vlSoles, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td colspan='2' align='right'>Neto USD $</td>
                  <td align='right'><?php echo number_format($vlDolar, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td colspan='2' align='right'>Neto USD $ a S/</td>
                  <td align='right'><?php echo number_format($cambiado, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td colspan='2' align='right'>Total Efectivos S/</td>
                  <td align='right'><?php echo number_format($vlTotalSoles, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                </tr>
                <?php
                    $contar = 0;
                    while ($row = mysqli_fetch_array($consulta2)){
                        $contar = $contar + 1;
                        $vlCodigo = $row["codigo"];
                        $vlNombre = $row["nombre"];
                        $vlSuma1 = 0;

                        if($pagos!=""){
                            foreach($pagos as $arrayPago){
                                if($arrayPago["cod_pago"] == $vlCodigo){
                                    $vlSuma1 = $vlSuma1 + $arrayPago["soles"];
                                }
                            }
                        }
                        $vlTotalIngreso = $vlTotalIngreso + $vlSuma1;
                ?>
                <tr>
                    <td><?php if($contar==1){?><b>TARJETA<?php }?></b></td>
                  <td align='right'><?php echo $vlNombre;?> S/</td>
                  <td align='right'><?php echo number_format($vlSuma1, 2, '.', ',');?></td>
                </tr>
                <?php }?>
                <tr>
                  <td>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2"><b>VUELTOS</b></td>
                  <td width='29%' align='right'><?php echo '- '.number_format($vuelto, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td colspan="2"><b>GASTOS DE CAJA</b></td>
                  <td width='29%' align='right'><?php echo '- '.number_format($gastos, 2, '.', ',');?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                  <td align='right'>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan='2'><b>INGRESOS DEL DIA</b></td>
                  <td align='right'><b><?php echo number_format($vlTotalIngreso+$impaper-$vuelto-$gastos, 2, '.', ',');?></b></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        <?php }?>
          <tr>
            <td style='height:10px'></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<?php
    //if($vlTotalIngreso>0){
?>
        <script language="JavaScript">
            document.getElementById("grid1").style.display = "";
            document.getElementById("ticket").style.display = "";
            document.getElementById("txtFecha").focus();
        </script>
<?php
    //}
}
?>