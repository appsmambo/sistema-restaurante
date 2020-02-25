<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    $id  = $_GET["id"];

	$vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    $vgFlujo  = $_SESSION["flujo"];
    $idmesa	  = $_GET["idmesa"];
    $vlMesa   = $_GET["idmesa"];

    $total = 0;
    if($idmesa>0){
        $lista_pedidos = array();
        @mysqli_query($con, "SET NAMES 'utf8'");


        if($vgFlujo==1){
            $consulta = mysqli_query($con, "select cant, descripcion, comentario, precio, cant*precio as total, estado, destino, user_registro, fec_registro, user_modifica, fec_modifica, id from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado<>9 order by id desc");
        }
        if($vgFlujo==2){
            $consulta = mysqli_query($con, "select cant, descripcion, comentario, precio, cant*precio as total, estado, destino, user_registro, fec_registro, user_modifica, fec_modifica, id from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado=3 order by id desc");
        }
        while ($rowPed = mysqli_fetch_array($consulta)){
            $total = $total + $rowPed["total"];
            $lista_pedidos[] = $rowPed;
        }
        $total = number_format($total, 2, '.', ',');
    }

    $tip = $_GET["tip"];
    $doc = $_GET["doc"];
    $ini = $_GET["ini"];
    $fin = $_GET["fin"];
    $cli = $_GET["nom"];


    //$id = 7;

    setlocale(LC_ALL, 'es_ES');
    $vlSesion = strftime("%d%m%Y%H%M%S");
    $vlFecha  = strftime("%d/%m/%Y");
    $vlFecha_formateada = strftime("%Y-%m-%d");

    //-Consultando cual es el tipo de documento popr defecto------------
    $consulta = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1");
    $rowTdoc = mysqli_fetch_array($consulta);
    $vgCab_Nom = "VENTA AL PUBLICO";
    $vgCab_Eti1 = "DNI";
    $vgCab_Eti2 = "NOMBRE DEL CLIENTE";
    $vgCab_Tdo = $rowTdoc["codigo"];
    $vgCab_Ndo = $rowTdoc["nombre"];
    $vgCab_Imp = "0.00";
    $vgCab_Cad = "";

    if (strpos($vgCab_Ndo, "FACTURA") !== false) {
        $vgCab_Nom  = "";
        $vgCab_Eti1  = "RUC";
        $vgCab_Eti2 = "RAZON SOCIAL";
    }
    //------------------------------------------------------------------



    if($id > 0){
        //-Consultando Cabecera----
        $consulta = mysqli_query($con, "select * from cabecera where cid='$id'");
        $rowCabecera = mysqli_fetch_array($consulta);
        $vgCab_Nro = $rowCabecera["cnrodoc"];
        $vgCab_Cod = $rowCabecera["cdniruc"];
        $vgCab_Nom = $rowCabecera["cnombre"];
        $vgCab_Obs = $rowCabecera["cobserva"];
        $vgCab_Tdo = $rowCabecera["ctipdoc"];
        $vgCab_Imp = $rowCabecera["cimportev"];
        if($vgCab_Tdo=="BE"){
            $vgCab_Ndo = "BOLETA ELECTRONICA";
        }
        if($vgCab_Tdo=="FE"){
            $vgCab_Ndo = "FACTURA ELECTRONICA";
        }
        if($vgCab_Tdo=="BM"){
            $vgCab_Ndo = "BOLETA MANUAL";
        }
        if($vgCab_Tdo=="FM"){
            $vgCab_Ndo = "FACTURA MANUAL";
        }
        $vlFecha   = date("d/m/Y", strtotime($rowCabecera["cfechad"]));
        $vlFecha_formateada = date("Y-m-d", strtotime($rowCabecera["cfechad"]));
        //-------------------------

        //-Consultando Detalle-----
        $cadenaCodigo = "";
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta = mysqli_query($con, "select a.dcodlib, b.nombre as dnomlib, a.dcantidad, a.dpdscto, a.dmoneda, a.dpreuni, a.dpventa, b.pv_libreria from detalle as a left join man_titulos as b on a.dcodlib = b.codigo where a.didc='$id' order by a.did");
        while ($row = mysqli_fetch_array($consulta)){
            $pre = $row["dpreuni"];
            if($row["dpdscto"]>0){
              $pre = $row["dpreuni"] - ($row["dpreuni"]*$row["dpdscto"])/100;
            }
            if($cadenaCodigo==""){
                $cadenaCodigo = $row["dcodlib"];
            }else{
                $cadenaCodigo = $cadenaCodigo.'|'.$row["dcodlib"];
            }
            $cod = $row["dcodlib"];
            $nom = $row["dnomlib"];
            $can = $row["dcantidad"];
            $plib = $row["pv_libreria"];
            //$pre = $row["dpreuni"];
            $vlSqlCad = "insert into tmp(sesion, codigo, descripcion, cantidad, precio, precio_libreria, user_registro, fec_registro) values ('$vlSesion', '$cod', '$nom', '$can', '$pre', '$plib', '$vlUser', now())";
            $rpta = mysqli_query($con, $vlSqlCad);
        }
        $vgCab_Cad = $cadenaCodigo;
        //-------------------------

        //-Consultando Pagos-----
        @mysqli_query($con, "SET NAMES 'utf8'");
        $consulta = mysqli_query($con, "select * from pagos where fidc='$id' order by fid");
        while ($row = mysqli_fetch_array($consulta)){
            $codpago = $row["cod_pago"];
            $nompago = $row["nom_pago"];
            $tippago = $row["ffpago"];
            $tcambio = $row["ftcambio"];
            $mensaje = strtoupper($row["fobserva"]);
            $importe_dolares = $row["fdolar"];
            $importe_soles   = $row["fsoles"];
            $vlSqlCad = "insert into tmp_pagos(sesion, tipo_pago, cod_pago, nom_pago, tcambio, importe_soles, importe_dolares, observacion) values ('$vlSesion', '$tippago', '$codpago', '$nompago', '$tcambio', '$importe_soles', '$importe_dolares', '$mensaje')";
            $rpta = mysqli_query($con, $vlSqlCad);
        }
        $vgCab_Cad = $cadenaCodigo;
        //-------------------------
    }

    //-Consultando tipo de cambio venta--------------
    $vltcambio = 0;
    $consulta = mysqli_query($con, "select venta as tcambio from man_tipocambio where fecha='$vlFecha_formateada'");
    $rowTope = mysqli_fetch_array($consulta);
    $vltcambio = $rowTope["tcambio"];

    //-Consultando monto tope para boletas con dni---
    $consulta = mysqli_query($con, "select pimporte1 as tope from man_parametros where pcodtabla='TOPEB' and pcodigo<>'00' order by pcodigo");
    $rowTope = mysqli_fetch_array($consulta);
    $vlTope = $rowTope["tope"];



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
  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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



    <section class="content">
      <div class="row">
                <input type="hidden" id="txtSesion" name="txtSesion" value="<?php echo $vlSesion;?>">
                <input type="hidden" id="txtId" name="txtId" value="<?php echo $id;?>">
                <input type="hidden" id="txtTope" name="txtTope" value="<?php echo $vlTope;?>">
      
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body" style="background-color: #222D32; color:#FFFFFF; height:53px;">
                            <div class="col-md-4">
        <button id="btnAtras" type="button" class="btn btn-default btn-sm" onClick="javascript:paginador('-');">
                                    <i class="fa fa-backward"></i>&nbsp&nbsp <span id="lblAtras" style="font-weight:bold; font-size:12px">ATRAS</span>
                                </button>
                            </div>
                            <div class="col-md-4" style="text-align:center">
                                <span id="lblTitulo" style="font-size:20px;">MESA <?php echo str_pad($idmesa, 2, "0", STR_PAD_LEFT);?></span>
                                <input type="hidden" id="txtPagina" name="txtPagina" value="1">
                                <input type="hidden" id="txtMesa" name="txtMesa" value="<?php echo $idmesa;?>">

                            </div>
                            <div id="contenedor" class="col-md-4" style="text-align:right">
                                <?php
                                    if($vltcambio>0){
                                ?>
                                    <button id="btnSiguiente" type="button" class="btn btn-default btn-sm" onClick="javascript:paginador('+');">
                                    <span id="lblSiguiente" style="font-weight:bold; font-size:12px">FORMAS DE PAGO</span> &nbsp&nbsp<i class="fa fa-forward"></i>
                                    </button>
                                <?php }?>
                            </div>

                        </div>
                    </div>
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
        <div class="row" id="pagina1">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">



                                        <div class="box" style="height:550px">
                                            <div class="box-header with-border" style="background-color:#222D32; color:#ffffff">
                                                <div class="col-md-12">
                                                    <h3 class="box-title">Fecha: <?php echo $vlFecha;?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <span style="color:#FCE301">T/C: <?php echo $vltcambio;?></span></h3>
                                                </div>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="grdCarrito" style="text-align:center">
                                                    <?php include("pro_ventas1.php");?>
                                                </div>
                                            </div>

                                            <div class="box-header with-border" style="background-color: #222D32; color:#FFFFFF;">
                                                <div class="col-md-6">
                                                    <h3 class="box-title">DATOS DEL CLIENTE</h3>
                                                </div>
                                                <div class="col-md-6" style="text-align:right">
                                                    <h3 class="box-title">Total a Pagar S/ :&nbsp&nbsp&nbsp <span id="lblTotalPagar1"><?php echo $total;?></span> 
                                                        <input type="hidden" id="txtTotalPagar1" name="txtTotalPagar1" value="<?php echo $$total;?>">
                                                        <input type="hidden" id="txtFecha" name="txtFecha" value="<?php echo $vlFecha_formateada;?>">
                                                        <input type="hidden" id="txtTcambio" name="txtTcambio" value="<?php echo $vltcambio;?>">
                                                    </h3>

                                                </div>
                                            </div>
                                            <div class="box-body" style="background-color: #FFFFFF;">
                                                <div class="col-md-3">
                                                    <div class="form-group has-success">
                                                        <label class="control-label" for="inputSuccess"><i class="fa fa-list-alt"></i> <span id="lbltipodoc1"><?php echo $vgCab_Eti1;?></span></label>
                                                        <input type="text" class="form-control" id="txtCodCli" value="" onkeypress="return teclas(event);">
                                                        <input type="hidden" class="form-control" id="txtTipCli" value="0">
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <div class="form-group has-success">
                                                        <label class="control-label" for="inputSuccess"><i class="fa fa-user"></i> <span id="lbltipodoc2"><?php echo $vgCab_Eti2;?></span></label>
                                                        <input type="text" class="form-control" id="txtNomCli" value="<?php echo $vgCab_Nom;?>" style="text-transform:uppercase" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group has-success">
                                                        <label class="control-label" for="inputSuccess"><i class="fa fa-comment-o"></i> OBSERVACION</label>
                                                        <input type="text" class="form-control" id="txtObsCli" style="text-transform:uppercase" value="<?php echo $vgCab_Obs;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-success">
                                                        <br><button type="button" id="btnTipDoc" style="background-color:#605CA8" name="btnTipDoc" class="btn btn-block btn-success btn-lg" onClick="javascript:cambiar_tipodoc();"><span id="lblTipDoc"><?php echo $vgCab_Ndo;?></span></button><input type="hidden" id="txtTipDoc" value="<?php echo $vgCab_Tdo;?>">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <!-- /.box -->





            </div>
            <div class="col-md-3">
            </div>



        </div>

        



        <div class="row" id="pagina2" style="display:none">
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border" style="background-color:#222D32; color:#ffffff">
                        <div class="col-md-12" style="text-align:right">
                            <h3 class="box-title">Total a Pagar S/ :&nbsp&nbsp&nbsp <?php echo $total;?>
                            <input type="hidden" id="txtTotalPagar2" name="txtTotalPagar2" value="<?php echo $total;?>">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="grdModalidades" style="text-align:center;height:340px;">
                        </div>
                    </div>
                    <!-- Loading (remove the following to stop the loading)-->
                    <div class="overlay" id="overlay3">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->


                    <div class="box-header with-border" style="background-color:#222D32; color:#ffffff;" id="pie1">
                        <div class="col-md-9" style="text-align:right">
                            <h3 class="box-title">Total Recibido :</h3> 
                        </div>
                        <div class="col-md-3" style="text-align:right">
                            <h3 class="box-title"><span id="lblTotalRecibido">0.00</span></h3>
                            <input type="hidden" id="txtTotalRecibido" name="txtTotalRecibido" value="0">
                        </div>
                    </div>
                    <div class="box-header with-border" style="background-color:#222D32; color:#ffffff; display:none;" id="pie2">
                        <div class="col-md-9" style="text-align:right">
                            <h3 class="box-title">Vuelto :</h3>
                            <input type="hidden" id="txtVuelto" name="txtVuelto" value="0">
                        </div>
                        <div class="col-md-3" style="text-align:right">
                            <h3 class="box-title"><span id="lblVuelto">0.00</span></h3>
                        </div>
                    </div>
                    <div class="box-header with-border" style="background-color:#FF0000; color:#FFFFFF;" id="pie3">
                        <div class="col-md-9" style="text-align:right">
                            <h3 class="box-title">Falta :</h3>
                            <input type="hidden" id="txtFalta" name="txtFalta" value="0">
                        </div>
                        <div class="col-md-3" style="text-align:right">
                            <h3 class="box-title"><span id="lblFalta">0.00</span></h3>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>


            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border" style="background-color:#222D32; color:#ffffff">
                        <div class="col-md-12" style="text-align:center">
                            <h3 class="box-title">Modalidades de Pagos</h3>
                        </div>
                    </div>


                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-5">
                            <a href="javascript:seleccionando_formapago('ES',1,'EFECTIVO EN SOLES','E');"><img src="../dist/img/ico_efectivo_soles.jpg" id="formapago1"></a>
                            <a href="javascript:seleccionando_formapago('ED',2,'EFECTIVO EN DOLARES','E');"><img src="../dist/img/ico_efectivo_dolares.jpg" id="formapago2"></a>


            <?php
                $r = 2;
                @mysqli_query($con, "SET NAMES 'utf8'");
                $consulta = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre, picono as icono from man_parametros where pcodtabla='CAJ01' and pcodigo<>'00' order by panexo1");
                while ($rowTar = mysqli_fetch_array($consulta)){
                    $r = $r + 1;
            ?>

                <a href="javascript:seleccionando_formapago('<?php echo $rowTar[codigo];?>',<?php echo $r;?>,'<?php echo 'TARJETA '.$rowTar[nombre];?>','T');"><img src="../dist/img/<?php echo $rowTar[icono];?>" id="formapago<?php echo $r;?>"></a>
            <?php }?>
                            <input type="hidden" id="tarjetas_all" name="tarjetas_all" value="">
                            <input type="hidden" id="nro_formaspago"  name="cant_formaspago" value="<?php echo $r;?>">
                            <input type="hidden" id="tipo_habilitado" name="tipo_habilitado" value="D">
                            <input type="hidden" id="tipo_formaspago" name="tipo_formaspago" value="">
                            <input type="hidden" id="cod_formaspago"  name="cod_formaspago"  value="">
                            <input type="hidden" id="nom_formaspago"  name="nom_formaspago"  value="">
                            <!--<input type="hidden" id="tcambio"  name="tcambio" value="3.30">-->
                        </div>



                        <div class="col-md-7" id="IngresarPago">
                                                    
                            <div class="col-lg-6">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess"><i class="fa fa-money"></i>&nbsp Importe</label><br>
                                    <input type="text" class="form-control" id="txtImporteTeclado" name="txtImporteTeclado" onkeypress="return enter(event);">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <br><button type="button" id="btnAplicar" style="background-color:#605CA8" name="btnAplicar" class="btn btn-block btn-success btn-lg" onClick="javascript:pagar();">AGREGAR</button>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess"><i class="fa fa-comment-o"></i>&nbsp Observacion</label>
                                    <input type="text" class="form-control" id="txtObservacionTeclado" name="txtObservacionTeclado" style="text-transform:uppercase;">
                                </div>
                            </div>
                                                

                            <div class="col-lg-12">
                                <button onClick="javascript: teclado('n',7);" type="button" id="btnTecla7"  name="btnTecla7"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">7</button>
                                <button onClick="javascript: teclado('n',8);" type="button" id="btnTecla8"  name="btnTecla8"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">8</button>
                                <button onClick="javascript: teclado('n',9);" type="button" id="btnTecla9"  name="btnTecla9"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">9</button>
                                <button onClick="javascript: teclado('b',10);" type="button" id="btnTecla10" name="btnTecla10" class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">+10</button><br>

                                <button onClick="javascript: teclado('n',4);" type="button" id="btnTecla4"  name="btnTecla4"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">4</button>
                                <button onClick="javascript: teclado('n',5);" type="button" id="btnTecla5"  name="btnTecla5"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">5</button>
                                <button onClick="javascript: teclado('n',6);" type="button" id="btnTecla6"  name="btnTecla6"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">6</button>
                                <button onClick="javascript: teclado('b',20);" type="button" id="btnTecla20" name="btnTecla20" class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">+20</button><br>

                                <button onClick="javascript: teclado('n',1);" type="button" id="btnTecla1"  name="btnTecla1"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">1</button>
                                <button onClick="javascript: teclado('n',2);" type="button" id="btnTecla2"  name="btnTecla2"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">2</button>
                                <button onClick="javascript: teclado('n',3);" type="button" id="btnTecla3"  name="btnTecla3"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">3</button>
                                <button onClick="javascript: teclado('b',50);" type="button" id="btnTecla50" name="btnTecla50" class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">+50</button><br>

                                <button onClick="javascript: teclado('o','c');" type="button" id="btnTeclaC"  name="btnTeclaC"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">C</button>
                                <button onClick="javascript: teclado('n',0);" type="button" id="btnTecla0"  name="btnTecla0"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">0</button>
                                <button onClick="javascript: teclado('n','p');" type="button" id="btnTeclaPunto"  name="btnTeclaPunto"  class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">.</button>
                                <button onClick="javascript: teclado('b',100);" type="button" id="btnTecla100" name="btnTecla100" class="btn btn-app" style="font-size:22px; font-weight:bold; color:#000000">+100</button><br>


                            </div>
                        </div>
                    </div>
                    <!-- Loading (remove the following to stop the loading)-->
                    <div class="overlay" id="overlay4">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->
                </div>

            </div>
        </div>


      <div id="precuenta">
      <?php include("imp_precuenta.php");?>
      </div>


        <div class="row" id="pagina3" style="display:none">
            <div class="col-md-12" style="text-align:center">
                <input type="hidden" id="txtIdc" name="txtIdc" value="">
                <button id="btnImprimir" type="button" class="btn btn-success btn-lg" style="background-color:#605CA8;" onClick="javascript:imprimir();">
                    <i class="fa fa-print"></i>&nbsp&nbsp <span id="lblImprimir" style="font-size:18px;">IMPRIMIR COMPROBANTE</span>
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

            

            <!--<div class="col-md-9">
            </div>-->
        </div>
        <?php }?>


        
    </section>

