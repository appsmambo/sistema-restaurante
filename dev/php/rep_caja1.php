<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");

    $vlUser = $_SESSION["user"];
    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    
    $fecha   = $_GET["fecha"];
    $idaper  = $_GET["idaper"];
    $impaper = $_GET["impaper"];
    $estaper = $_GET["estaper"];
?>
<?php
    //-Apertura y cierre de caja------------------------
    $apertura = ""; $haycaja = 0;
    /*@mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select id_apertura, importe_apertura, importe_cierre, estado from pro_aperturacajas where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_apertura='$idaper'");
    $row = mysqli_fetch_array($consulta1);
    $apertura[] = $row;
    $haycaja = 1;
    }*/                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
    //-------------------------------------------------

    //-Cabecera de comprobantes------------------------
    $vuelto = 0;
    $cabecera = "";
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select ctipdoc, cnrodoc, cestado, csaldo from cabecera where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and cfechad='$fecha' and cucrea='$vlUser' order by ctipdoc, cnrodoc");
    while($row = mysqli_fetch_array($consulta1)){
        $cabecera[] = $row;
        $vuelto = $vuelto  + ABS($row["csaldo"]);
    }
    //-------------------------------------------------

    //-Pagos de comprobantes------------------------
    $pagos = "";
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select ffpago as tip_pago, cod_pago, nom_pago, fsoles as soles, fdolar as dolar, ftcambio as tcambio, fobserva as observacion from pagos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and ffechad='$fecha' and user_registro='$vlUser' order by cod_pago");
    while($row = mysqli_fetch_array($consulta1)){
        $pagos[] = $row;
    }
    //-------------------------------------------------

    //-Gastos de Caja------------------------
    $gastos = "";
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta1 = mysqli_query($con, "select concepto, importe, id from pro_gastoscaja_cab where id_empresa='$vgCodEmp' AND id_sucursal='$vgCodSuc' AND fecha='$fecha' and user_registro='$vlUser' order by id desc");
    while($row = mysqli_fetch_array($consulta1)){
        $gastos = $gastos  + $row["importe"];
    }
    //-------------------------------------------------

    $consulta2 = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre from man_parametros where pcodtabla='COMPR' and pcodigo<>'00' order by pimporte1");
    if (mysqli_num_rows($consulta2) <= 0){
?>
        <h4 style="color:#FF0000;text-align:center"><i class="icon fa fa-ban"></i> No se encontraron registros</h4>
<?php
    }else{
?>
                <table id="example2" class="table table-bordered table-hover" style="background-color:#FFFFFF;">
                <thead>
                <tr style="background-color:#222D32; color:#FFFFFF;">
                    <th colspan="6" style="text-align:center">RESUMEN POR COMPROBANTE DE PAGO</th>
                </tr>
                <tr style="background-color:#605CA8; color:#FFFFFF;">
                    <th style="width: 25%; text-align:left">Tipo Comprobante</th>
                    <th style="width: 10%; text-align:center">Total Emitidos</th>
                    <th style="width: 10%; text-align:center">Total Validos</th>
                    <th style="width: 10%; text-align:center">Total Anulados</th>
                    <th style="width: 10%; text-align:center">Nro Inicial</th>
                    <th style="width: 10%; text-align:center">Nro Final</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    while ($row = mysqli_fetch_array($consulta2)){
                        $vlCodigo = $row["codigo"];
                        $vlSuma1 = 0; $vlSuma2 = 0; $vlSuma3 = 0; $vlNroIni = ""; $vlNroFin = "";

                        if($cabecera!=""){
                            foreach($cabecera as $arrayCab){
                                if($arrayCab["ctipdoc"] == $vlCodigo){
                                    $vlSuma1 = $vlSuma1 + 1;

                                    if($vlSuma1==1){
                                        $vlNroIni = $arrayCab["cnrodoc"];
                                    }
                                    $vlNroFin = $arrayCab["cnrodoc"];

                                    if($arrayCab["cestado"] == "A"){
                                        $vlSuma3 = $vlSuma3 + 1;
                                    }else{
                                        $vlSuma2 = $vlSuma2 + 1;
                                    }
                                }
                            }
                        }
                ?>
                <tr>
                    <td><?php echo $row["nombre"];?></td>
                    <td style="text-align:center"><?php if($vlSuma1>0){ echo $vlSuma1;}?></td>
                    <td style="text-align:center"><?php if($vlSuma2>0){ echo $vlSuma2;}?></td>
                    <td style="text-align:center"><?php if($vlSuma3>0){ echo $vlSuma3;}?></td>
                    <td style="text-align:center"><?php echo $vlNroIni;?></td>
                    <td style="text-align:center"><?php echo $vlNroFin;?></td>
                </tr>
                <!--<div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                </div>-->
                <?php }?>
                </tbody>
                </table>
<?php }?>

