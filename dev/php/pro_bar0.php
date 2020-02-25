<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
  $vgDestino = "BAR";

  setlocale(LC_ALL, 'es_ES');
  $vlFecha  = strftime("%d/%m/%Y");
  $vlFecha_formateada = strftime("%Y-%m-%d");

  $vlAno = strftime("%Y");
  $vlMes = strftime("%m");
  $vlDia = strftime("%d");
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
  <!-- This is what you need -->
  <script src="../bower_components/bootstrap-sweetalert/dist/sweetalert.js"></script>
  <link rel="stylesheet" href="../bower_components/bootstrap-sweetalert/dist/sweetalert.css">
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<script language="javascript">
	function despachar(a,b){
		var variable_post = a;
		var id_mesa = b;
		$.post("../sql/pedidos_despachar.php", { variable: variable_post , mesa: id_mesa , destino: '<?php echo $vgDestino;?>' }, function(data){
		$("#despachar").html(data);
		});
	}
</script>
</head>
<body class="hold-transition skin-purple sidebar-collapse" onLoad="setInterval('location.reload()',20000)">
<div class="wrapper">

  <?php include("inc_header.php");?>
  <?php include("inc_menu.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Bar
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo "&nbspFlujo para hoy: &nbsp&nbsp".$_SESSION["nomflujo"];?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
      <?php
        $ancho = 12; $anchoventana = 3;
        //-consultar los platos atendidos------------------------
        $consulta00 = mysqli_query($con, "select cant, descripcion, comentario, precio, fec_registro, fec_prepara, user_prepara, TIMESTAMPDIFF(minute, fec_registro, fec_prepara) as demora, DATE_FORMAT(fec_prepara, '%H:%i') as hora, id_mesa from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and year(fec_registro)='$vlAno' and month(fec_registro)='$vlMes' and day(fec_registro)='$vlDia' and destino='$vgDestino' AND (estado=2 or estado=9) order by fec_prepara desc");
        if (mysqli_num_rows($consulta00) > 0){
          $ancho = 9;
          $anchoventana = 4;
      ?>
        <div class="col-md-3">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-cutlery"></i>&nbsp Bebidas atendidas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <?php              
                while ($row00 = mysqli_fetch_array($consulta00)){
              ?>
              <strong class="text-blue" title="<?php echo utf8_encode($row00["comentario"]);?>"><?php echo '('.$row00["cant"].') '.utf8_encode($row00["descripcion"]);?></strong><br>
              <div class="pull-right text-gray">
                <span class="time" title="Nro Mesa"><i class="fa fa-bookmark"></i> <?php echo "Mesa ". str_pad($row00["id_mesa"], 2, "0", STR_PAD_LEFT);?></span>&nbsp|&nbsp
                <span class="time" title="Barman que despacho"><i class="fa fa-user"></i> <?php echo $row00["user_prepara"];?></span>&nbsp|&nbsp
                <span class="time" title="Hora de despacho"><i class="fa fa-clock-o"></i> <?php echo $row00["hora"];?></span>&nbsp|&nbsp
                <span class="time" title="Tiempo que tomo para atender la bebida"><i class="fa fa-hourglass-2 "></i> <?php echo $row00["demora"];?> min</span>
              </div>
              <hr>
              <?php }?>




            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      <?php }?>


      <div class="col-md-<?php echo $ancho;?>">
      <?php
        //-consultar si la caja esta abierta------------------------
        $consulta1 = mysqli_query($con, "select id_apertura, estado from pro_aperturacajas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and fecha='$vlFecha_formateada' and estado=0");
        if (mysqli_num_rows($consulta1) > 0){
      ?>
		<?php
			//-Pedidos del temporal-------------------------
			$vlSqlTmpPedidosx = mysqli_query($con, "select a.id_mesa, a.cant, a.id_producto, a.descripcion, a.comentario, TIMESTAMPDIFF(minute, a.fec_registro, now()) as minutos, a.user_registro, b.nombres, b.paterno, b.materno, a.id, a.precio from tmp_pedidos as a left join man_trabajadores as b on a.user_registro = b.usuario where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and a.estado=1 and a.destino='$vgDestino' order by a.id_mesa, a.descripcion");
			//--------------------------------------------

            @mysqli_query($con, "SET NAMES 'utf8'");
            @mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
            $consulta = mysqli_query($con, "select id_mesa, TIMESTAMPDIFF(minute, min(fec_registro), now()) as minutos, count(*) as total from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and estado=1 and destino='$vgDestino' group by id_mesa order by 2 desc");
            if (mysqli_num_rows($consulta) > 0){

				while ($row = mysqli_fetch_array($consulta)){
					$idMesa = $row[id_mesa];
        ?>
        <div class="col-md-<?php echo $anchoventana;?>" id="M<?php echo $idMesa;?>">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
<?php
		$r = 0;
		mysqli_data_seek($vlSqlTmpPedidosx, 0);
		while($regTmp = mysqli_fetch_array($vlSqlTmpPedidosx)){
			$vlLlave1 = trim($regTmp[id_mesa]);
			if($vlLlave1 == $idMesa){
					$r = $r + 1;
					if($r==1){
						$color="";
						if($row[minutos]<=10){
							$color="green";
						}
						if($row[minutos]>10 && $row[minutos]<=15){
							$color="yellow";
						}
						if($row[minutos]>15){
							$color="red";
						}


						$nombre_foto = "../dist/img/user/".strtoupper($regTmp[user_registro]).".jpg";
						if(!file_exists($nombre_foto)) {
							$nombre_foto = "../dist/img/user/SINFOTO.jpg";
						}
?>
            <div class="widget-user-header bg-<?php echo $color;?>">
              <div class="widget-user-image">
                <img class="img-circle" src="<?php echo $nombre_foto;?>" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username" style="font-weight:600">Mesa <?php echo str_pad($vlLlave1, 2, "0", STR_PAD_LEFT);?> &nbsp;&nbsp;&nbsp;&nbsp;<small class="label label-oclock" style="font-size:14px"><i class="fa fa-clock-o"></i> <?php echo $row[minutos];?> min</small></h3>
              <h5 class="widget-user-desc">Mozo: <?php echo ucwords(strtolower($regTmp[nombres].' '.$regTmp[paterno]));?></h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
					
<?php
					}
?>
                <li id="<?php echo $regTmp[id];?>"><a href="#" onDblClick="javasccript:despachar(<?php echo $regTmp[id];?>,<?php echo $vlLlave1;?>)"><?php echo '('.$regTmp[cant].') '.utf8_encode($regTmp[descripcion])." <span style='color:#605CA8'><b>(S/ ".$regTmp[precio].")</b></span>";;?><?php echo "<div style='color:#00F; font-weight:600'>".$regTmp[comentario]."</div>";?></a></li>
<?php }}?>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <?php }}?>



        <div class="modal modal-info fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas DESPACHAR ?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:despachar(1);">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->



		<div id="despachar"></div>


          <?php }else{?>
            <script language="JavaScript">
              //swal("Caja cerrada", "", "error");
              swal("La caja se encuentra cerrada.");
            </script>
          <?php }?>

      </div>
      </div>
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
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>