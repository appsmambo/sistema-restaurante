<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
  $vgCodSuc = $_SESSION["vgCodSuc"];
  $vgFlujo  = $_SESSION["flujo"];
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
        Emitir Comprobantes
        <small>&nbsp;</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo "&nbspFlujo para hoy: &nbsp&nbsp".$_SESSION["nomflujo"];?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


      <div class="row">


		<?php
            @mysqli_query($con, "SET NAMES 'utf8'");
            @mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");

            if($vgFlujo==1){
              $consulta = mysqli_query($con, "select a.id_mesa, b.ocupado, b.user_ocupado, c.nombres, c.paterno, c.materno, sum(a.cant * a.precio) as total from tmp_pedidos as a left join man_mesas as b on a.id_mesa = b.id_mesa left join man_trabajadores as c on a.user_registro = c.usuario where a.id_empresa='$vgCodEmp' and b.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and b.id_sucursal='$vgCodSuc' and a.estado<>9 and b.ocupado=4 group by a.id_mesa, b.ocupado, b.user_ocupado, c.nombres, c.paterno, c.materno");
            }

            if($vgFlujo==2){
              $consulta = mysqli_query($con, "select a.id_mesa, b.ocupado, b.user_ocupado, c.nombres, c.paterno, c.materno, sum(a.cant * a.precio) as total from tmp_pedidos as a left join man_mesas as b on a.id_mesa = b.id_mesa left join man_trabajadores as c on a.user_registro = c.usuario where a.id_empresa='$vgCodEmp' and b.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and b.id_sucursal='$vgCodSuc' and a.estado=3 and b.ocupado=4 group by a.id_mesa, b.ocupado, b.user_ocupado, c.nombres, c.paterno, c.materno");
            }


            if (mysqli_num_rows($consulta) > 0){
				while ($row = mysqli_fetch_array($consulta)){
					$vlColorBusy = ""; $icono = "";
					if($row[ocupado]==1){
						$vlColorBusy = "red";
						$icono = "fa-hourglass-o";
					}
					if($row[ocupado]==2){
						$vlColorBusy = "yellow";
						$icono = "fa-hourglass-end";
					}
					if($row[ocupado]==3){
						$vlColorBusy = "green";
						$icono = "fa-hourglass";
					}
					if($row[ocupado]==4){
						$vlColorBusy = "blue";
						$icono = "fa-credit-card";
					}
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12" style="cursor:pointer" onClick="javascript:location.href = 'pro_ventas0.php?idmesa=<?php echo $row[id_mesa];?>';">
          <div class="info-box bg-<?php echo $vlColorBusy;?>">
            <span class="info-box-icon"><i class="fa <?php echo $icono;?>"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Mesa <?php echo str_pad($row[id_mesa], 2, "0", STR_PAD_LEFT);?></span>
              <span class="info-box-number">S/ <?php echo number_format($row[total], 2, '.', ',');?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
                  <span class="progress-description">
                    Mozo: <?php echo ucwords(strtolower($row[nombres].' '.$row[paterno]));?>
                  </span>
            </div>
          </div>
        </div>
		<?php }}?>






      </div>
      <!-- /.row -->

      <!-- =========================================================== -->



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