<?php
    $consulta2 = mysqli_query($con, "select pcodigo as codigo, pnombre as nombre from man_parametros where pcodtabla='CAJ01' and pcodigo<>'00' order by panexo1");
    if (mysqli_num_rows($consulta2) <= 0){
?>
        <h4 style="color:#FF0000;text-align:center"><i class="icon fa fa-ban"></i> No se encontraron registros</h4>
<?php
    }else{
?>
                <table id="example2" class="table table-bordered table-hover" style="background-color:#FFFFFF;">
                <thead>
                <tr style="background-color:#222D32; color:#FFFFFF;">
                    <th colspan="6" style="text-align:center">RESUMEN POR FORMA DE PAGO</th>
                </tr>
                <tr style="background-color:#F39C12; color:#FFFFFF;">
                    <th style="width: 25%; text-align:left">Forma de pago</th>
                    <th style="width: 10%; text-align:center">Soles</th>
                    <th style="width: 10%; text-align:center">Dolares</th>
                    <th style="width: 10%; text-align:center">T/C</th>
                    <th style="width: 10%; text-align:center">Al cambio S/.</th>
                    <th style="width: 10%; text-align:center">T. Efectivo S/.</th>
                </tr>
                </thead>
                <tbody>
                <tr style="background-color:#CFFFBF">
                    <td>APERTURA DE CAJA</td>
                    <td style="text-align:right"><?php if($impaper>0){ echo number_format($impaper, 2, '.', ',');}?></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"><?php if($impaper>0){ echo number_format($impaper, 2, '.', ',');}?></td>
                </tr>
                <?php
                    $vlSoles = 0; $vlDolar = 0; $cambiado = 0; $vlTotalIngreso = 0;

                    if($pagos!=""){
                        foreach($pagos as $arrayPago){
                            if($arrayPago["cod_pago"] == "ES"){
                                $vlSoles = $vlSoles + $arrayPago["soles"];
                            }
                            if($arrayPago["cod_pago"] == "ED"){
                                $vlDolar = $vlDolar + $arrayPago["dolar"];
                                $cambiado = $cambiado + $arrayPago["soles"];
                            }
                            $tipoCambio = $arrayPago["tcambio"];
                        }
                        $vlSoles = $vlSoles-$vuelto;
                    }
                    $vlTotalSoles = $vlSoles + $cambiado;
                    $vlTotalIngreso = $vlTotalIngreso + $vlTotalSoles;
                ?>
                <tr>
                    <td><?php echo "EFECTIVO";?></td>
                    <td style="text-align:right"><?php if($vlSoles>0){ echo number_format($vlSoles, 2, '.', ',');}?></td>
                    <td style="text-align:right"><?php if($vlDolar>0){ echo number_format($vlDolar, 2, '.', ',');}?></td>
                    <td style="text-align:right"><?php if($vlDolar>0){ echo number_format($tipoCambio, 2, '.', ',');}?></td>
                    <td style="text-align:right"><?php if($cambiado>0){ echo number_format($cambiado, 2, '.', ',');}?></td>
                    <td style="text-align:right"><?php if($vlTotalSoles>0){ echo number_format($vlTotalSoles, 2, '.', ',');}?></td>
                </tr>
                <?php
                    while ($row = mysqli_fetch_array($consulta2)){
                        $vlCodigo = $row["codigo"];
                        $vlSuma1 = 0;

                        if($pagos!=""){
                            foreach($pagos as $arrayPago){
                                if($arrayPago["cod_pago"] == $vlCodigo){
                                    $vlSuma1 = $vlSuma1 + $arrayPago["soles"];
                                }
                            }
                        }
                        $vlTotalIngreso = $vlTotalIngreso + $vlSuma1;
                ?>
                <tr>
                    <td><?php echo "TARJETA ".$row["nombre"];?></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"><?php if($vlSuma1>0){ echo number_format($vlSuma1, 2, '.', ',');}?></td>
                </tr>
                <?php }?>
                <tr style="background-color:#FFD9D9">
                    <td>VUELTOS</td>
                    <td style="text-align:right"><?php if($vuelto>0){ echo '- '.number_format($vuelto, 2, '.', ',');}?></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"><?php if($vuelto>0){ echo '- '.number_format($vuelto, 2, '.', ',');}?></td>
                </tr>
                <tr style="background-color:#BFDFFF">
                    <td>GASTOS DE CAJA</td>
                    <td style="text-align:right"><?php if($gastos>0){ echo '- '.number_format($gastos, 2, '.', ',');}?></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"></td>
                    <td style="text-align:right"><?php if($gastos>0){ echo '- '.number_format($gastos, 2, '.', ',');}?></td>
                </tr>
                </tbody>
                </table>
<?php }?>

<div class="col-md-4">
    <div class="input-group input-group-lg" style="text-align:left">
        <span class="input-group-btn">
            <button type="btnEgresos" onclick="abrir_egresos();" class="btn btn-success btn-flat">GASTOS DE CAJA: <span style="font-size:18px">&nbsp&nbsp&nbspS/ <?php echo number_format($gastos, 2, '.', ',');?></span></button>
        </span>
    </div>
