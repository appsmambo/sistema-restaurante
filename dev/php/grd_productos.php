<?php
  session_start();
  include("sys_seguridad.php");
  include("../sql/conexion.php");

  $tipo0            = $_POST["txtTipo0"];
  $cboCategoria0    = $_POST["cboCategoria0"];
  $cboSubcategoria0 = $_POST["cboSubcategoria0"];
  $cboTipoProducto0 = $_POST["cboTipoProducto0"];
  $cboPublicar0     = $_POST["cboPublicar0"];
  $txtNombre0       = $_POST["txtNombre0"];

  $vlCad01 = ""; $vlCad02 = ""; $vlCad03 = ""; $vlCad04 = ""; $vlCad05 = "";
  
  if($cboCategoria0 != ""){
    $vlCad01 = " and categoria='$cboCategoria0'";
  }

  if($cboSubcategoria0 != ""){
    $vlCad02 = " and subcategoria='$cboSubcategoria0'";
  }else{
    if($cboCategoria0 != ""){
      $consulta = mysqli_query($con, "select cod_secuencia from man_tablas where tabla='$tipo0' and tipo='CATEGORIAS' and item='$cboCategoria0'");
      if (mysqli_num_rows($consulta) > 0){
        $x = 0;
        while ($row = mysqli_fetch_array($consulta)){
          $codigo = $row["cod_secuencia"];
          $x = $x + 1;
          if($x==1){
            $vlCad02 = " and (subcategoria='$codigo'";
          }else{
            $vlCad02 = $vlCad02 ." or subcategoria='$codigo'";
          }
        }
        if($x>0){
          $vlCad02 = $vlCad02 .")";
        }
      }
    }
  }

  if($cboTipoProducto0 != ""){
    $vlCad03 = " and tipo_producto='$cboTipoProducto0'";
  }

  if($cboPublicar0 != ""){
    $vlCad04 = " and publicar='$cboPublicar0'";
  }

  if($txtNombre0 != ""){
    $vlCad05 = " and nombre like '%$txtNombre0%'";
  }

  @mysqli_query($con, "SET NAMES 'utf8'");
  $consulta = mysqli_query($con, "select * from man_productos0 where tabla='$tipo0'".$vlCad01.$vlCad02.$vlCad03.$vlCad04.$vlCad05." order by cod_secuencia desc");
  if (mysqli_num_rows($consulta) == 0){
?>
    <h4 style="color:#FF0000"><i class="icon fa fa-ban"></i> No se encontraron productos</h4>
<?php
  }else{
?>

          <div class="box box-danger">


            <!-- /.box-header -->
            <div class="box-body">



                      <div class="row">


                        <?php
                            while ($row = mysqli_fetch_array($consulta)){
                        ?>
                        <div class="col-md-3">
                          <!-- Widget: user widget style 1 -->
                          <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="box-footer no-padding">
                              <h4 class="box-title" style="font-width:bold;text-align:center"><?php echo $row["nombre"];?></h4>
                              <img src='../../images/productos_panel/<?php echo $row["imagen"];?>' style="width:100%;height:auto; cursor:pointer;" onClick="javascript:document.getElementById('filtros').style.display='none';cargarMenu('#grid','man_productos1.php?tipo=<?php echo $tipo0;?>&id=<?php echo $row["cod_secuencia"];?>');">
                              <!--<ul class="nav nav-stacked">
                                <li><a href="#">Visitas <span class="pull-right badge bg-blue">0</span></a></li>
                                <li><a href="#">Me gusta <span class="pull-right badge bg-aqua">0</span></a></li>
                                <li><a href="#">Pedidos <span class="pull-right badge bg-green">0</span></a></li>
                              </ul>-->
                              <div style="text-align:center;">
                                <br>
                                <button id="btnEdit" name="btnEdit" type="button" class="btn btn-warning" onClick="javascript:document.getElementById('filtros').style.display='none';cargarMenu('#grid','man_productos1.php?tipo=<?php echo $tipo0;?>&id=<?php echo $row["cod_secuencia"];?>');"><i class="fa fa-wrench"></i></button>
                                <button id="btnDelete" name="btnDelete" type="button" class="btn btn-danger"><i class="fa fa-times" onClick="javascript:eliminar2(<?php echo $row["cod_secuencia"]?>);"></i></button><br><br>
                              </div>
                            </div>
                          </div>
                          <!-- /.widget-user -->
                        </div>
                        <?php }?>

                        



                      </div>
                      <!-- /.row -->



            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
<?php }?>