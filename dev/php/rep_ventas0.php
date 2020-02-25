<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];

    $tip = $_GET["tip"];
    $doc = $_GET["doc"];
    $ini = $_GET["ini"];
    $fin = $_GET["fin"];
    $nom = $_GET["nom"];
    $mod = $_GET["modo"];

    setlocale(LC_ALL, 'es_ES');
    $vlSesion = strftime("%d%m%Y%H%M%S");
    $vlFecha  = strftime("%d/%m/%Y");
    $vlFecha_formateada = strftime("%Y-%m-%d");
    //$vlFecha_inicio = strftime("%Y-01-01");
    $vlFecha_inicio = strftime("%Y-%m-%d");

    if($mod=="R"){
        $vlFecha_inicio = $ini;
        $vlFecha_formateada = $fin;
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
  <!-- Select2 -->
  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
                                <span id="lblTitulo" style="font-size:20px;">CONSULTA DE COMPROBANTES</span>
                            </div>

                        </div>
                        <div class="box-body">
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">TIPO DE DOCUMENTO</label>
                                    <select class="form-control" id="cboTipo">
                                        <option VALUE="">(TODO)</option>
                                        <?php
                                            $consulta = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1");
                                                while ($row = mysqli_fetch_array($consulta)){
                                        ?>
                                            <option VALUE="<?php echo $row["codigo"];?>" <?php if($tip==$row["codigo"]){ echo "selected";}?>><?php echo $row["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">NUMERO DOCUMENTO</label>
                                    <input type="text" class="form-control" id="txtNroDoc" value="<?php echo $doc?>" style="text-transform:uppercase" >
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group has-success">
                                <label class="control-label" for="inputSuccess">FECHA INICIO</label>
                                    <input type="date" class="form-control" id="txtFecIni" value="<?php echo $vlFecha_inicio;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                <label class="control-label" for="inputSuccess">FECHA FINAL</label>
                                    <input type="date" class="form-control" id="txtFecFin" value="<?php echo $vlFecha_formateada;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <br><button type="button" id="btnTipDoc" name="btnTipDoc" style="height:40px; font-size:18px; background-color:#605CA8" class="btn btn-block btn-success" onClick="javascript:buscar();" style="height:60px"><i class="fa fa-refresh"></i>&nbsp&nbsp BUSCAR</button>
                                </div>
                            </div>
                            
                        </div>
                                            <!-- Loading (remove the following to stop the loading)-->
                                            <div class="overlay" id="overlay10" style="display:none">
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                            <!-- end loading -->


                    </div>



                </div>

                <div class="col-md-12" id="grid">
                </div>
        </div>

        <div class="row" id="ticket" style="display:none">



        
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" style="background-color: #222D32; color:#FFFFFF; height:53px;">
                        <div class="col-md-4">
                            <button id="btnAtras" type="button" class="btn btn-default btn-sm" onClick="javascript:ir_atras();" >
                                <i class="fa fa-backward"></i>&nbsp&nbsp <span id="lblAtras" style="font-weight:bold; font-size:12px">ATRAS</span>
                            </button>
                        </div>
                        <div class="col-md-4" style="text-align:center">
                            <span id="lblTitulo" style="font-size:20px;">EMISIÓN DE COMPROBANTE</span>
                            <input type="hidden" id="txtPagina" name="txtPagina" value="1">
                        </div>
                        <div id="contenedor" class="col-md-4" style="text-align:right">

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-12" style="text-align:center">
                <input type="hidden" id="txtIdc" name="txtIdc" value="">
                <button id="btnImprimir" type="button" class="btn btn-success btn-lg" style="background-color:#605CA8;" onClick="javascript:imprimir();">
                    <i class="fa fa-print"></i>&nbsp&nbsp <span id="lblImprimir" style="font-size:18px">IMPRIMIR COMPROBANTE</span>
                </button>

                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 

                <button id="btnEnviarCorreo" type="button" class="btn btn-success btn-lg" style="background-color:#605CA8;" onClick="javascript:correo();">
                    <i class="fa fa-envelope-o"></i>&nbsp&nbsp <span id="lblCorreo" style="font-size:18px">ENVIAR CORREO</span>
                </button><br><br>
            </div>

            <div class="col-md-12">
                <div id="imprimir" style="background-color: #ECF0F5;">
                    <?php //include("imp_ticket.php");?>
                </div>
                <div id="img-out"></div>
            </div>
        </div> 

    </section>

<!-- page script 350 -->
<script>
    function buscar(){
        if(document.getElementById("txtFecIni").value=="" && document.getElementById("txtFecFin").value!=""){
            swal("Rango de fechas incompleto", "Ingrese fecha de inicio.", "error");
        }
        if(document.getElementById("txtFecIni").value!="" && document.getElementById("txtFecFin").value==""){
            swal("Rango de fechas incompleto", "Ingrese fecha final.", "error");
        }
        if(document.getElementById("txtFecIni").value > document.getElementById("txtFecFin").value){
            swal("Rango de fechas incompleto", "Fecha de inicio no puede ser mayor que la fecha final.", "error");
        }
        if(document.getElementById("txtFecFin").value < document.getElementById("txtFecIni").value){
            swal("Rango de fechas incompleto", "Fecha final no puede ser menor que la fecha de inicio.", "error");
        }
        $('#overlay10').show();

        tip = document.getElementById("cboTipo").value;
        doc = document.getElementById("txtNroDoc").value;
        ini = document.getElementById("txtFecIni").value;
        fin = document.getElementById("txtFecFin").value;
        cargarMenu("#grid","rep_ventas1.php?tip="+tip+"&doc="+doc+"&ini="+ini+"&fin="+fin);
    }
    function anular(a,b,c){
        swal({
            title: "Anular ?",
            text: "Seguro de anular el comprobante: "+b+" "+c,
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Anular",
            closeOnConfirm: false
        },
        function(){
            $.post("sql_anularcomprobante.php", { cid: a }, function(data){
                if(data>=1){
                    document.getElementById("btnAnula"+a).disabled = "true";
                    document.getElementById("btnModifica"+a).disabled = "true";
                    document.getElementById("btnImprimir"+a).disabled = "true";
                    document.getElementById("lblEstado"+a).innerHTML = "ANULADO";
                    document.getElementById("lblTotal"+a).innerHTML = "0.00";
                    document.getElementById("fila"+a).style.backgroundColor  = "#F2D7D5";
                    swal("Comprobante Anulado", "", "success");
                }else{
                    swal("Problemas para anular el comprobante", "Intentalo nuevamente.", "error");
                }
            });
        });
    }
    function modificar(a){
        tip = document.getElementById("cboTipo").value;
        doc = document.getElementById("txtNroDoc").value;
        ini = document.getElementById("txtFecIni").value;
        fin = document.getElementById("txtFecFin").value;
        nom = document.getElementById("txtNomCli").value;
        cargarMenu("#contenedor","pro_ventas0.php?id="+a+"&tip="+tip+"&doc="+doc+"&ini="+ini+"&fin="+fin+"&nom="+nom);
    }
    function imprimir(){
        var divToPrint = document.getElementById('imprimir');
        var popupWin = window.open('', '_blank', 'width=600,height=400');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
        popupWin.focus();
        return true;
    }
    function imprime(a){
        document.getElementById("txtIdc").value = a;
        document.getElementById("pagina0").style.display = "none";
        document.getElementById("ticket").style.display = "";
        cargarMenu('#imprimir','imp_ticket.php?idc='+a);
    }
    function ir_atras(){
        document.getElementById("pagina0").style.display = "block";
        document.getElementById("ticket").style.display = "none";
        cargarMenu('#imprimir','imp_ticket.php?idc=0');
    }

    function correo(a){
        swal({
            title: "Ingrese correo electronico",
            text: "",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            inputPlaceholder: ""
            }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("Ingrese correo electronico");
                return false
            }else{
                // Patron para el correo
                var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                if(inputValue.search(patron)==0){
                }else{
                    swal.showInputError("Correo incorrecto");
                    return false
                }
            }

            //-Enviando correo------------
            idc = document.getElementById("txtIdc").value;
            $.post("enviar_ticket.php", { correo: inputValue, id: idc }, function(data){
                if(data=="Invalid address: 1"){
                    swal("Correo enviado", "", "success");
                }else{
                    swal("Problemas para enviar correo", "", "error");
                }
            });



            //-----------------------------------------------
        });
    }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#overlay10').hide();
});
<?php if($mod=="R"){?>
    buscar();
<?php }?>
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