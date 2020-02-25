<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");

    $vlUser = $_SESSION["user"];
    $vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    
    $fecha   = $_GET["fecha"];
    $estaper = $_GET["estaper"];
?>
<?php


    $consulta2 = mysqli_query($con, "select concepto, importe, id from pro_gastoscaja_cab where id_empresa='$vgCodEmp' AND id_sucursal='$vgCodSuc' AND fecha='$fecha' and user_registro='$vlUser' order by id desc");
    if (mysqli_num_rows($consulta2) > 0){
?>
        <div class="form-group">
            <table id="example3" class="table table-bordered table-hover" style="background-color:#FFFFFF;" align="center">
                <thead>
                <tr style="background-color:#605CA8; color:#FFFFFF;">
                    <th style="width: 80%; text-align:left">CONCEPTO</th>
                    <th style="width: 10%; text-align:center">IMPORTE</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sumatoria1 = 0;
                    while ($row = mysqli_fetch_array($consulta2)){
                        $sumatoria1 = $sumatoria1 + $row["importe"];
                ?>
                    <tr <?php if($estaper==0){?> onclick="javascript:eliminar_egresos('<?php echo $row[id];?>')" <?php }?>>
                        <td><?php echo $row["concepto"];?></td>
                        <td style="text-align:right"><?php echo number_format($row["importe"], 2, '.', ',');?></td>
                    </tr>
                <?php }?>
                <tr style="background-color:#605CA8; color:#FFFFFF;font-size:14px">
                    <td style="text-align:right">TOTAL DE GASTOS:</td>
                    <td style="text-align:right"><?php echo number_format($sumatoria1, 2, '.', ',');?></td>
                </tr>
                </tbody>
            </table>
        </div>
<?php }?>