<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

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
  <!-- Theme style -->
  <!-- This is what you need -->
  <script src="../bower_components/bootstrap-sweetalert/dist/sweetalert.js"></script>
  <link rel="stylesheet" href="../bower_components/bootstrap-sweetalert/dist/sweetalert.css">
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
<body class="hold-transition skin-purple sidebar-collapse">
<div class="wrapper">

  <?php include("inc_header.php");?>
  <?php include("inc_menu.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->

    <!-- Main content -->
    <section class="content">

      <div class="row">
                <input type="hidden" id="txtSesion" name="txtSesion" value="<?php echo $vlSesion;?>">
                <input type="hidden" id="txtId" name="txtId" value="<?php echo $id;?>">
                <input type="hidden" id="txtNroDoc" name="txtNroDoc" value="<?php echo $vgCab_Nro;?>">
                <input type="hidden" id="txtTope" name="txtTope" value="<?php echo $vlTope;?>">
      
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body" style="background-color: #222D32; color:#FFFFFF; height:53px;">
                            <div class="col-md-4">
        <button id="btnAtras" type="button" class="btn btn-default btn-sm" onClick="javascript:refrescar();">
                                    <i class="fa fa-refresh"></i>&nbsp&nbsp <span id="lblAtras" style="font-weight:bold; font-size:12px">REFRESCAR</span>
                                </button>
                            </div>
                            <div class="col-md-4" style="text-align:center">
                                <span id="lblTitulo" style="font-size:20px;">TIPO DE CAMBIO</span>
                                <input type="hidden" id="txtPagina" name="txtPagina" value="1">
                            </div>
                            <div id="contenedor" class="col-md-4" style="text-align:right">

                            </div>

                        </div>
                    </div>
                </div>
        </div>

        <div class="row" id="pagina0">    


                <div class="col-md-4" id="grid1">
<?php
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "select * from man_tipocambio order by fecha desc");
    if (mysqli_num_rows($consulta) <= 0){
?>
        <!--<h4 style="color:#FF0000;text-align:center"><i class="icon fa fa-ban"></i> No se encontraron registros</h4>-->
<?php
    }else{
?>
<button type="button" id="btnNuevo" style="height:40px;font-size:14px; width:120px;background-color:#605CA8" name="btnNuevo" class="btn btn-block btn-success btn-lg" onClick="javascript:nuevo();"><i class="fa fa-file-o"></i>&nbsp&nbsp NUEVO</button><br>
                    <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr style="background-color:#605CA8; color:#FFFFFF;">
                        <th style="width: 20%; text-align:center">Fecha</th>
                        <th style="width: 8%; text-align:center">Compra</th>
                        <th style="width: 8%; text-align:center">Venta</th>
                        <th style="width: 30%; text-align:center">Dia</th>
                        <th style="width: 4%; text-align:center"></th>
                        <th style="width: 4%; text-align:center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $itm = 0;
                        while ($row = mysqli_fetch_array($consulta)){
                            $itm = $itm + 1;
                            $fecha = strtotime($row["fecha"]);
                            switch (date('w', $fecha)){ 
                                case 0: $vlNomDia = "DOMINGO"; break; 
                                case 1: $vlNomDia = "LUNES"; break; 
                                case 2: $vlNomDia = "MARTES"; break; 
                                case 3: $vlNomDia = "MIERCOLES"; break; 
                                case 4: $vlNomDia = "JUEVES"; break; 
                                case 5: $vlNomDia = "VIERNES"; break; 
                                case 6: $vlNomDia = "SABADO"; break; 
                            } 
                    ?>
                    <tr id="fila<?php echo $itm;?>" style="background-color:#FFFFFF">
                        <td style="text-align:center">
                            <?php echo substr($row["fecha"],8,2).'/'.substr($row["fecha"],5,2).'/'.substr($row["fecha"],0,4);?>
                            <input type="hidden" id="txtFecha<?php echo $row["cod_secuencia"];?>" value="<?php echo $row["fecha"];?>">
                        </td>
                        <td style="text-align:center">
                            <?php echo $row["compra"];?>
                            <input type="hidden" id="txtCompra<?php echo $row["cod_secuencia"];?>" value="<?php echo $row["compra"];?>">
                        </td>
                        <td style="text-align:center">
                            <?php echo $row["venta"];?>
                            <input type="hidden" id="txtVenta<?php echo $row["cod_secuencia"];?>" value="<?php echo $row["venta"];?>">
                            <input type="hidden" id="txtCodSec<?php echo $row["cod_secuencia"];?>" value="<?php echo $row["cod_secuencia"];?>">
                        </td>
                        <td><?php echo $vlNomDia;?></td>
                        <td><button type="button" class="btn btn-block btn-warning btn-sm" id="btnEditar<?php echo $itm;?>" title="Modificar" style="width:33px" onClick="javascript:modificar(<?php echo $row["cod_secuencia"];?>,'<?php echo $row["codigo"];?>');" ><i class="fa fa-pencil"></i></button></td>
                        <td><button type="button" class="btn btn-block btn-danger btn-sm" id="btnEliminar<?php echo $itm;?>" title="Quitar" style="width:33px" onClick="javascript:quitar('<?php echo $row["cod_secuencia"];?>',<?php echo $itm;?>)"><i class="fa fa-close"></i></button></td>
                    </tr>
                    <?php }?>
                    </tbody>
                    </table>
                    <?php }?>
                    <input type="hidden" id="txtTotReg" value="<?php echo $itm;?>">

                </div>

                <div class="col-md-8" id="form">
                    <div class="boxS" style="background-color:#FFFFFF;">
                        <div class="box-header with-border" style="background-color:#222D32; color:#ffffff">
                            <h3 class="box-title">Detalle del tipo de cambio</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-md-4">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Fecha</label>
                                    <input type="date" class="form-control" id="txtFecha" value="" disabled >
                                    <input type="hidden" id="txtCodSec" value="">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Compra</label>
                                    <input type="text" class="form-control" id="txtCompra" value=""  disabled >
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Venta</label>
                                    <input type="text" class="form-control" id="txtVenta" value="" disabled >
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnGrabar" style="height:40px;font-size:14px;background-color:#605CA8" name="btnGrabar" class="btn btn-block btn-success btn-lg" onClick="javascript:grabar();" disabled ><i class="fa fa-save"></i>&nbsp&nbsp GRABAR</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnCancelar" style="height:40px;font-size:14px" name="btnCancelar" class="btn btn-block btn-default btn-lg" onClick="javascript:cancelar();" disabled ><i class="fa fa-mail-reply"></i>&nbsp&nbsp VOLVER</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
        </div>


    </section>
