<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $sesion  = $_GET["sesion"];

    @mysqli_query($con, "SET NAMES 'utf8'");
    $consulta = mysqli_query($con, "select * from tmp_pagos where sesion='$sesion' order by cod_secuencia desc");
    if (mysqli_num_rows($consulta) <= 0){

?>

    <!--<br><br><img src="../dist/img/carritovacio.jpg">-->
<?php
    }else{
?>
                            <table id="example3" class="table table-bordered table-hover">
                                <thead style="width: 100%;">
                                    <tr style="background-color:#605CA8; color:#FFFFFF;">
                                        <th style="width: 70%; text-align:left">Modalidad</th>
                                        <th style="width: 28%; text-align:center">Importe</th>
                                        <th style="width: 2%"></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
  $suma = 0; $tarjetas = "";
  while ($row = mysqli_fetch_array($consulta)){
    $suma = $suma + $row["importe_soles"];
    $tarjetas = $tarjetas."|".$row["cod_pago"];
?>
                                    <tr>
                                        <td style="text-align:left"><?php echo $row["nom_pago"];?></td>
                                        <td style="text-align:right">S/ <?php echo number_format($row["importe_soles"], 2, '.', ',');?></td>
                                        <td><button type="button" id="btnQuitar<?php echo $row["cod_secuencia"];?>" name="btnQuitar<?php echo $row["cod_secuencia"];?>" class="btn btn-block btn-danger btn-sm" onClick="javascript:quitarpago('<?php echo $row["cod_secuencia"];?>')"><i class="fa fa-close"></i></button></td>
                                    </tr>
<?php }?>
                                </tbody>
                            </table>
<?php }?>

<!-- DataTables -->
<!--<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function() {
    $('#overlay3').hide();
});
$(document).ready(function() {
        $('#example3').DataTable( {
            "scrollY":          "290px",
            "scrollCollapse":   true,
            "searching":        false,
            "ordering":         false,
            "paging":           false
        });
    });
</script>

    <script language="JavaScript">
        document.getElementById("pie1").style.display = "block";

        document.getElementById("txtTotalRecibido").value = "<?php echo number_format($suma, 2, '.', ',');?>";
        document.getElementById("lblTotalRecibido").innerHTML = "<?php echo number_format($suma, 2, '.', ',');?>";
        document.getElementById("tarjetas_all").value = "<?php echo $tarjetas;?>";

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