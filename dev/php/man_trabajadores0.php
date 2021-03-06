<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$txtBuscar = trim($_POST[txtBuscar]);

	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
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
</head>
<body class="hold-transition skin-purple sidebar-collapse" onLoad="javascript:document.form1.txtBuscar.focus();">
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
        Tabla de Trabajadores
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> Tablas Generales</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><a href="man_trabajadores1.php" class="btn btn-sm btn-info">Agregar Trabajador</a></h3>
              <div class="box-tools">
              <form id="form1" name="form1" method="post">
                <div class="input-group input-group-sm" style="width: 150px;">

                  <input type="text" name="txtBuscar" id="txtBuscar" class="form-control pull-right" placeholder="Filtrar" autocomplete="off" value="<?php echo $txtBuscar;?>" >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
<?php
	$vlCad01 = "";
	if(!empty($txtBuscar)){
		$vlCad01 = " and (a.paterno like '%$txtBuscar%' or materno like '%$txtBuscar%' or a.nombres like '%$txtBuscar%' or a.nro_doc like '%$txtBuscar%' or a.direccion like '%$txtBuscar%' or a.distrito like '%$txtBuscar%' or a.telefono like '%$txtBuscar%')";
	}
	@mysqli_query($con, "SET NAMES 'utf8'");
	$consulta = mysqli_query($con, "select a.id_sucursal, a.id_trabajador, a.paterno, a.materno, a.nombres, a.fecha_nac, b.nombre as nom_tipodoc, a.nro_doc, a.direccion, a.distrito, a.telefono, a.correo, c.nombre as nom_cargo, a.estado, a.usuario, a.clave from man_trabajadores as a left JOIN man_tipodocumentos as b on a.id_tipodoc = b.id_tipodocumento left join man_cargos as c on a.id_cargo = c.id_cargo where a.id_empresa=1".$vlCad01." order by a.paterno, a.materno, a.nombres");
	if (mysqli_num_rows($consulta) == 0){
//		echo "<span>No se encontraron registros.</span>";
	}else{
		$XDETASUCU = array();
		$vlSqlCadx = mysqli_query($con, "select id_sucursal, nombre from man_sucursales WHERE id_empresa='$vgCodEmp' order by nombre");
		while($reg = mysqli_fetch_array($vlSqlCadx)){
			$XDETASUCU[$reg[id_sucursal]]["nombre"] = $reg[nombre];
		}

?>
              <table id="example1" class="table table-hover">
                <thead>
                <tr>
                  <th width="22%">Nombres completos</th>
                  <th width="7%">Tipo</th>
                  <th width="8%">Nro Doc.</th>
                  <th width="14%">Sucursal</th>
                  <th width="20%">Dirección</th>
                  <th width="9%">Distrito</th>
                  <th width="8%">Telefono</th>
                  <th width="12%">Cargo</th>
                  </tr>
                </thead>
                <tbody>
<?php
	while ($row = mysqli_fetch_array($consulta)){
		$vlNomSucu = $XDETASUCU[$row[id_sucursal]][nombre];
		if(empty($vlNomSucu)){
			$vlNomSucu = "TODOS";
		}
?>
                <tr onClick="javascript:location.href='man_trabajadores1.php?id=<?php echo $row[id_trabajador];?>';" style="cursor:pointer">
                  <td><?php echo $row[paterno].' '.$row[materno].', '.$row[nombres];?></td>
                  <td><?php echo $row[nom_tipodoc];?></td>
                  <td><?php echo $row[nro_doc];?></td>
                  <td><?php echo $vlNomSucu;?></td>
                  <td><?php echo $row[direccion];?></td>
                  <td><?php echo $row[distrito];?></td>
                  <td><?php echo $row[telefono];?></td>
                  <td><?php echo $row[nom_cargo];?></td>
                  </tr>
<?php }?>
                </tbody>
              </table>
<?php }?>
            </div>
            <!-- /.box-body -->
          </div>
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