<!-- DataTables -->
<script>
    function grabar(){
        if(document.getElementById("txtFecha").value == ""){
            document.getElementById("txtFecha").focus();
            swal("Ingrese Fecha.");
            return false;
        }
        if(parseFloat(document.getElementById("txtCompra").value) <= 0 || document.getElementById("txtCompra").value == ""){
            document.getElementById("txtCompra").focus();
            swal("Ingrese T/C Compra.");
            return false;
        }
        if(parseFloat(document.getElementById("txtVenta").value) <= 0 || document.getElementById("txtVenta").value == ""){
            document.getElementById("txtVenta").focus();
            swal("Ingrese T/C Venta.");
            return false;
        }

        document.getElementById("btnGrabar").disabled = "true";


        sec = parseInt(document.getElementById("txtCodSec").value);
        fec = document.getElementById("txtFecha").value;
        com = parseFloat(document.getElementById("txtCompra").value);
        ven = parseFloat(document.getElementById("txtVenta").value);

        $.post("sql_grabartipocambio.php", { sec: sec, fec: fec, com: com, ven: ven }, function(data){
            if(data>=1){
                //swal("Se grabo el Tipo de cambio !");
                location.href = "man_tcambio0.php";
            }else{
                document.getElementById("btnGrabar").disabled = "";
                document.getElementById("txtCompra").focus();
                if(data==-100){
                    swal("Fecha ya existe", "Hemos encontrado un tipo de cambio con la misma fecha.", "error");
                }else{
                    swal("Problemas para grabar", "Intentalo nuevamente.", "error");
                }
            }
        });
    }

    function refrescar(){
        location.href = "man_tcambio0.php";
    }

    function modificar(a){
        document.getElementById("txtFecha").value   = document.getElementById("txtFecha"+a).value;
        document.getElementById("txtCompra").value  = document.getElementById("txtCompra"+a).value;
        document.getElementById("txtVenta").value   = document.getElementById("txtVenta"+a).value;
        document.getElementById("txtCodSec").value  = document.getElementById("txtCodSec"+a).value;

        document.getElementById("btnNuevo").disabled = "true";
        document.getElementById("txtCompra").disabled = "";
        document.getElementById("txtVenta").disabled  = "";
        document.getElementById("btnGrabar").disabled = "";
        document.getElementById("btnCancelar").disabled = "";
        document.getElementById("txtCompra").focus();

        tot = parseInt(document.getElementById("txtTotReg").value);
        for(z=1;z<=tot;z++){
            document.getElementById("btnEditar"+z).disabled = "true";
            document.getElementById("btnEliminar"+z).disabled = "true";
        }
    }
    function quitar(a,b){
        document.getElementById("btnEliminar"+b).disabled = "true";
        swal({
            title: "Seguro de eliminar ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "SI, eliminar!",
            closeOnConfirm: false
        },
        function(isConfirm){
            if (isConfirm) {
                $.post("sql_quitartipocambio.php", { cod: a }, function(data){
                    if(data>=1){
                        swal("Eliminado", "El tipo de cambio se elimino correctamente.", "success");
                        location.href = "man_tcambio0.php";
                    }else{
                        swal("Problemas para eliminar el tipo de cambio", "Intentalo nuevamente.", "error");
                    }
                });
            }else{
                document.getElementById("btnEliminar"+b).disabled = "";
            }
        });
    }
    function nuevo(){
        document.getElementById("txtFecha").value   = "";
        document.getElementById("txtCompra").value  = "";
        document.getElementById("txtVenta").value   = "";
        document.getElementById("txtCodSec").value  = "";

        document.getElementById("btnNuevo").disabled = "true";
        document.getElementById("txtFecha").disabled = "";
        document.getElementById("txtCompra").disabled = "";
        document.getElementById("txtVenta").disabled  = "";
        document.getElementById("btnGrabar").disabled = "";
        document.getElementById("btnCancelar").disabled = "";
        document.getElementById("txtFecha").focus();

        tot = parseInt(document.getElementById("txtTotReg").value);
        for(z=1;z<=tot;z++){
            document.getElementById("btnEditar"+z).disabled = "true";
            document.getElementById("btnEliminar"+z).disabled = "true";
        }
    }

    function cancelar(){
        a = document.getElementById("txtCodSec").value;
        document.getElementById("txtFecha").value   = document.getElementById("txtFecha"+a).value;
        document.getElementById("txtCompra").value  = document.getElementById("txtCompra"+a).value;
        document.getElementById("txtVenta").value   = document.getElementById("txtVenta"+a).value;

        document.getElementById("txtFecha").disabled = "true";
        document.getElementById("txtCompra").disabled = "true";
        document.getElementById("txtVenta").disabled  = "true";
        document.getElementById("btnGrabar").disabled = "true";
        document.getElementById("btnCancelar").disabled = "true";
        document.getElementById("btnNuevo").disabled = "";

        tot = parseInt(document.getElementById("txtTotReg").value);
        for(z=1;z<=tot;z++){
            document.getElementById("btnEditar"+z).disabled = "";
            document.getElementById("btnEliminar"+z).disabled = "";
        }
    }
</script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
        $('#example2').DataTable( {
            "scrollY":          "420px",
            "scrollCollapse":   true,
            "searching":        false,
            "ordering":         false,
            "paging":           false
        });
    });
</script>
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