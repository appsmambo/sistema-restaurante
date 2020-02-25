<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
  $vlMesa	  = $_GET["m"];
  $vgFlujo  = $_SESSION["flujo"];
	$agregado = 0;
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
  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
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
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }


</style>

<script language="javascript">
	function cambiar(a,b){
		document.getElementById("textito").innerHTML = b;
		id_category = a;
		$.post("../sql/cbo_platos.php", { id_category: id_category }, function(data){
			$("#cboPlatos").html(data);
		});  
	}
	function agregar(a){
		var variable_post = a;
		var b = 1;
		$.post("../sql/grd_pedidos.php", { variable: variable_post , mesa: <?php echo $vlMesa;?> , agregado: b }, function(data){
		$("#pedido").html(data);
		});
	}
	function editar_cantidad(a,b,c){
		var variable_post = a;	// cantidad
		var codsec = b; 		// cod secuencia
		var precio = c; 		// precio
		var total  = (a*c).toFixed(2);
		document.getElementById("total"+b).innerHTML = total;
		
		$.post("../sql/pedidos_editar.php", { variable: variable_post , secuencia: codsec }, function(data){
		$("#editarcantidad").html(data);
		});
	}
	function confirmar(a){
		var variable_post = a;
		$.post("../sql/pedidos_confirmar.php", { variable: variable_post , mesa: <?php echo $vlMesa;?> }, function(data){
		$("#confirmar").html(data);
		});
	}
	function cerrar(a){
		var variable_post = a;
		$.post("../sql/pedidos_cerrar.php", { variable: variable_post , mesa: <?php echo $vlMesa;?> }, function(data){
		$("#cerrar").html(data);
		});
	}
	function abrir(a){
		var variable_post = a;
		$.post("../sql/pedidos_abrir.php", { variable: variable_post , mesa: <?php echo $vlMesa;?> }, function(data){
		$("#cerrar").html(data);
		});
	}
  function imprimir(){
    var divToPrint = document.getElementById('precuenta');
        var popupWin = window.open('', '_blank', 'width=600,height=400');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
        popupWin.focus();
        return true;
  }
	function quitar(){
		var variable_post = document.getElementById("txtId").value;
		var estado = document.getElementById("txtEst").value;
		$.post("../sql/pedidos_quitaritem.php", { variable: variable_post , estado: estado , mesa: <?php echo $vlMesa;?> }, function(data){
		$("#quitaritem").html(data);
		});
	}
	function puntear(a,b){
		document.getElementById("txtId").value  = a;
		document.getElementById("txtEst").value = b;
		document.getElementById("txtMes").value = <?php echo $vlMesa;?>;
	}
	function marcar(a){
		document.getElementById("txtLlave").value = a;
		document.getElementById("txtObservacion").value = "";
	}
	function comentar(){
		a = document.getElementById("txtLlave").value;
		b = document.getElementById("txtObservacion").value;

		var variable_post = b;	// observacion
		var codsec = a; 		// cod secuencia
		document.getElementById("txtComentario"+a).innerHTML = variable_post;
		
		$.post("../sql/pedidos_comentar.php", { variable: variable_post , secuencia: codsec }, function(data){
		$("#comentar").html(data);
		});

	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body class="hold-transition skin-purple sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("inc_header.php");?>
  <?php include("inc_menu.php");?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mesa seleccionada: <?php echo str_pad($vlMesa, 2, "0", STR_PAD_LEFT);?>
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <?php echo "&nbspFlujo para hoy: &nbsp&nbsp".$_SESSION["nomflujo"];?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">


          <!-- Application buttons -->
          <div class="box">
          <input type="hidden" name="txtLlave" id="txtLlave" value="">
                    <input type="hidden" name="txtId" id="txtId" value="">
                    <input type="hidden" name="txtEst" id="txtEst" value="">
                    <input type="hidden" name="txtMes" id="txtMes" value="">
				<?php
                    $consulta = mysqli_query($con, "select ocupado from man_mesas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'");
//                    $abierto = mysqli_num_rows($consulta);
					$row = mysqli_fetch_array($consulta);
					$ocupado = $row[ocupado];
					if($ocupado<>4){
                ?>
            <div class="box-body">
                  <p>Seleccione plato</p>

              <div class="input-group input-group-lg">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="height:34px; line-height:5px"><div id="textito">Todos</div></button>
                  <ul class="dropdown-menu">
				<?php
                    @mysqli_query($con, "SET NAMES 'utf8'");
                    $consulta = mysqli_query($con, "select id_tipoproducto, nombre from man_tipoproductos order by nombre");
                    if (mysqli_num_rows($consulta) > 0){
                ?>
                    <li><a href="javascript:cambiar('0','Todos')">Todos</a></li>
                    <li class="divider"></li>
				<?php while ($row = mysqli_fetch_array($consulta)){?>
                    <li><a href="javascript:cambiar('<?php echo $row[id_tipoproducto];?>','<?php echo utf8_encode(ucfirst(strtolower($row[nombre])));?>')"><?php echo utf8_encode(ucfirst(strtolower($row[nombre])));?></a></li>
				<?php }}?>
                  </ul>
                </div>
                <!-- /btn-group -->
                <select class="form-control select2" style="width: 100%;" onChange="javascript:agregar(this.value)" id="cboPlatos" name="cboPlatos" >
                <option value=""></option>
				<?php
                    $consulta = mysqli_query($con, "select a.id_producto, a.nombre, a.precio, b.destino from man_productos as a left join man_tipoproductos as b on a.id_tipoproducto = b.id_tipoproducto where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and a.estado=0 order by a.nombre");
                    if (mysqli_num_rows($consulta) > 0){
                        while ($row = mysqli_fetch_array($consulta)){
                ?>
                  <option value="<?php echo $row[id_producto].'|'.$row[nombre].'|'.$row[precio].'|'.$row[destino];?>"><?php echo strtoupper($row[nombre])."&nbsp&nbsp&nbsp(S/ ".$row[precio].")";?></option>
				<?php }}?>
                </select>
              </div>
              <?php }?>

			<div id="pedido">
				  <?php include("../sql/grd_pedidos.php");?>
      </div>


            <!-- /.box-body -->
          </div>
          <!-- /.box -->

            </div>
            <!-- /.box-body -->
          </div>

          <div id="precuenta">
            <?php if($imprimir==1){?>
              <?php include("imp_precuenta.php");?>
            <?php }?>
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
      <!-- /.row -->



        <div class="modal fade" id="modal-obsv">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Comentario Adicional</h4>
              </div>
              <div style="background-color:#FFFFFF;">
                <br>
                <div align="center"><input type="text" class="form-control" id="txtObservacion" placeholder="Escriba aqui" style="width:95%"></div>
                <br>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left" data-dismiss="modal" onClick="javascript:comentar();">&nbsp;&nbsp;&nbsp;Ok&nbsp;&nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <div class="modal modal-info fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas CONFIRMAR los pedidos de la mesa?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:confirmar('<?php echo $ocupado;?>');">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <div class="modal modal-success fade" id="modal-success">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas CERRAR LA MESA ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:cerrar('<?php echo $vlCad01;?>');">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Salir</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <div class="modal modal-info fade" id="modal-abrir">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas ABRIR LA MESA NUEVAMENTE ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:abrir('<?php echo $vlCad01;?>');">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->



        <div class="modal modal-danger fade" id="modal-danger">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmación</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas ANULAR el item ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:quitar();">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div id="confirmar"></div>
        <div id="cerrar"></div>
        <div id="quitaritem"></div>
        <div id="editarcantidad"></div>
        <div id="comentar"></div>

    </section>
    <!-- /.content -->
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
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
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
</script>
</body>
</html>