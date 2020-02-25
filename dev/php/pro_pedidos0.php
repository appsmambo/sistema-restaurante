<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
  $vgCodSuc = $_SESSION["vgCodSuc"];

  setlocale(LC_ALL, 'es_ES');
  $vlFecha  = strftime("%d/%m/%Y");
  $vlFecha_formateada = strftime("%Y-%m-%d");
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
  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
    /* FROM HTTP://WWW.GETBOOTSTRAP.COM
     * Glyphicons
     *
     * Special styles for displaying the icons and their classes in the docs.
     */

    .bs-glyphicons {
      padding-left: 0;
      padding-bottom: 1px;
      margin-bottom: 20px;
      list-style: none;
      overflow: hidden;
    }

    .bs-glyphicons li {
      float: left;
      width: 25%;
      height: 80px;
      padding: 10px;
      margin: 0 -1px -1px 0;
      font-size: 15px;
      line-height: 1.4;
      text-align: center;
      border: 1px solid #ddd;
    }

    .bs-glyphicons .glyphicon {
      margin-top: 5px;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .bs-glyphicons .glyphicon-class {
      display: block;
      text-align: center;
      word-wrap: break-word; /* Help out IE10+ with class names */
    }

    .bs-glyphicons li:hover {
      background-color: rgba(86, 61, 124, .1);
    }

    @media (min-width: 768px) {
      .bs-glyphicons li {
        width: 12.5%;
      }
    }
  </style>

</head>
<body class="hold-transition skin-purple sidebar-collapse" onLoad="setInterval('location.reload()',20000)">
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
        Registro de Comandas
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <?php echo "&nbspFlujo para hoy: &nbsp&nbsp".$_SESSION["nomflujo"];?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
<?php
    //-consultar si la caja esta abierta------------------------
    $consulta1 = mysqli_query($con, "select id_apertura, estado from pro_aperturacajas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and fecha='$vlFecha_formateada' and estado=0");
    if (mysqli_num_rows($consulta1) > 0){                                                     
?>

          <!-- Application buttons -->
          <div class="box">
            <div class="box-body">
			<?php
                @mysqli_query($con, "SET NAMES 'utf8'");
				@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
                $consulta = mysqli_query($con, "select id_mesa, ocupado, fec_ocupado, id, TIMESTAMPDIFF(minute, fec_ocupado, now()) as minutos from man_mesas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' order by id_mesa");
                if (mysqli_num_rows($consulta) == 0){
			?>
                    <p>No se encontraron mesas.</p>
			<?php
                }else{
            ?>
                  <p>Seleccione una mesa:</p>
                <ul class="bs-glyphicons">
			<?php
                while ($row = mysqli_fetch_array($consulta)){
					$vlColorBusy = "";
					if($row[ocupado]==1){
						$vlColorBusy = " background-color:#DD4B39; color:#FFF;";
					}
					if($row[ocupado]==2){
						$vlColorBusy = " background-color:#F39C12; color:#FFF;";
					}
					if($row[ocupado]==3){
						$vlColorBusy = " background-color:#00A65A; color:#FFF;";
					}
					if($row[ocupado]==4){
						$vlColorBusy = " background-color:#3C8DBC; color:#FFF;";
					}
            ?>
                  <li style="cursor:pointer; <?php echo $vlColorBusy;?>" onClick="javascript:location.href='pro_pedidos1.php?m=<?php echo $row[id_mesa];?>'">
                  	<?php if($row[ocupado]==0){?>
                    <span class="glyphicon glyphicon-cutlery"></span>
                    <?php }?>
                    <?php if($row[ocupado]>=1 && $row[ocupado]<=2){?>
                    <small class="label label-oclock"><i class="fa fa-clock-o"></i> <?php echo $row[minutos];?> min</small>
                    <?php }?>
                  	<?php if($row[ocupado]==3){?>
                    <span class="glyphicon glyphicon-ok"></span>
                    <?php }?>
                  	<?php if($row[ocupado]==4){?>
                    <span class="glyphicon glyphicon-credit-card"></span>
                    <?php }?>
                    <span class="glyphicon-class"><?php echo str_pad($row[id_mesa], 2, "0", STR_PAD_LEFT);?></span>
                  </li>
			<?php }}?>
                </ul>


                        <div class="form-group has-error">
                            <label for="inputError"><i class="fa fa-spinner"></i> En espera</label>
                        </div>
                        <div class="form-group has-warning">
                            <label for="inputWarning"><i class="fa fa-hand-stop-o"></i> Atención parcial</label>
                        </div>
                        <div class="form-group has-success">
                            <label for="inputSuccess"><i class="fa fa-thumbs-o-up"></i> Atención total</label>
                        </div>
                        <div class="form-group has-information">
                            <label for="information"><i class="fa fa-dollar"></i> &nbsp;En caja</label>
                        </div>


            </div>
            <!-- /.box-body -->
          </div>
          <?php }else{?>
            <script language="JavaScript">
              //swal("Caja cerrada", "", "error");
              swal("La caja se encuentra cerrada.");
            </script>
          <?php }?>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

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
  })
</script>
</body>
</html>