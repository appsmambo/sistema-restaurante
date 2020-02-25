<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];

    setlocale(LC_ALL, 'es_ES');
    $vlFecha  = strftime("%d/%m/%Y");
    $vlFecha_formateada = strftime("%Y-%m-%d");

    //-Consultando tipo de cambio venta--------------
    $vltcambio = 0;
    $consulta = mysqli_query($con, "select venta as tcambio from man_tipocambio where fecha='$vlFecha_formateada'");
    $rowTope = mysqli_fetch_array($consulta);
    $vltcambio = $rowTope["tcambio"];
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

  <style type="text/css">
  .table{
    font-size: 11px;
  }
  .table-hover {
    cursor: pointer;
  }
  .modal-dialog { /* Vertical scrollbar if necessary */
    width: 50%; 
  }
  .img_list {
    width: 30%;
    padding: 5px;
  }
  .stylemap {
    height: 300px;
    width: 100%;
  }
  .input-small{
    width: 20%;
    float: left;
    margin-right: 5px;
  }

  @media (max-width: 768px) {
    .modal-dialog {
      width: 95%;
    }
    .img_list {
      width: 100%;
    }
    .input-small{
      width:100%;
      margin-bottom: 5px;
    }
  }

  </style>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script language="JavaScript">
    function cargarMenu(div,url2){
        //$(div).load(url);

        $.ajax({
                    url: url2,
                    type: "POST",
                    //data: "codsec="+codsec+"&a="+a+"&b="+b+"&c="+c+"&d="+d+"&e="+e+"&f="+f+"&h="+h,
                    success: function(resp){
            $(div).html(resp);
                    }       
                });

    }
</script>
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
        <div class="row" id="pagina0">      
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body" style="background-color: #222D32; color:#FFFFFF; height:53px;">
                            <div class="col-md-12" style="text-align:center">
                                <span id="lblTitulo" style="font-size:20px;">APERTURA Y CIERRE DE CAJA</span>
                            </div>

                        </div>

                        <?php
                                if($vltcambio<=0){
                        ?>
                                    <script type="text/javascript">
                                        swal("Tipo de cambio no configurado", "Deberas ingresar el tipo de cambia del dia.", "error");
                                    </script>
                        <?php
                                }else{
                        ?>

                        <div class="box-body">
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                <label class="control-label" for="inputSuccess">FECHA</label>
                                    <input type="date" class="form-control" id="txtFecha" value="<?php echo $vlFecha_formateada;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnGenerar" name="btnGenerar" style="height:40px; font-size:18px; background-color:#605CA8" class="btn btn-block btn-success" onClick="javascript:generar();"><i class="fa fa-refresh"></i>&nbsp&nbsp GENERAR</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnCerrar" disabled name="btnCerrar" style="height:40px; font-size:18px;  background-color:#DD4B39; color:#FFFFFF; display:none;" class="btn btn-block btn-danger" onClick="javascript:cerrar();"><i class="fa fa-download"></i>&nbsp&nbsp  CERRAR CAJA</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnImprimir" disabled name="btnImprimir" style="height:40px; font-size:18px; display:none; background-color:#605CA8; color:#FFFFFF;" class="btn btn-block btn-default" onClick="javascript:imprime();"><i class="fa fa-print"></i>&nbsp&nbsp IMPRIMIR</button>
                                </div>
                            </div>
                            
                        </div>
                                            <!-- Loading (remove the following to stop the loading)-->
                                            <div class="overlay" id="overlay10" style="display:none;">
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                            <!-- end loading -->

                        <?php }?>
                    </div>



                </div>

                <div class="col-md-9" id="grid1">
                </div>
                <div class="col-md-3" id="ticket">
                </div>
        </div>


    </section>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<!-- page script 350 -->
<script>
    document.getElementById("txtFecha").focus();
    function imprime(){
        var divToPrint = document.getElementById('ticket');
        var popupWin = window.open('', '_blank', 'width=600,height=400');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
        popupWin.focus();
        return true;
    }
    function cerrar(){
        swal({
            title: "Cerrar caja ?",
            text: "Al cerrarse la caja se bloqueará la operatividad de los demás usuarios.",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, deseo cerrar",
            closeOnConfirm: false
        },
        function(){
            fecha = document.getElementById("txtFecha").value;
            $('#overlay10').show();

            document.getElementById("btnCerrar").disabled = "true";
            document.getElementById("btnImprimir").disabled = "true";

            $.post("sql_cerraraperturacaja.php", { fecha: fecha }, function(data){
                $('#overlay10').hide();
                if(data>=1){
                    document.getElementById("btnCerrar").style.display = "none";
                    document.getElementById("btnImprimir").disabled = "";
                    generar();
                    swal("Caja cerrada", "", "success");
                }else{
                    document.getElementById("btnCerrar").disabled = "";
                    document.getElementById("btnImprimir").disabled = "";
                    swal("Problemas para cerrar la caja", "Intentalo nuevamente.", "error");
                }
            });
        });
    }
    function generar(){
        if(document.getElementById("txtFecha").value==""){
            swal("Seleccione una fecha", "", "error");
            return false;
        }
        $('#overlay10').show();
        document.getElementById("grid1").style.display = "none";
        document.getElementById("ticket").style.display = "none";

        document.getElementById("btnCerrar").disabled = "true";
        document.getElementById("btnImprimir").disabled = "true";

        document.getElementById("btnCerrar").style.display = "none";
        document.getElementById("btnImprimir").style.display = "none";

        fecha = document.getElementById("txtFecha").value;

        //-Aperturando caja------------
        fechita = document.getElementById("txtFecha").value;
        $.post("sql_consultaaperturacaja.php", { fecha: fechita }, function(data){
            rspta = data.split("|");
            ida = rspta["0"];
            imp = rspta["1"];
            est = rspta["2"];
            if(imp>0){
                cargarMenu("#grid1","rep_caja1.php?fecha="+fechita+"&idaper="+ida+"&impaper="+imp+"&estaper="+est);
                cargarMenu('#ticket','imp_cuadrecaja.php?fecha='+fechita+"&idaper="+ida+"&impaper="+imp+"&estaper="+est);
            }else{
          swal({
              title: "Desea aperturar su caja ?",
              text: "",
              type: "input",
              showCancelButton: true,
              closeOnConfirm: false,
              showLoaderOnConfirm: true,
              inputPlaceholder: ""
              }, function (inputValue) {
              if (inputValue === false) return false;
              if (inputValue === "") {
                  swal.showInputError("Ingrese el importe con que desea aperturar su caja");
                  return false
              }else{
              }

              //-Aperturando caja------------
              fechita = document.getElementById("txtFecha").value;
              $.post("sql_aperturarcaja.php", { importe: inputValue, fecha: fechita }, function(data){
                  if(data>0){
                      swal("Caja aperturada", "", "success");
                      generar();
                  }else{
                      swal("Problemas para aperturar la caja", "", "error");
                  }
              });
              //-----------------------------------------------
          });
            }
            $('#overlay10').hide();
        });
        //-----------------------------------------------
    }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#overlay10').hide();
});
</script>
</div>

  <?php include("inc_footer.php");?>
  <?php //include("inc_tool.php");?>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
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