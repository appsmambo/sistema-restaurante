<?php
	session_start();
	include("sys_seguridad.php");
	include("../sql/conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$id = $_GET[id];
	if($id > 0){

		@mysqli_query($con, "SET NAMES 'utf8'");
		$consulta = mysqli_query($con, "select * from man_trabajadores where id_trabajador='$id'");
		if(mysqli_num_rows($consulta) > 0){
			$row = mysqli_fetch_array($consulta);
			$id_sucursal = $row[id_sucursal];
			$paterno     = $row[paterno];
			$materno     = $row[materno];
			$nombres     = $row[nombres];
			$fecha_nac   = $row[fecha_nac];
			$id_tipodoc  = $row[id_tipodoc];
			$nro_doc     = $row[nro_doc];
			$direccion	 = $row[direccion];
			$distrito    = $row[distrito];
			$telefono    = $row[telefono];
			$correo 	 = $row[correo];
			$id_cargo	 = $row[id_cargo];
			$estado		 = $row[estado];
			$usuario	 = $row[usuario];
			$clave		 = $row[clave];
		}
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
  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
	function eliminar(a){
		location.href = "../sql/trabajadores_eliminar.php?id="+a;
	}

	function busca_trabajadores1(a,b,c){
		$.ajax({
			url: "../sql/trabajadores_existe1.php",
			type: "POST",
			data: "tipo="+a+"&valor="+b+"&id="+c,
			success: function(resp){
			$('#resultado').html(resp)
			}       
		});
	}

	function busca_trabajadores2(a,b){
		$.ajax({
			url: "../sql/trabajadores_existe2.php",
			type: "POST",
			data: "&valor="+a+"&id="+b,
			success: function(resp){
			$('#resultado2').html(resp)
			}       
		});
	}

</script>
</head>
<body class="hold-transition skin-purple sidebar-collapse" onLoad="javascript:document.form1.txtPaterno.focus();">
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

    <!-- SACAR -->
    <section class="content">
      

      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <!--<div class="box-header with-border">
              <h3 class="box-title">Nuevo Cliente</h3>
            </div>-->
            
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="form1" name="form1" action="../sql/trabajadores_grabar.php" method="post">
              <div class="box-body">

                <div class="form-group">
                  <label for="exampleInputEmail1">Apellido Paterno (*)</label>
                  <input type="text" class="form-control" id="txtPaterno" name="txtPaterno" value="<?php echo $paterno;?>" placeholder="" style="text-transform:uppercase" autocomplete="off" >
                </div>


                <div class="form-group">
                  <label for="exampleInputEmail1">Apellido Materno (*)</label>
                  <input type="text" class="form-control" id="txtMaterno" name="txtMaterno" value="<?php echo $materno;?>" placeholder="" style="text-transform:uppercase" autocomplete="off" >
                </div>


                <div class="form-group">
                  <label for="exampleInputEmail1">Nombres (*)</label>
                  <input type="text" class="form-control" id="txtNombres" name="txtNombres" value="<?php echo $nombres;?>" placeholder="" style="text-transform:uppercase" autocomplete="off" >
                </div>

                <div class="form-group">
                  <label>Tipo Doc. (*)</label>
                  <select class="form-control select2" id="cboTipoDoc" name="cboTipoDoc" style="width: 100%;" >
					<?php
						@mysqli_query($con, "SET NAMES 'utf8'");
						$consulta = mysqli_query($con, "select id_tipodocumento, nombre from man_tipodocumentos order by id_tipodocumento");
						if (mysqli_num_rows($consulta) > 0){
							while ($row = mysqli_fetch_array($consulta)){
                    ?>
			                    <option value="<?php echo $row[id_tipodocumento];?>" <?php if($id_tipodoc==$row[id_tipodocumento]){ echo 'selected';}?>><?php echo strtoupper($row[nombre]);?></option>
                    <?php }}?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Nro Doc. (*)</label>
                  <input type="number" class="form-control" id="txtNroDoc" name="txtNroDoc" value="<?php echo $nro_doc;?>" autocomplete="off" onBlur="busca_trabajadores1(document.getElementById('cboTipoDoc').value, document.getElementById('txtNroDoc').value,<?php echo $id;?>);" >
                      <div id="resultado" class="text-red"></div>
                </div>


                <div class="form-group">
                  <label>Sucursal (*)</label>
                  <select class="form-control select2" id="cboSucursal" name="cboSucursal" style="width: 100%;" >
                  <option value="0" <?php if($id_tipodoc==$row[id_tipodocumento]){ echo 'selected';}?>>TODAS LAS SUCURSALES</option>
					<?php
						@mysqli_query($con, "SET NAMES 'utf8'");
						$consulta = mysqli_query($con, "select id_sucursal, nombre from man_sucursales WHERE id_empresa='$vgCodEmp' order by nombre");
						if (mysqli_num_rows($consulta) > 0){
							while ($row = mysqli_fetch_array($consulta)){
                    ?>
			                    <option value="<?php echo $row[id_sucursal];?>" <?php if($id_sucursal==$row[id_sucursal]){ echo 'selected';}?>><?php echo strtoupper($row[nombre]);?></option>
                    <?php }}?>
                  </select>
                </div>


                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha Nac.</label>
                  <input type="date" class="form-control" id="txtFecNac" name="txtFecNac" value="<?php echo $fecha_nac;?>" placeholder="">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Dirección</label>
                  <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" value="<?php echo $direccion;?>"  placeholder="" style="text-transform:uppercase" autocomplete="off" >
                </div>
                <div class="form-group">
                  <label>Distrito</label>
                  <select class="form-control select2" id="txtDistrito" name="txtDistrito" style="width: 100%" >
                    <option value=""></option>
					<?php
						@mysqli_query($con, "SET NAMES 'utf8'");
						$consulta = mysqli_query($con, "select nombre from man_distritos order by nombre");
						if (mysqli_num_rows($consulta) > 0){
							while ($row = mysqli_fetch_array($consulta)){
                    ?>
			                    <option value="<?php echo $row[nombre];?>" <?php if($row[nombre]==$distrito){ echo 'selected';}?>><?php echo strtoupper($row[nombre]);?></option>
                    <?php }}?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Telefono (*)</label>
                  <input type="number" class="form-control" id="txtTelefono" name="txtTelefono" value="<?php echo $telefono;?>"  placeholder="" autocomplete="off" >
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Correo</label>
                  <input type="email" class="form-control" id="txtCorreo" name="txtCorreo" value="<?php echo $correo;?>"  placeholder="" style="text-transform:lowercase" autocomplete="off" >
                </div>
                <div class="form-group">
                  <label>Cargo</label>
                  <select class="form-control select2" id="cboCargo" name="cboCargo" style="width: 100%;" >
                    <option value=""></option>
					<?php
						@mysqli_query($con, "SET NAMES 'utf8'");
						$consulta = mysqli_query($con, "select id_cargo, nombre from man_cargos order by nombre");
						if (mysqli_num_rows($consulta) > 0){
							while ($row = mysqli_fetch_array($consulta)){
                    ?>
			                    <option value="<?php echo $row[id_cargo];?>" <?php if($row[id_cargo]==$id_cargo){ echo 'selected';}?>><?php echo strtoupper($row[nombre]);?></option>
                    <?php }}?>
                  </select>
                </div>

                <div class="form-group">
                  <label>Estado</label>
                  <select class="form-control select2" id="cboEstado" name="cboEstado" style="width: 100%;" >
                    <option value="0" <?php if($estado==0){ echo 'selected';}?>>ACTIVO</option>
                    <option value="1" <?php if($estado==1){ echo 'selected';}?>>DE BAJA</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Usuario (*)</label>
                  <input name="txtUsuario" type="text" class="form-control" id="txtUsuario" style="text-transform:uppercase" onBlur="busca_trabajadores2(document.getElementById('txtUsuario').value, <?php echo $id;?>);" value="<?php echo $usuario;?>" maxlength="15"  placeholder="" autocomplete="off" >
                  <div id="resultado2" class="text-red"></div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Clave (*)</label>
                  <input name="txtClave" type="password" class="form-control" id="txtClave" style="text-transform:uppercase" value="<?php echo $clave;?>" maxlength="20"  placeholder="" autocomplete="off" >
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button id="btnGrabar" name="btnGrabar" type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info" disabled >Grabar</button>
                <button type="button" class="btn btn-default" onClick="javascript:location.href='man_trabajadores0.php'">Cancelar</button>
                <?php if($id > 0){?><button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-danger">Eliminar</button><?php }?>
                
                <input name="oculto" type="hidden" id="oculto" value="41846788">
                <input name="grabar" type="hidden" id="grabar" value="<?php echo $id;?>">
              </div>
            </form>
          </div>
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <!--/.col (right) -->
      </div>
      <!-- /.row -->



        <div class="modal modal-info fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje de Confirmación</h4>
              </div>
              <div class="modal-body">
                <p>Estas seguro que deseas grabar el registro?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:document.form1.submit();">Aceptar</button>
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
                <p>Estas seguro que deseas eliminar el registro?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" onClick="javascript:eliminar(<?php echo $id;?>);">Aceptar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


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
		})
		$(function () {
			//Initialize Select2 Elements
			$('.select2').select2()
		})

		$("#txtPaterno").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#txtMaterno").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});
		
		$("#txtNombres").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#txtNroDoc").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#txtTelefono").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#cboCargo").change(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#txtUsuario").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});

		$("#txtClave").keyup(function(){
			nombre1 = $("#txtPaterno").val();
			nombre2 = $("#txtMaterno").val();
			nombre3 = $("#txtNombres").val();
			nombre4 = $("#txtNroDoc").val();
			nombre6 = $("#txtTelefono").val();
			nombre7 = $("#cboCargo").val();
			nombre8 = $("#txtUsuario").val();
			nombre9 = $("#txtClave").val();
			if(nombre1.length>=3 && nombre2.length>=3 && nombre3.length>=5 && nombre4.length>=8 && nombre6.length>=6 && nombre7.length>=1 && nombre8.length>=5 && nombre9.length>=4){
				document.getElementById("btnGrabar").disabled = false;
			}else{
				document.getElementById("btnGrabar").disabled = true;
			}
		});


</script>
</body>
</html>
<?php
	if($id > 0){
?>
<script language="javascript">
	document.getElementById("btnGrabar").disabled = false;
</script>
<?php
	}
?>