<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");

	$vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];

    $ini = $_GET["ini"];
    $fin = $_GET["fin"];

    $vlCad01="";
    if($ini!="" && $fin!=""){
        $vlCad01 = " and cfechad>='$ini' and cfechad<='$fin'";   
    }

?>
<?php
    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "select a.cid, a.ctipdoc, b.pnombre as cnomtipdoc, a.cnrodoc, a.cfechad, a.cdniruc, a.cnombre, a.cobserva, a.cmoneda, a.cimportev, a.cestado from cabecera as a left join man_parametros as b on a.ctipdoc = b.pcodigo where a.id_empresa='$vgCodEmp' and a.id_sucursal='$vgCodSuc' and  a.cid>0 and b.pcodtabla='COMPR' and b.pcodigo<>'00'".$vlCad01.$vlCad02.$vlCad03.$vlCad04." order by a.cid desc");
    if (mysqli_num_rows($consulta) <= 0){
?>
        <h4 style="color:#FF0000;text-align:center"><i class="icon fa fa-ban"></i> No se encontraron registros</h4>
<?php
    }else{
?>
                <table id="example100" class="table table-bordered table-hover">
                <thead>
                <tr style="background-color:#605CA8; color:#FFFFFF;">
                    <th style="width: 7%; text-align:left">Fecha</th>
                    <th style="width: 25%; text-align:left">Tip. Doc</th>
                    <th style="width: 10%; text-align:left">Nro. Doc</th>
                    <th style="width: 47%; text-align:left">Cliente</th>
                    <th style="width: 10%; text-align:right">Total S/</th>
                    <th style="width: 25%; text-align:left">Estado</th>
                    <th style="width: 1%; text-align:left"></th>
                    <th style="width: 1%; text-align:left"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $suma = 0;
                    while ($row = mysqli_fetch_array($consulta)){
                        $vlEstado = "VALIDO"; $blocked1 = ""; $blocked2 = ""; $blocked3 = ""; $colorfondo = "#ffffff";
                        if($row["cestado"]=="A"){
                            $vlEstado = "ANULADO";
                            $blocked1 = "disabled";
                            $blocked2 = "disabled";
                            $blocked3 = "disabled";
                            $colorfondo = "#F2D7D5";
                        }
                        $suma = $suma + $row["cimportev"];
                ?>
                <tr style="background:<?php echo $colorfondo;?>" id="fila<?php echo $row["cid"];?>">
                    <td><?php echo date("d/m/Y", strtotime($row["cfechad"]));?></td>
                    <td><?php echo $row["cnomtipdoc"];?></td>
                    <td><?php echo $row["cnrodoc"];?></td>
                    <td><?php echo $row["cnombre"];?></td>
                    <td style="text-align:right"><span id="lblTotal<?php echo $row["cid"];?>"><?php echo $row["cimportev"];?></span></td>
                    <td><span id="lblEstado<?php echo $row["cid"];?>"><?php echo $vlEstado;?></span></td>
                    
                    <td><button type="button" id="btnAnula<?php echo $row["cid"];?>" class="btn btn-block btn-default btn-sm" title="Anular" style="width:33px" <?php echo $blocked1;?> onClick="javascript:anular(<?php echo $row["cid"];?>,'<?php echo $row["ctipdoc"];?>','<?php echo $row["cnrodoc"];?>');" ><i class="fa fa-remove"></i></button></td>
                    
                    <td><button type="button" id="btnImprimir<?php echo $row["cid"];?>" <?php echo $blocked3;?> class="btn btn-block btn-default btn-sm" title="Imprimir Ticket" style="width:33px" onClick="javascript:imprime(<?php echo $row["cid"];?>);"><i class="fa fa-print"></i></button></td>
                </tr>
                <?php }?>
                <tfoot>
                <tr style="background-color:#605CA8; color:#FFFFFF;">
                    <th colspan="6" style="text-align:right">Total S/ &nbsp&nbsp&nbsp&nbsp<?php echo number_format($suma, 2, '.', ',');?></th>
                </tr>
                </tfoot>
                </tbody>
                </table>
<?php }?>

<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
        $('#example100').DataTable( {
            "searching"   : false,
            "ordering"    : false,
            "lengthChange": false,
            "paging"      : false,
            "scrollY"     : 350,
            "scrollX"     : false,
            "autoWidth"   : true
        });
    });
$(document).ready(function() {
    $('#overlay10').hide();
});
</script>
