<table id="example222" class="table table-bordered table-hover">
<thead>
<tr style="background-color:#605CA8; color:#FFFFFF;">
    <th style="width: 75%; text-align:left">Descripcion</th>
    <th style="width: 5%; text-align:center">Cantidad</th>
    <th style="width: 10%; text-align:right">Precio</th>
    <th style="width: 10%; text-align:right">Total</th>
</tr>
</thead>
<tbody>
<?php
  $suma = 0;
  $cadenaCodigo = "";
  $itm = 0; $lib = 0;
  $detalle = "";
    foreach($lista_pedidos as $pedidos){
    $suma = $suma + ($row["cantidad"]*$precio);
    $detalle[] = $pedidos;
?>
<tr>
    <td style="text-align:left"><?php echo $pedidos["descripcion"];?></td>
    <td style="text-align:center"><?php echo $pedidos["cant"];?></td>
    <td style="text-align:right"><?php echo number_format($pedidos["precio"], 2, '.', ',');?></td>
    <td style="text-align:right"><?php echo number_format($pedidos["cant"]*$pedidos["precio"], 2, '.', ',');?></td>

</tr>
<?php }?>
</tbody>
</table>
<div class="col-md-12">
    <button id="btnPreCuenta" name="btnPreCuenta" type="button" class="btn btn-primary" onClick="javascript:imprimir_precuenta();"><i class="fa fa-print"></i> Imprimir PRE-Cuenta</button>
</div>

<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example222').DataTable( {
            "searching"   : false,
            "ordering"    : false,
            "lengthChange": false,
            "paging"      : false,
            "scrollY"     : 350,
            "scrollX"     : false,
            "autoWidth"   : true
        });
    });
</script>