</div>
<div class="col-md-4">
    <div class="input-group input-group-lg" style="text-align:left">
        <span class="input-group-btn" style="text-align:center">
            <button type="btnEgresos" onclick="abrir_ingresos();" class="btn btn-primary btn-flat">INGRESO DE CAJA: <span style="font-size:18px">&nbsp&nbsp&nbspS/ <?php echo number_format($gastos, 2, '.', ',');?></span></button>
        </span>
    </div>
</div>

<div class="col-md-4">
    <div class="input-group input-group-lg" style="text-align:right">
        <span class="input-group-btn">
            <button type="btnTipBus" class="btn btn-warning btn-flat">TOTAL CAJA: <span style="font-size:18px">&nbsp&nbsp&nbspS/ <?php echo number_format($vlTotalIngreso+$impaper-$vuelto-$gastos, 2, '.', ',');?></span></button>
        </span>
    </div>
</div>

<!--MODAL GASTOS DE CAJA-->
<div class="modal fade" id="mdlEgresos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#00A65A; color:#FFFFFF">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gastos de Caja</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if($estaper==0){?>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Concepto:</label>
                                <input type="text" class="form-control input-sm" id="txtConcepto" name="txtConcepto" style="text-transform:uppercase">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Importe:</label>
                                <input type="text" class="form-control input-sm" id="txtImporte" name="txtImporte">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <br>
                                <button type="button" class="btn btn-primary" style="width:100%" id="btnGrabarEgreso" name="btnGrabarEgreso" onClick="javascript:grabar_egresos();">GRABAR</button>
                            </div>
                        </div>
                    <?php }?>

                    <div class="col-md-12" id="egresito">
                    </div>



                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left" id="btnImprimirGasto" name="btnImprimirGasto" onclick="printDiv();"><i class="fa fa-print"></i> Imprimir</button>
                <button type="button" class="btn btn-default pull-right" id="btnCerrarVentana" name="btnCerrarVentana">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="grabaegreso"></div>
<!--FIN MODAL GASTOS CAJA-->



<script type="text/javascript">
    function abrir_egresos(){
        cargar_egresos();
        $('#mdlEgresos').modal('show');
    }

    function grabar_egresos(){
        if(document.getElementById("txtConcepto").value==""){
            document.getElementById("txtConcepto").focus();
            swal("Ingrese Concepto", "", "error");
            return false;
        }
        if(document.getElementById("txtImporte").value==""){
            document.getElementById("txtImporte").focus();
            swal("Ingrese Importe", "", "error");
            return false;
        }

        fecha    = document.getElementById("txtFecha").value;
        concepto = document.getElementById("txtConcepto").value;
        importe  = document.getElementById("txtImporte").value;

        document.getElementById("btnGrabarEgreso").disabled = "true";

		$.post("../sql/egresos_grabar.php", { fecha: fecha, concepto: concepto , importe: importe }, function(data){
            cargar_egresos();
		$("#grabaegreso").html(data);
		});
        
	}

    function eliminar_egresos(id){
        swal({
            title: "Desea eliminar?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
            function(isConfirm) {
            if (isConfirm) {
                $.post("../sql/egresos_eliminar.php", { id: id }, function(data){
                    $("#grabaegreso").html(data);
                });
                cargar_egresos();
            }
        });
	}

    function cargar_egresos(){
        fecha = document.getElementById("txtFecha").value;
        cargarMenu("#egresito","rep_caja2.php?fecha="+fecha+"&estaper=<?php echo $estaper;?>");
    }

    function printDiv() {
        fecha = document.getElementById("txtFecha").value;

        ano = fecha.substring(0, 4);
        mes = fecha.substring(5, 7);
        dia = fecha.substring(8, 10);

        newfecha = dia + "/" + mes + "/" + ano;


        var divToPrint = document.getElementById('egresito');
        var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            'padding;0.5em;' +
            '}' +
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        newWin.document.write("<h3 align='center'>Gastos de Caja<br>Fecha " + newfecha + " </h3>");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }

    $(document).ready(function() {
        $('#overlay10').hide();
    });
    $('#btnCerrarVentana').click(function(){
        //generar();
        $('#mdlEgresos').modal('hide');
    })

    $(document).ready(function() {
            $('#example2').DataTable( {
                "scrollY":          "380px",
                "scrollCollapse":   true,
                "searching":        false,
                "ordering":         false,
                "paging":           false
            });
    });

    document.getElementById("btnCerrar").disabled = "";
    document.getElementById("btnImprimir").disabled = "";
    document.getElementById("btnCerrar").style.display = "";
    document.getElementById("btnImprimir").style.display = "";

    <?php if($estaper==1){?>
        document.getElementById("btnCerrar").style.display = "none";
    <?php }?>

    $('#overlay10').hide();

</script>