<!-- DataTables -->


<!-- page script 350 -->





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

<script language="JavaScript">
    document.getElementById("txtCodCli").focus();
    $(document).ready(function() {
        $('#overlay2').hide();
        $('#overlay3').hide();
        $('#overlay4').hide();
        deshabilitar_teclado();
    });
  function quitarpago(a){
    $('#overlay3').show();
    ses = document.getElementById("txtSesion").value;

    $.post("sql_quitarpago.php", { cod: a, ses: ses }, function(data){
        if(data>=1){
            document.getElementById("btnQuitar"+a).disabled = "true";
            //swal("Producto quitado", "El producto ya no se encuentra en su carrito.", "success");
            cargarMenu('#grdModalidades','pro_ventas2.php?sesion='+ses);
        }else{
            document.getElementById("btnQuitar"+a).disabled = "";
            swal("Problemas para quitar el pago", "Intentalo nuevamente.", "error");
            $('#overlay3').hide();
        }
    });

  }

    function teclas(event) {
        tecla=(document.all) ? event.keyCode : event.which;
        //if (tecla==13 && event.altKey) {
        if(tecla==13) {
            buscar_clientes();
        }
    }

    function buscar_clientes(){
        vlBuscar = document.getElementById("txtCodCli").value;
        vlCaract = vlBuscar.length;
        c = document.getElementById("lbltipodoc1").innerHTML;
        document.getElementById("txtTipCli").value = "0";

        if(vlBuscar == ""){
            document.getElementById("txtNomCli").value = "";
            if(c=="DNI"){
                document.getElementById("txtNomCli").value = "VENTA AL PUBLICO";
            }
        }else{
            if(c=="DNI" && vlCaract!=8){
                document.getElementById("txtCodCli").focus();
                swal("Dni requiere 8 caracteres.");
            }else{
                if(c=="RUC" && vlCaract!=11){
                    document.getElementById("txtCodCli").focus();
                    swal("Ruc requiere 11 caracteres.");
                }else{
                    document.getElementById("txtCodCli").disabled = "true";
                    document.getElementById("txtNomCli").disabled = "true";
                    document.getElementById("btnSiguiente").disabled = "true";
                    document.getElementById("txtCodCli").value = "Buscando Cliente...";
                    document.getElementById("txtNomCli").value = "";
                    $.post("sql_buscarcliente.php", { buscar: vlBuscar }, function(data){
                        rspta = data.split("|");
                        hay = rspta["0"];
                        cod = rspta["1"];
                        nom = rspta["2"];
                        if(hay==1){
                            document.getElementById("txtCodCli").value = cod;
                            document.getElementById("txtNomCli").value = nom;
                            document.getElementById("txtTipCli").value = "1";
                        }else{
                            document.getElementById("txtCodCli").value = vlBuscar;
                            document.getElementById("txtNomCli").value = "";
                            document.getElementById("txtTipCli").value = "0";
                        }
                        document.getElementById("txtCodCli").disabled = "";
                        document.getElementById("txtNomCli").disabled = "";
                        document.getElementById("btnSiguiente").disabled = "";
                    });
                }
            }
        }
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



  function correo(){
    ses = document.getElementById("txtSesion").value;
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

    function totalizador(){
        var sumita = 0;
        var cadena = document.getElementById("txtCadenaCodigo").value;
        arreglo = cadena.split("|");
        for(i=0;i<=100;i++){
            codigo = "txtTotal"+arreglo[i];
            total = parseFloat(document.getElementById(codigo).value);
            if(total>0){
                sumita = sumita + total;
                document.getElementById("lblTotalPagar1").innerHTML = (sumita).toFixed(2);
                document.getElementById("lblTotalPagar2").innerHTML = (sumita).toFixed(2);
                document.getElementById("txtTotalPagar1").value = sumita;
                document.getElementById("txtTotalPagar2").value = sumita;
                totalizador1();
            }
        }
    }

    function totalizador1(){
        importe = parseFloat(document.getElementById("txtTotalPagar2").value) - parseFloat(document.getElementById("txtTotalRecibido").value);

        if(importe>0){
            document.getElementById("lblVuelto").innerHTML = "0";
            document.getElementById("txtVuelto").value = "0";
            document.getElementById("lblFalta").innerHTML = importe.toFixed(2);
            document.getElementById("txtFalta").value = importe.toFixed(2);
            document.getElementById("pie3").style.display = "block";
            document.getElementById("pie2").style.display = "none";
        }else{
            document.getElementById("lblFalta").innerHTML = "0";
            document.getElementById("txtFalta").value = "0";
            document.getElementById("lblVuelto").innerHTML = importe.toFixed(2);
            document.getElementById("txtVuelto").value = importe.toFixed(2);
            if(importe>0){

            }
            document.getElementById("pie2").style.display = "block";
            document.getElementById("pie3").style.display = "none";
        }
    }



  function paginador(a){
        document.getElementById("btnAtras").disabled = "";
        document.getElementById("btnSiguiente").disabled = "";
        var pagina = parseFloat(document.getElementById("txtPagina").value);
        if(a=="+"){
            if(pagina==2){
                document.getElementById("precuenta").style.display = "none";
                var vuelto = parseFloat(document.getElementById("txtVuelto").value);
                var falta  = parseFloat(document.getElementById("txtFalta").value);
                var grabacion = parseInt(document.getElementById("txtId").value);
                if(falta>0 && vuelto==0){
                    swal("Deberá completar el pago.");
                    return false;
                }
                $('#overlay3').show();
                $('#overlay4').show();
                //-GRABAR COMPROBANTE---------------------------------------
                ses       = document.getElementById("txtSesion").value;
                tipdoc    = document.getElementById("txtTipDoc").value;
                codcli    = document.getElementById("txtCodCli").value;
                nomcli    = document.getElementById("txtNomCli").value;
                tipcli    = document.getElementById("txtTipCli").value;
                obsv      = document.getElementById("txtObsCli").value;
                total     = document.getElementById("txtTotalPagar2").value;
                recibido  = document.getElementById("txtTotalRecibido").value;

                idmesa    = document.getElementById("txtMesa").value;

                $.post("sql_grabarcomprobante.php", { ses: ses, tipdoc: tipdoc, codcli: codcli, nomcli: nomcli, tipcli: tipcli, obsv: obsv, total: total, recibido: recibido, grabacion: grabacion, idmesa: idmesa }, function(data){
                    rspta = data.split("|");
                    data  = rspta["0"];
                    idc   = rspta["1"];

                    if(data>=1){
                        cargarMenu('#imprimir','imp_ticket.php?idc='+idc);
                        document.getElementById("btnAtras").disabled = "true";
                        pagina = 3;
                        document.getElementById("txtPagina").value = pagina;
                        document.getElementById("txtIdc").value = idc;

                        swal("Comprobante generado", "La venta se realizo correctamente.", "success");
                        $('#overlay3').hide();
                        $('#overlay4').hide();

                        vlTitulo = "EMISIÓN DE COMPROBANTE";
                        document.getElementById("pagina1").style.display  = "none";
                        document.getElementById("pagina2").style.display  = "none";
                        document.getElementById("pagina3").style.display  = "block";
                        document.getElementById("lblAtras").innerHTML     = "FORMAS DE PAGO";
                        if(parseFloat(document.getElementById("txtId").value) > 0){
                            document.getElementById("lblSiguiente").innerHTML = "REGRESAR A CONSULTA";
                        }else{
                            document.getElementById("lblSiguiente").innerHTML = "SIGUIENTE VENTA";
                        }
                        document.getElementById("lblTitulo").innerHTML = vlTitulo;

                    }else{
                        if(data==-1){
                            swal("No tiene configurado \nla serie y/o correlativo", "Intentalo nuevamente.", "error");
                        }else{
                            swal("Problemas para grabar el comprobante.", "Intentalo nuevamente.", "error");
                        }
                        $('#overlay3').hide();
                        $('#overlay4').hide();
                        return false;
                    }
                });
                //-------------------------------------------------------
            }

            if(pagina==1){
                var total = parseFloat(document.getElementById("txtTotalPagar1").value);
                document.getElementById("precuenta").style.display == "none";
                if(total==0){
                    swal("La mesa esta vacio.");
                    return false;
                }

                if(document.getElementById("txtTipDoc").value == "BE" || document.getElementById("txtTipDoc").value == "BM" ){
                    if(parseFloat(document.getElementById("txtTotalPagar1").value) > parseFloat(document.getElementById("txtTope").value)){
                        if(document.getElementById("txtCodCli").value == ""){
                            document.getElementById("txtCodCli").focus();
                            swal("Ingrese DNI del cliente.");
                            return false;
                        }
                        if(document.getElementById("txtNomCli").value == ""){
                            document.getElementById("txtNomCli").focus();
                            swal("Ingrese nombre del cliente.");
                            return false;
                        }
                    }
                }

                if(document.getElementById("txtTipDoc").value == "FE" || document.getElementById("txtTipDoc").value == "FM" ){
                    if(document.getElementById("txtCodCli").value == ""){
                        document.getElementById("txtCodCli").focus();
                        swal("Ingrese RUC del cliente.");
                        return false;
                    }
                    if(document.getElementById("txtNomCli").value == ""){
                        document.getElementById("txtNomCli").focus();
                        swal("Ingrese Razon Social.");
                        return false;
                    }
                }
            }

            if(pagina==3){
                //document.getElementById("btnAtras").disabled = "true";
                document.getElementById("precuenta").style.display = "none";
                if(parseFloat(document.getElementById("txtId").value) > 0){
                    cargarMenu("#contenedor","rep_ventas0.php?tip=<?php echo $tip;?>&doc=<?php echo $doc;?>&ini=<?php echo $ini;?>&fin=<?php echo $fin;?>&nom=<?php echo $cli;?>&modo=R");
                }else{
                    location.href = "pro_caja0.php";
                }
                return false;
            }else{
                if(pagina<3){
                    pagina = pagina + 1;
                }
                    if(pagina==3){
                        var vuelto = parseFloat(document.getElementById("txtVuelto").value);
                        var falta  = parseFloat(document.getElementById("txtFalta").value);
                        if(falta>0 && vuelto==0){
                            swal("Deberá completar el pago.");
                            return false;
                        }


                    }
            }
        }else{
            d = parseFloat(pagina);
            if(pagina>0){
                pagina = pagina - 1;
            }
                if(d==2){
                    document.getElementById("btnAtras").disabled == "true";
                }
                if(d==1){
                    location.href = "pro_caja0.php";
                }
        }
        document.getElementById("txtPagina").value = pagina;
        if(pagina==1){
            document.getElementById("precuenta").style.display = "";
            vlTitulo = "MESA <?php echo str_pad($idmesa, 2, "0", STR_PAD_LEFT);?>";
            document.getElementById("pagina1").style.display  = "block";
            document.getElementById("pagina2").style.display  = "none";
            document.getElementById("pagina3").style.display  = "none";
            document.getElementById("lblAtras").innerHTML     = "ATRAS";
            document.getElementById("lblSiguiente").innerHTML = "FORMAS DE PAGO";
            if(parseFloat(document.getElementById("txtId").value) > 0){
                document.getElementById("btnAtras").disabled = "";
            }else{
                document.getElementById("btnAtras").disabled = "";
            }
        }
        if(pagina==2){
            document.getElementById("precuenta").style.display = "none";
            vlTitulo = "FORMAS DE PAGO";
            document.getElementById("pagina1").style.display  = "none";
            document.getElementById("pagina2").style.display  = "block";
            document.getElementById("pagina3").style.display  = "none";
            document.getElementById("lblAtras").innerHTML     = "MESA <?php echo str_pad($idmesa, 2, "0", STR_PAD_LEFT);?>";
            document.getElementById("lblSiguiente").innerHTML = "GRABAR COMPROBANTE";
        }
        if(pagina==3){
            document.getElementById("precuenta").style.display = "none";
            vlTitulo = "EMISIÓN DE COMPROBANTE";
            document.getElementById("pagina1").style.display  = "none";
            document.getElementById("pagina2").style.display  = "none";
            document.getElementById("pagina3").style.display  = "block";
            document.getElementById("lblAtras").innerHTML     = "FORMAS DE PAGO";
            if(parseFloat(document.getElementById("txtId").value) > 0){
                document.getElementById("lblSiguiente").innerHTML = "REGRESAR A CONSULTA";
            }else{
                document.getElementById("lblSiguiente").innerHTML = "SIGUIENTE VENTA";
            }
        }
        document.getElementById("lblTitulo").innerHTML = vlTitulo;
  }
  function imprimir_precuenta(){
    var divToPrint = document.getElementById('precuenta');
        var popupWin = window.open('', '_blank', 'width=600,height=400');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
        popupWin.focus();
        return true;
  }
    function habilitar_teclado(){
        document.getElementById("btnAplicar").disabled = "";
        document.getElementById("txtImporteTeclado").disabled = "";
        document.getElementById("txtObservacionTeclado").disabled = "";
        document.getElementById("btnTecla1").disabled = "";
        document.getElementById("btnTecla2").disabled = "";
        document.getElementById("btnTecla3").disabled = "";
        document.getElementById("btnTecla4").disabled = "";
        document.getElementById("btnTecla5").disabled = "";
        document.getElementById("btnTecla6").disabled = "";
        document.getElementById("btnTecla7").disabled = "";
        document.getElementById("btnTecla8").disabled = "";
        document.getElementById("btnTecla9").disabled = "";
        document.getElementById("btnTecla10").disabled = "";
        document.getElementById("btnTecla20").disabled = "";
        document.getElementById("btnTecla50").disabled = "";
        document.getElementById("btnTecla0").disabled = "";
        document.getElementById("btnTeclaC").disabled = "";
        document.getElementById("btnTeclaPunto").disabled = "";
        document.getElementById("btnTecla100").disabled = "";
    }

    function deshabilitar_teclado(){
        document.getElementById("btnAplicar").disabled = "true";
        document.getElementById("txtImporteTeclado").disabled = "true";
        document.getElementById("txtObservacionTeclado").disabled = "true";
        document.getElementById("btnTecla1").disabled = "true";
        document.getElementById("btnTecla2").disabled = "true";
        document.getElementById("btnTecla3").disabled = "true";
        document.getElementById("btnTecla4").disabled = "true";
        document.getElementById("btnTecla5").disabled = "true";
        document.getElementById("btnTecla6").disabled = "true";
        document.getElementById("btnTecla7").disabled = "true";
        document.getElementById("btnTecla8").disabled = "true";
        document.getElementById("btnTecla9").disabled = "true";
        document.getElementById("btnTecla10").disabled = "true";
        document.getElementById("btnTecla20").disabled = "true";
        document.getElementById("btnTecla50").disabled = "true";
        document.getElementById("btnTecla0").disabled = "true";
        document.getElementById("btnTeclaC").disabled = "true";
        document.getElementById("btnTeclaPunto").disabled = "true";
        document.getElementById("btnTecla100").disabled = "true";
    }

    function seleccionando_formapago(a,b,c,d){
        cadena = document.getElementById("tarjetas_all").value;
        if(cadena.indexOf(a) != -1){
            swal(c+"\nya esta agregado anteriormente.");
            return false;
        }

        var cant = parseFloat(document.getElementById("nro_formaspago").value);
        var tipo = document.getElementById("tipo_habilitado").value;
        document.getElementById("txtImporteTeclado").value = "";
        document.getElementById("txtObservacionTeclado").value = "";
        

        if(tipo=="D"){
            habilitar_teclado();
            document.getElementById("tipo_habilitado").value = "H";
            document.getElementById("cod_formaspago").value = a;
            document.getElementById("nom_formaspago").value = c;
            document.getElementById("tipo_formaspago").value = d;

            for(x=1;x<=cant;x++){
                if(x==b){
                    document.getElementById("formapago"+x).disabled = "";
                    document.getElementById("formapago"+x).style.opacity = "1";
                }else{
                    //document.getElementById("formapago"+x).disabled = "true";
                    //document.getElementById("formapago"+x).style.pointer-events = "none";
                    //document.getElementById("formapago"+x).className = "disabled";
                    document.getElementById("formapago"+x).style.opacity = "0.05";
                }
            }
            document.getElementById("txtImporteTeclado").focus();
        }else{
            deshabilitar_teclado();
            document.getElementById("tipo_habilitado").value = "D";
            document.getElementById("cod_formaspago").value = "";
            document.getElementById("nom_formaspago").value = "";
            document.getElementById("tipo_formaspago").value = "";
            for(x=1;x<=cant;x++){
                document.getElementById("formapago"+x).disabled = "";
                document.getElementById("formapago"+x).style.opacity = "1";
            }
        }
    }

  function pagar(){
    ses = document.getElementById("txtSesion").value;
    codpago = document.getElementById("cod_formaspago").value;
    nompago = document.getElementById("nom_formaspago").value;
    tippago = document.getElementById("tipo_formaspago").value;
    tcambio = document.getElementById("txtTcambio").value;
    importe = document.getElementById("txtImporteTeclado").value;
    mensaje = document.getElementById("txtObservacionTeclado").value;

    if(codpago==""){
        swal("Debe seleccionar la modalidad de pago.");
        document.getElementById("txtImporteTeclado").focus();
        return false;
    }
    if(importe==""){
        swal("Debe ingresar el importe a pagar.");
        document.getElementById("txtImporteTeclado").focus();
        return false;
    }

    $('#overlay3').show();
    document.getElementById("btnAplicar").disabled = "true";
    $.post("sql_agregarpago.php", { codpago: codpago, nompago: nompago, tippago: tippago, tcambio: tcambio, importe: importe, mensaje: mensaje, ses: ses }, function(data){
        //$("#procesar").html(data);
        if(data>=1){
            deshabilitar_teclado();
            var cant = parseFloat(document.getElementById("nro_formaspago").value);
            document.getElementById("txtImporteTeclado").value = "";
            document.getElementById("txtObservacionTeclado").value = "";
            document.getElementById("tipo_habilitado").value = "D";
            document.getElementById("tipo_formaspago").value = "";
            document.getElementById("cod_formaspago").value = "";
            document.getElementById("nom_formaspago").value = "";
            for(x=1;x<=cant;x++){
                document.getElementById("formapago"+x).disabled = "";
                document.getElementById("formapago"+x).style.opacity = "1";
            }
            totalizador1();
            cargarMenu('#grdModalidades','pro_ventas2.php?sesion='+ses);
        }else{
            document.getElementById("btnAplicar").disabled = "";
            swal("Problemas para aplicar el pago", "Intentalo nuevamente.", "error");
            $('#overlay3').hide();
        }
    });
  }


  function cambiar_tipodoc(){
      var tipdoc = document.getElementById("txtTipDoc").value;
      document.getElementById("txtCodCli").value = "";
      document.getElementById("txtNomCli").value = "";
      document.getElementById("txtCodCli").focus();

        $.post("sql_cambiartipodoc.php", { tipdoc: tipdoc }, function(data){   
            rspta = data.split("|");
            a  = rspta["0"];
            b  = rspta["1"];
            c  = rspta["2"];
            d  = rspta["3"];
            document.getElementById("txtTipDoc").value = a;
            document.getElementById("lblTipDoc").innerHTML = b;
            document.getElementById("lbltipodoc1").innerHTML = c;
            document.getElementById("lbltipodoc2").innerHTML = d;

            if(c=="DNI"){
                document.getElementById("txtNomCli").value = "VENTA AL PUBLICO";
            }
        });
  }
  function teclado(a,b){
      if(a=="n"){
          if(b=="p"){
            document.getElementById("txtImporteTeclado").value = (document.getElementById("txtImporteTeclado").value).toString() + ".";
          }else{
            document.getElementById("txtImporteTeclado").value = document.getElementById("txtImporteTeclado").value + b;
          }
      }
      if(a=="o"){
          if(b=="c"){
            document.getElementById("txtImporteTeclado").value = "";
          }
      }
      if(a=="b"){
          if(document.getElementById("txtImporteTeclado").value == ""){
            document.getElementById("txtImporteTeclado").value = b;
          }else{
            document.getElementById("txtImporteTeclado").value = parseFloat(document.getElementById("txtImporteTeclado").value) + b;
          }
      }
  }
</script>



    <script language="JavaScript">


        importe = parseFloat(document.getElementById("txtTotalPagar2").value) - parseFloat(document.getElementById("txtTotalRecibido").value);

        if(importe>0){
            document.getElementById("lblVuelto").innerHTML = "0";
            document.getElementById("txtVuelto").value = "0";
            document.getElementById("lblFalta").innerHTML = importe.toFixed(2);
            document.getElementById("txtFalta").value = importe.toFixed(2);
            document.getElementById("pie3").style.display = "block";
            document.getElementById("pie2").style.display = "none";
        }else{
            document.getElementById("lblFalta").innerHTML = "0";
            document.getElementById("txtFalta").value = "0";
            document.getElementById("lblVuelto").innerHTML = importe.toFixed(2);
            document.getElementById("txtVuelto").value = importe.toFixed(2);
            if(importe>0){

            }
            document.getElementById("pie2").style.display = "block";
            document.getElementById("pie3").style.display = "none";
        }

    </script>

</body>
</html>