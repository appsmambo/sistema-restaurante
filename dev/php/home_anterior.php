<?php
	session_start();
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
  <!-- jvectormap -->
  <link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
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
<body class="hold-transition skin-purple sidebar-collapse">
<div class="wrapper">

  <?php include("inc_header.php");?>
  <?php include("inc_menu.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Movimientos
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> Reporte Estadisticos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">VENTAS DEL DIA</span>
              <span class="info-box-number">S/ 120.50</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">COMPRAS DEL MES</span>
              <span class="info-box-number">S/ 2,750.80</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">VENTAS DEL MES</span>
              <span class="info-box-number">S/ 5,278.50</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Cant. Clientes</span>
              <span class="info-box-number">10</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Consumo de Clientes</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Rango: 01-Ene-2017 / 12-Dic-2017</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 180px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <p class="text-center">
                    <strong>Ranking de ventas</strong>
                  </p>

                  <div class="progress-group">
                    <span class="progress-text">Arroz Chaufa de Mariscos</span>
                    <span class="progress-number"><b>30</b>/100</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: 30%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Ceviche de Pollo</span>
                    <span class="progress-number"><b>45</b>/100</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: 45%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Ceviche de Pescado</span>
                    <span class="progress-number"><b>35</b>/100</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: 35%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Combo Arrocero</span>
                    <span class="progress-number"><b>90</b>/100</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Comandas Online</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th width="13%">Nro Pedido</th>
                    <th width="35%">Cliente</th>
                    <th width="15%">Mesa</th>
                    <th width="18%">Estado</th>
                    <th width="19%">Por cobrar</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>OR0001</td>
                    <td>Diego Angeldones</td>
                    <td>Mesa 01</td>
                    <td><span class="label label-warning">Cocinando</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">S/ 83.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td>OR0002</td>
                    <td>Fredy Cangalaya</td>
                    <td>Mesa 09</td>
                    <td><span class="label label-success">Atendido</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">S/ 55.50</div>
                    </td>
                  </tr>
                  <tr>
                    <td>OR0003</td>
                    <td>Karen Hurtado</td>
                    <td>Mesa 15</td>
                    <td><span class="label label-success">Atendido</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f56954" data-height="20">S/ 25.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td>OR0004</td>
                    <td>Walter Jauregui</td>
                    <td>Mesa 17</td>
                    <td><span class="label label-warning">Cocinando</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00c0ef" data-height="20">S/ 150.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td>OR0005</td>
                    <td>Carlos Delucio</td>
                    <td>Mesa 18</td>
                    <td><span class="label label-warning">Cocinando</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">S/ 55.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td>OR0006</td>
                    <td>Jorge Briceño</td>
                    <td>Mesa 19</td>
                    <td><span class="label label-success">Atendido</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f56954" data-height="20">S/ 75.00</div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Ir a cocina</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las comandas</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Ranking por Sucursal</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="../dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Sucursal Ucayali
                      <span class="label label-success pull-right">S/ 12,650.50</span></a>
                    <span class="product-description">
                          Jr. Uayali 443 - Int 21
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="../dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Sucursal Los olivos
                      <span class="label label-success pull-right">S/ 7,500.00</span></a>
                    <span class="product-description">
                          Av. Thomas Valle 3548
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="../dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Sucursal Abancay<span
                        class="label label-success pull-right">S/ 8,750.00</span></a>
                    <span class="product-description">
                          Jr. Puno 452
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="../dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Sucursal Jesus Maria
                      <span class="label label-success pull-right">S/ 12,850.00</span></a>
                    <span class="product-description">
                          Av. Garzon 1251 Int 2
                        </span>
                  </div>
                </li>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">Ver mas sucursales</a>
            </div>
            <!-- /.box-footer -->
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
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="../bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
