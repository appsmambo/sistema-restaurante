<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $id = $_GET["id"];
    //$id = 15;
    $hay = 1;

    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "select a.fec_registro, a.oc, a.tipo_doc, a.nro_doc, a.cliente, a.celular, a.correo, a.tipo_entrega, b.departamento, c.provincia, d.distrito, a.tienda, a.direccion, a.referencia, a.forma_pago, a.nro_cuenta, a.nro_operacion, a.fecha_operacion, a.subtotal, a.delivery, a.total, a.estado from pro_pedidos0 as a left join man_ubdepartamento as b on a.departamento = b.id_depa left join man_ubprovincia as c on a.provincia = c.id_prov left join man_ubdistrito as d on a.distrito = d.id_dist where a.cod_secuencia='$id'");
    $rowCli = mysqli_fetch_array($consulta);
?>

    <!-- Main content -->
    <section class="invoice">
    <div id="comprobante">
    <form id="frmOrden" name="frmOrden">
      <input type="hidden" id="txtCodSec" name="txtCodSec" value="<?php echo $id;?>" >
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-cart-plus"></i>&nbsp&nbsp # Orden: <?php echo $rowCli[oc];?>
            <small class="pull-right"><strong>Fecha Pedido:</strong> <?php echo substr($rowCli[fec_registro],8,2).'/'.substr($rowCli[fec_registro],5,2).'/'.substr($rowCli[fec_registro],0,4).' '.substr($rowCli[fec_registro],11,5);?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <address>
            <strong>Cliente</strong><br>
            <?php echo ucwords(strtolower($rowCli["cliente"]));?><br>
            <?php echo '<strong>'.$rowCli["tipo_doc"].':</strong> '.$rowCli["nro_doc"];?><br>
            <strong>Celular:</strong> <?php echo $rowCli["celular"];?><br>
            <strong>Correo:</strong> <?php echo $rowCli["correo"];?>
          </address>
        </div>
        <!-- /.col -->
        <?php if($rowCli["tipo_entrega"]=="TIENDA"){?>
          <div class="col-sm-4 invoice-col">
          <address>
            <strong>Tipo de Entrega</strong><br>
            <?php echo ucwords(strtolower($rowCli["tipo_entrega"]));?><br>
            <strong>Recojer en:</strong><br>
            <?php echo ucwords(strtolower($rowCli["tienda"]));?>
          </address>
        </div>
        <?php }?>
        <!-- /.col -->
        <?php if($rowCli["tipo_entrega"]=="DELIVERY"){?>
          <div class="col-sm-4 invoice-col">
          <address>
            <strong>Tipo de Entrega</strong><br>
            Delivery: <?php echo ucwords(strtolower($rowCli["departamento"])).' / '.ucwords(strtolower($rowCli["provincia"])).' / '.ucwords(strtolower($rowCli["distrito"]));?><br>
            <strong>Dirección: </strong><?php echo ucwords(strtolower($rowCli["direccion"]));?><br>
            <?php echo '(Ref. '.ucwords(strtolower($rowCli["referencia"])).')';?>
          </address>
        </div>


        <div class="col-sm-4 invoice-col">
          <address>
            <strong>Deposito a cuenta bancaria</strong><br>
            <?php echo ucwords(strtolower($rowCli["nro_cuenta"]));?><br>
            <strong>Nro Operacion:</strong> <?php echo $rowCli["nro_operacion"];?><br>
            <strong>Fecha Operacion:</strong> <?php echo substr($rowCli[fecha_operacion],8,2).'/'.substr($rowCli[fecha_operacion],5,2).'/'.substr($rowCli[fecha_operacion],0,4);?>
          </address>
        </div>
        <?php }?>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
<?php
	$vlCad01 = "";
	if(!empty($txtBuscar)){
		$vlCad01 = " and (nombre like '%$txtBuscar%')";
	}
	@mysqli_query($con, "SET NAMES 'utf8'");
	$consulta = mysqli_query($con, "select * from pro_pedidos1 where secref='$id' order by cod_secuencia");
	if (mysqli_num_rows($consulta) > 0){
?>
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Imagen</th>
              <th style="text-align:center">Cant</th>
              <th>Descripcion</th>
              <th>Color</th>
              <th>Talla</th>
              <th style="text-align:right">P.Unit.</th>
              <th style="text-align:right">Total</th>
            </tr>
            </thead>
            <tbody>
<?php
	while ($row = mysqli_fetch_array($consulta)){
?>
            <tr>
              <td width="6%"><img src="../../images/productos_panel/<?php echo $row["imagen"];?>" style="width:100%;height:auto"></td>
              <td style="text-align:center" width="2%"><?php echo $row["cantidad"];?></td>
              <td><?php echo $row["nombre"];?></td>
              <td width="9%"><?php echo $row["color"];?></td>
              <td width="9%"><?php echo $row["talla"];?></td>
              <td width="12%" style="text-align:right">S/ <?php echo number_format($row["precio"], 2, '.', ',');?></td>
              <td width="12%" style="text-align:right">S/ <?php echo number_format($row["cantidad"] * $row["precio"], 2, '.', ',');?></td>
            </tr>
<?php }?>
            </tbody>
          </table>
<?php }?>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-9">
        <div class="row no-print">
        <div class="col-xs-12">
        <?php
          $oculta1 = ""; $oculta2 = "";
          if($rowCli["estado"]==1){
            $oculta1 = "display:none;";
          }else{
            $oculta2 = "display:none;";
          }
        ?> 
        
        <button id="btnProcesar" name="btnProcesar" type="button" class="btn btn-primary pull-left" style="margin-right: 5px;<?php echo $oculta1;?>" onClick="javascript:procesar();"><i class="fa fa-thumbs-o-up"></i> Procesar</button>
        <a href="javascript:imprimir('comprobante');" id="btnImprimir" name="btnImprimir" class="btn btn-default" style="width:100px;<?php echo $oculta2;?>"><i class="fa fa-print"></i> Imprimir</a>
        </div>
      </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-3">

          <div>
            <table class="table">
              <?php if($rowCli["tipo_entrega"]=="DELIVERY"){?>
              <tr>
                <th style="text-align:right;width:50%">Subtotal:</th>
                <td style="text-align:right">S/ <?php echo number_format($rowCli["subtotal"], 2, '.', ',');?></td>
              </tr>
              <tr>
                <th style="text-align:right">Delivery</th>
                <td style="text-align:right">S/ <?php echo number_format($rowCli["delivery"], 2, '.', ',');?></td>
              </tr>
              <?php }?>
              <tr>
                <th style="text-align:right">Total:</th>
                <td style="text-align:right">S/ <?php echo number_format($rowCli["total"], 2, '.', ',');?></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      </form>
      </div>
    </section>
    <!-- /.content -->
<?php if($hay>0){?>
<script language='JavaScript'>
  document.getElementById("btnRetornar").style.display = "block";
  document.getElementById("btnGenerar").style.display = "none";
</script>
<?php }?>