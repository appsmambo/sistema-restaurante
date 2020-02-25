<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$idmesa	  = $_GET["idmesa"];

	@mysqli_query($con, "SET NAMES 'utf8'");
	$consulta = mysqli_query($con, "select a.ocupado, a.user_ocupado, b.nombres, b.paterno, b.materno from man_mesas as a left join man_trabajadores as b on a.user_ocupado = b.usuario  where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and a.id_mesa='$idmesa'");
	$row0 = mysqli_fetch_array($consulta);
	$vlOcupado = $row0[ocupado];

	$nombre_foto = "../dist/img/user/".strtoupper($row0[user_ocupado]).".jpg";
	if(!file_exists($nombre_foto)) {
		$nombre_foto = "../dist/img/user/SINFOTO.jpg";
	}

	$vlColorBusy = ""; $vlColorLine = "";
	if($row0[ocupado]==1){
		$vlColorBusy = "red";
		$vlColorLine = "danger";
	}
	if($row0[ocupado]==2){
		$vlColorBusy = "yellow";
		$vlColorLine = "warning";
	}
	if($row0[ocupado]==3){
		$vlColorBusy = "green";
		$vlColorLine = "success";
	}
	if($row0[ocupado]==4){
		$vlColorBusy = "blue";
		$vlColorLine = "primary";
	}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestion Restaurante</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<script language="javascript">
	function cambiar(a){
		id_category = a;
		$.post("../sql/cbo_clientes.php", { id_category: id_category }, function(data){
			$("#cboCliente").html(data);
			if(a==""){
				$('#cboCliente').attr('disabled', 'disabled');
			}else{
				$('#cboCliente').removeAttr('disabled');
			}
		});


		$.post("../sql/cbo_correlativo.php", { id_category: id_category }, function(data){
			$("#txtNroDoc").html(data);
		});

	}
	function pulsar(e){
		tecla = (document.all) ? e.keyCode :e.which;
		return (tecla!=13);
	}
	function sumar(){
		document.getElementById("fal").style.display = "none";
		document.getElementById("vue").style.display = "none";
		document.getElementById("separa").style.display = "none";
		var pagar = parseFloat(document.form1.txtPagar.value).toFixed(2);

		var efectivo = 0;
		if(document.form1.txtEfectivo.value!=""){
			var efectivo = parseFloat(document.form1.txtEfectivo.value);
		}

		var visa = 0;
		if(document.form1.txtVisa.value!=""){
			var visa = parseFloat(document.form1.txtVisa.value);
		}
		
		var mastercard = 0;
		if(document.form1.txtMastercard.value!=""){
			var mastercard = parseFloat(document.form1.txtMastercard.value);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);

		if(sumatoria>0){
			if(sumatoria<pagar){
				document.form1.txtVuelto.value = "";
				document.form1.txtFalta.value = parseFloat(pagar-sumatoria).toFixed(2);
				document.form1.txtPagado.value = parseFloat(sumatoria).toFixed(2);
				document.getElementById("fal").style.display = "block";
				document.getElementById("separa").style.display = "block";
			}
			if(sumatoria>pagar){
				document.form1.txtFalta.value = "";
				document.form1.txtPagado.value = parseFloat(pagar).toFixed(2);
				document.form1.txtVuelto.value = parseFloat(sumatoria-pagar).toFixed(2);
				document.getElementById("vue").style.display = "block";
				document.getElementById("separa").style.display = "block";
			}
			if(sumatoria==pagar){
				document.form1.txtFalta.value = "";
				document.form1.txtVuelto.value = "";
				document.form1.txtPagado.value = parseFloat(sumatoria).toFixed(2);
			}
		}
		
	}

	function grabar(a,b){
		var variable_post = a;
		var tipo_documento = b;
		$.post("../sql/caja_grabar.php", { variable: variable_post , tipodoc: b }, function(data){
		$("#grabacaja").html(data);
		});
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body class="hold-transition skin-purple sidebar-collapse" onLoad="javascript:document.form1.txtEfectivo.focus();">
<div class="wrapper">

  <?php include("inc_header.php");?>
  <?php include("inc_menu.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Emitir Comprobantes
        <small>&nbsp;</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Caja</a></li>
      </ol>
    </section>




    <section class="content">


      <div class="row">

        <div class="col-md-6">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-<?php echo $vlColorBusy;?>">
              <div class="widget-user-image">
                <img class="img-circle" src="<?php echo $nombre_foto;?>" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">Mesa <?php echo str_pad($idmesa, 2, "0", STR_PAD_LEFT);?></h3>
              <h5 class="widget-user-desc">Mozo: <?php echo ucwords(strtolower($row0[nombres].' '.$row0[paterno]));?></h5>
            </div>
            <div class="box-footer no-padding">
<?php
		@mysqli_query($con, "SET NAMES 'utf8'");
//		$consulta = mysqli_query($con, "select cant, descripcion, precio, cant*precio as total from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado<>9 order by id desc");
		
		$consulta = mysqli_query($con, "select cant, descripcion, comentario, precio, cant*precio as total, estado, destino, user_registro, fec_registro, user_modifica, fec_modifica, id from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado<>9 order by id desc");
		
		if (mysqli_num_rows($consulta) > 0){
?>

                    <div class="box-body no-padding">
                      <table class="table table-condensed">
                        <tr>
                          <th width="30" style="width: 10px">Cant</th>
                          <th width="871">Descripcion</th>
                          <th width="99" style="text-align:right">Total</th>
                          <th width="104" style="text-align:right">&nbsp;</th>
                        </tr>
									<?php
                                        $total = 0;
                                        while ($row = mysqli_fetch_array($consulta)){
                                            $total = $total + $row[total];


											if($row[estado]==1){
												$vlNomEst = "Cocinando";
												if($row[destino]=="BAR"){
													$vlNomEst = "Preparando";
												}
												$vlColor  = "danger";
											}
											if($row[estado]==2){
												$vlNomEst = "Atendido";
												$vlColor  = "warning";
											}
											if($row[estado]==3){
												$vlNomEst = "Por cobrar";
												$vlColor  = "primary";
											}
											if($row[estado]==9){
												$vlNomEst = "Anulado";
												$vlColor  = "danger";
											}
											
											
											$bgColor = "";
											if($row[estado]==0){
												$bgColor = "style='background-color:#FFD'";
											}
											if($row[estado]==9){
												$bgColor = "style='background-color:#FFE8E8'";
											}
                                    ?>
                                                            <tr>
                                                              <td style="text-align:center"><?php echo $row[cant];?></td>
                                                              <td><?php echo utf8_encode(ucfirst(strtolower($row[descripcion])));?></td>
                                                              <td style="text-align:right"><?php echo number_format($row[total], 2, '.', ',');?></td>
                                                              <td style="text-align:right"><span class="label label-<?php echo $vlColor;?>"><?php echo $vlNomEst;?></span></td>
                                                            </tr>
                                    <?php }?>
                                                          <tfoot>
                                                              <tr>
                                                                <th>&nbsp;</td>
                                                                <th style="text-align:right">Total:</td>
                                                                <th style="text-align:right"><?php echo number_format($total, 2, '.', ',');?></td>
                                                                <th>&nbsp;</td>
                                                              </tr>
                                                          </tfoot>
                                                          </table>
                                                        </div>
                                    <?php }?>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->







                <div class="col-md-6">
                  <div class="box box-<?php echo $vlColorLine;?>">
                    <div class="box-header">
                      <h3 class="box-title">Efectuar Cobranza</h3>
                    </div>
                    <div class="box-body">

            <form class="form-horizontal" id="form1" name="form1">
              <div class="box-body">


                <div class="form-group">
                  <label for="inputEmail3" style="text-align:left" class="col-sm-2 control-label">Comprobante:</label>

                  <div class="col-sm-5">
                      <select class="form-control" id="txtTipoDoc" name="txtTipoDoc" onChange="javascript:cambiar(this.value)" <?php if($vlOcupado<>4){?>disabled<?php }?> >
<?php
if($vlOcupado==4){
$consulta = mysqli_query($con, "select id_docuconta, nombre from man_docuconta order by id_docuconta");
if (mysqli_num_rows($consulta) > 0){
	$html .= '<option value=""></option>';
	while ($row = mysqli_fetch_array($consulta)){
?>
						<option value="<?php echo $row["id_docuconta"];?>" <?php if($row["id_docuconta"]==4){ echo "selected";}?>><?php echo strtoupper($row["nombre"]);?></option>
<?php }}}?>
                      </select>
                  </div>
                  <div class="col-sm-5">
                    <input type="textbox" class="form-control" id="txtNroDoc" name="txtNroDoc" disabled >
                  </div>
                </div>




                <div class="form-group">
                  <label for="inputEmail3" style="text-align:left" class="col-sm-2 control-label">Cliente:</label>

                  <div class="col-sm-10">
                    <select class="form-control select2" style="width: 100%;" id="cboCliente" name="cboCliente" disabled >
                    </select>
                  </div>
                </div>




                <div class="form-group">
                  <label for="inputEmail3" style="text-align:left" class="col-sm-2 control-label">Total pagar:</label>
                  <div class="col-sm-5">
                    <input type="number" class="form-control" id="txtPagar" name="txtPagar" style="text-align:right; font-size:19px; font-weight:700; color:#00F;" <?php if($vlOcupado==4){?>value="<?php echo number_format($total, 2, '.', ',');?>"<?php }?> disabled >
                  </div>

                  <div class="col-sm-5">
                        <div class="form-group">
                          <label for="inputEmail3" style="text-align:left" class="col-sm-5 control-label">Efectivo:</label>
                          <div class="col-sm-7">
                            <input type="number" class="form-control" id="txtEfectivo" style="text-align:right; font-size:19px; font-weight:700; color:#00F;" name="txtEfectivo" onBlur="javascript:sumar();" onKeyPress="return pulsar(event)" <?php if($vlOcupado<>4){?>disabled<?php }?> >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" style="text-align:left" class="col-sm-5 control-label">Visa:</label>
                          <div class="col-sm-7">
                            <input type="number" class="form-control" id="txtVisa" style="text-align:right; font-size:19px; font-weight:700; color:#00F;" name="txtVisa" onBlur="javascript:sumar();" onKeyPress="return pulsar(event)" <?php if($vlOcupado<>4){?>disabled<?php }?> >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" style="text-align:left" class="col-sm-5 control-label">Mastercard:</label>
                          <div class="col-sm-7">
                            <input type="number" class="form-control" id="txtMastercard" style="text-align:right; font-size:19px; font-weight:700; color:#00F;" name="txtMastercard" onBlur="javascript:sumar();" onKeyPress="return pulsar(event)" <?php if($vlOcupado<>4){?>disabled<?php }?> >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" style="text-align:left; color:#093;" class="col-sm-5 control-label">Tot. Pagado:</label>
                          <div class="col-sm-7">
                            <input type="textbox" class="form-control" id="txtPagado" style="text-align:right; font-size:19px; font-weight:700; color:#093;" name="txtPagado" disabled >
                          </div>
                        </div>
                        
                        <hr id="separa" style="display:none;">

                        <div class="form-group" id="fal" style="display:none;">
                          <label for="inputEmail3" style="text-align:left; color:#F00;" class="col-sm-5 control-label">Falta:</label>
                          <div class="col-sm-7">
                            <input type="textbox" class="form-control" id="txtFalta" style="text-align:right; font-size:19px; font-weight:700; color:#F00;" name="txtFalta" disabled >
                          </div>
                        </div>

                        
                        <div class="form-group" id="vue" style="display:none;">
                          <label for="inputEmail3" style="text-align:left; color:#F00;" class="col-sm-5 control-label">Vuelto:</label>
                          <div class="col-sm-7">
                            <input type="textbox" class="form-control" id="txtVuelto" style="text-align:right; font-size:19px; font-weight:700; color:#F00;" name="txtVuelto" disabled >
                          </div>
                        </div>




                  </div>
                </div>












              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" class="btn btn-default" style="cursor:pointer" onClick="javascript:location.href='pro_caja0.php'">Regresar</button>
                <?php if($vlOcupado==4){?><button type="button" class="btn btn-info pull-right" id="btnGrabar" data-toggle="modal" data-target="#modal-info" disabled >Efectuar Pago</button><?php }?>
              </div>
              <!-- /.box-footer -->
            </form>



        
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
        




        <div class="modal modal-info fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas EFECTUAR EL PAGO ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:grabar('<?php echo $idmesa;?>',document.form1.txtTipoDoc.value);">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


	<div id="grabacaja"></div>





      </div>
      </div>



    </section>




  </div>
  <!-- /.content-wrapper -->

  <?php include("inc_footer.php");?>
  <?php //include("inc_tool.php");?>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
	$('.select2').select2()
  })

  <?php if($vlOcupado==4){?>
	  document.form1.txtTipoDoc.onchange();
  <?php }?> 


	$("#txtNroDoc").keyup(function(){
		nombre1 = $("#txtNroDoc").val();
		nombre2 = $("#cboCliente").val();
		nombre3 = $("#txtPagar").val();
		nombre4 = $("#txtEfectivo").val();
		nombre5 = $("#txtVisa").val();
		nombre6 = $("#txtMastercard").val();

		var efectivo = 0;
		if(nombre4!=""){
			var efectivo = parseFloat(nombre4);
		}

		var visa = 0;
		if(nombre5!=""){
			var visa = parseFloat(nombre5);
		}
		
		var mastercard = 0;
		if(nombre6!=""){
			var mastercard = parseFloat(nombre6);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);


		if(nombre1.length>5 && nombre2.length>=8 && nombre3<=sumatoria){
			document.getElementById("btnGrabar").disabled = false;
		}else{
			document.getElementById("btnGrabar").disabled = true;
		}
	});


	$("#cboCliente").keyup(function(){
		nombre1 = $("#txtNroDoc").val();
		nombre2 = $("#cboCliente").val();
		nombre3 = $("#txtPagar").val();
		nombre4 = $("#txtEfectivo").val();
		nombre5 = $("#txtVisa").val();
		nombre6 = $("#txtMastercard").val();

		var efectivo = 0;
		if(nombre4!=""){
			var efectivo = parseFloat(nombre4);
		}

		var visa = 0;
		if(nombre5!=""){
			var visa = parseFloat(nombre5);
		}
		
		var mastercard = 0;
		if(nombre6!=""){
			var mastercard = parseFloat(nombre6);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);


		if(nombre1.length>5 && nombre2.length>=8 && nombre3<=sumatoria){
			document.getElementById("btnGrabar").disabled = false;
		}else{
			document.getElementById("btnGrabar").disabled = true;
		}
	});




	$("#txtEfectivo").keyup(function(){
		nombre1 = $("#txtNroDoc").val();
		nombre2 = $("#cboCliente").val();
		nombre3 = $("#txtPagar").val();
		nombre4 = $("#txtEfectivo").val();
		nombre5 = $("#txtVisa").val();
		nombre6 = $("#txtMastercard").val();

		var efectivo = 0;
		if(nombre4!=""){
			var efectivo = parseFloat(nombre4);
		}

		var visa = 0;
		if(nombre5!=""){
			var visa = parseFloat(nombre5);
		}
		
		var mastercard = 0;
		if(nombre6!=""){
			var mastercard = parseFloat(nombre6);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);


		if(nombre1.length>5 && nombre2.length>=8 && nombre3<=sumatoria){
			document.getElementById("btnGrabar").disabled = false;
		}else{
			document.getElementById("btnGrabar").disabled = true;
		}
	});



	$("#txtVisa").keyup(function(){
		nombre1 = $("#txtNroDoc").val();
		nombre2 = $("#cboCliente").val();
		nombre3 = $("#txtPagar").val();
		nombre4 = $("#txtEfectivo").val();
		nombre5 = $("#txtVisa").val();
		nombre6 = $("#txtMastercard").val();

		var efectivo = 0;
		if(nombre4!=""){
			var efectivo = parseFloat(nombre4);
		}

		var visa = 0;
		if(nombre5!=""){
			var visa = parseFloat(nombre5);
		}
		
		var mastercard = 0;
		if(nombre6!=""){
			var mastercard = parseFloat(nombre6);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);


		if(nombre1.length>5 && nombre2.length>=8 && nombre3<=sumatoria){
			document.getElementById("btnGrabar").disabled = false;
		}else{
			document.getElementById("btnGrabar").disabled = true;
		}
	});


	$("#txtMastercard").keyup(function(){
		nombre1 = $("#txtNroDoc").val();
		nombre2 = $("#cboCliente").val();
		nombre3 = $("#txtPagar").val();
		nombre4 = $("#txtEfectivo").val();
		nombre5 = $("#txtVisa").val();
		nombre6 = $("#txtMastercard").val();

		var efectivo = 0;
		if(nombre4!=""){
			var efectivo = parseFloat(nombre4);
		}

		var visa = 0;
		if(nombre5!=""){
			var visa = parseFloat(nombre5);
		}
		
		var mastercard = 0;
		if(nombre6!=""){
			var mastercard = parseFloat(nombre6);
		}
	
		var sumatoria = parseFloat(efectivo + visa + mastercard);


		if(nombre1.length>5 && nombre2.length>=8 && nombre3<=sumatoria){
			document.getElementById("btnGrabar").disabled = false;
		}else{
			document.getElementById("btnGrabar").disabled = true;
		}
	});



</script>
</body>
</html>