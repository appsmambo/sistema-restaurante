<?php
	$agregado = $_POST['agregado'];
	if($agregado==1){
		session_start();
		error_reporting(0);
		include("conexion.php");
		$vgCodEmp = $_SESSION["vgCodEmp"];
		$vgCodSuc = $_SESSION["vgCodSuc"];
		$vgCodUser = $_SESSION["user"];
	
		$variable = $_POST['variable'];
		$vlMesa	  = $_POST["mesa"];
		
    $arreglo = explode("|", $variable);
    $imprimir = 0;
		
		$cod_plato = $arreglo[0];
		$nom_plato = $arreglo[1];
		$pre_plato = $arreglo[2];
		$des_plato = $arreglo[3];
	}else{
		$cod_plato = "sinplato";
	}
	
	if(!empty($cod_plato)){
		if($agregado==1){
			include("pedidos_agregar.php");
		}

    $detalle = "";
    @mysqli_query($con, "SET NAMES 'utf8'");
		$consulta = mysqli_query($con, "select cant, descripcion, comentario, precio, estado, destino, user_registro, fec_registro, user_modifica, fec_modifica, id from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado<>9 order by id desc");
		if (mysqli_num_rows($consulta) > 0){
?>
    <table width="100%" class="table no-margin">
      <thead>
      <tr>
        <th width="4%">Cant</th>
        <th width="77%">Descripcion</th>
        <th width="13%" style="text-align:right">Total</th>
        <th width="13%" style="text-align:left">Estado</th>
        <th width="6%">&nbsp;</th>
        <th width="6%">&nbsp;</th>
      </tr>
      </thead>
      <tbody>
        <?php
            $total = 0;
            $porconfirmar = 0;
            $atencion = 0;
            $validos = 0;
            $porpagar = 0;
            while ($row = mysqli_fetch_array($consulta)){
              $detalle[] = $row;
				$vlNomEst = "Anotado";
				$vlColor  = "info";
				if($row[estado]==0){
					$porconfirmar = $porconfirmar + 1;
				}
				if($row[estado]==2){
					$atencion = $atencion + 1;
        }
        if($row[estado]==3){
					$porpagar = $porpagar + 1;
				}
				if($row[estado]<>9){
					$validos = $validos + 1;
					$total += $row[cant] * $row[precio];
				}

				if($row[estado]==1){
					$vlNomEst = "Cocinando";
					if($row[destino]=="BAR"){
						$vlNomEst = "Preparando";
					}
					$vlColor  = "danger";
				}
				if($row[estado]==2){
					$vlNomEst = "Atendido";
					$vlColor  = "warning";
				}
				if($row[estado]==3){
					$vlNomEst = "Por cobrar";
					$vlColor  = "primary";
				}
				if($row[estado]==9){
					$vlNomEst = "Anulado";
					$vlColor  = "danger";
				}
				
				
				$bgColor = "";
				if($row[estado]==0){
					$bgColor = "style='background-color:#FFD;'";
				}
				if($row[estado]==9){
					$bgColor = "style='background-color:#FFE8E8;'";
				}
        ?>
          <tr <?php echo $bgColor;?>>
            <td style="text-align:center">
			<?php if($row[estado]==0){?>
              <div class="form-group">
                <select style="background-color:#FFD; width:38px; height:30px;" onchange="javascript:editar_cantidad(this.value,<?php echo $row[id];?>,<?php echo $row[precio];?>)">
                  <option value="1" <?php if($row[cant]=="1"){ echo "selected";}?>>1</option>
                  <option value="2" <?php if($row[cant]=="2"){ echo "selected";}?>>2</option>
                  <option value="3" <?php if($row[cant]=="3"){ echo "selected";}?>>3</option>
                  <option value="4" <?php if($row[cant]=="4"){ echo "selected";}?>>4</option>
                  <option value="5" <?php if($row[cant]=="5"){ echo "selected";}?>>5</option>
                  <option value="6" <?php if($row[cant]=="6"){ echo "selected";}?>>6</option>
                  <option value="7" <?php if($row[cant]=="7"){ echo "selected";}?>>7</option>
                  <option value="8" <?php if($row[cant]=="8"){ echo "selected";}?>>8</option>
                  <option value="9" <?php if($row[cant]=="9"){ echo "selected";}?>>9</option>
                  <option value="10" <?php if($row[cant]=="10"){ echo "selected";}?>>10</option>
                  <option value="11" <?php if($row[cant]=="11"){ echo "selected";}?>>11</option>
                  <option value="12" <?php if($row[cant]=="12"){ echo "selected";}?>>12</option>
                  <option value="13" <?php if($row[cant]=="13"){ echo "selected";}?>>13</option>
                  <option value="14" <?php if($row[cant]=="14"){ echo "selected";}?>>14</option>
                  <option value="15" <?php if($row[cant]=="15"){ echo "selected";}?>>15</option>
                  <option value="16" <?php if($row[cant]=="16"){ echo "selected";}?>>16</option>
                  <option value="17" <?php if($row[cant]=="17"){ echo "selected";}?>>17</option>
                  <option value="18" <?php if($row[cant]=="18"){ echo "selected";}?>>18</option>
                  <option value="19" <?php if($row[cant]=="19"){ echo "selected";}?>>19</option>
                  <option value="20" <?php if($row[cant]=="20"){ echo "selected";}?>>20</option>
                </select>
              </div>
            <?php
            	}else{
					      echo $row[cant];
            	}
			?>
            </td>
            <td style="white-space: pre-line;"><?php echo strtoupper($row[descripcion]);?>
            <div style="color:#00F; font-weight:600" id="txtComentario<?php echo $row[id]?>"><?php echo utf8_encode($row[comentario]);?></div><input type="hidden" name="txtDesc<?php echo $row[id]?>" id="txtDesc<?php echo $row[id]?>" value="<?php echo strtoupper($row[descripcion]);?>"><input type="hidden" name="txtObserva<?php echo $row[id]?>" id="txtObserva<?php echo $row[id]?>" value="<?php echo strtoupper($row[comentario]);?>"></td>
            <td style="text-align:right"><div id="total<?php echo $row[id]?>"><?php echo number_format($row[cant]*$row[precio], 2, '.', ',');?></div></td>
            <td style="text-align:left"><span class="label label-<?php echo $vlColor;?>"><?php echo $vlNomEst;?></span></td>
            <td style="text-align:right">

            

            <?php if($row[estado]==0){?>
            <button id="btnObsv<?php echo $row[id]?>" name="btnObsv<?php echo $row[id]?>" type="button" title="Adicionar comentario" class="btn btn-Default" data-toggle="modal" data-target="#modal-obsv" onclick="javascript:marcar('<?php echo $row[id]?>');"><i class="fa fa-pencil"></i></button>
            <?php }?></td>
            <td style="text-align:right"><?php if((($_SESSION["cargo"] == "GERENTE GENERAL" || $_SESSION["cargo"] == "ADMINISTRADOR" || $_SESSION["cargo"] == "SISTEMAS") && ($row[estado]!=9)) || (($_SESSION["cargo"] != "GERENTE GENERAL" && $_SESSION["cargo"] != "ADMINISTRADOR" && $_SESSION["cargo"] != "SISTEMAS") && ($row[estado]==0))){?><button id="btnEli<?php echo $row[id]?>" name="btnEli<?php echo $row[id]?>" type="button" title="Anular item" class="btn btn-Default" data-toggle="modal" data-target="#modal-danger" onclick="javascript:puntear(<?php echo $row[id];?>,<?php echo $row[estado];?>);"><i class="fa fa-times"></i></button><?php }?>
            
            </td>
            
          </tr>
        <?php }?>
      </tbody>
      <?php if($total>0){?>
      <tfoot>
          <tr style="font-size:18px">
            <th>&nbsp;</td>
            <th style="text-align:right">Total:</td>
            <th style="text-align:right"><?php echo number_format($total, 2, '.', ',');?></td>
            <td></td>
          </tr>
      </tfoot>
      <?php }?>
    </table>
    <?php if($porconfirmar>0){?>
        <div class="box-footer clearfix no-border">
          <button id="btnConfirmar" name="btnConfirmar" type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info"><i class="fa fa-check"></i> Confirmar</button>
        </div>
	<?php }?>
  <?php if($atencion==$validos){?>
      <div class="box-footer clearfix no-border">
        <button id="btnConfirmar" name="btnConfirmar" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-success"><i class="fa fa-check"></i> Cerrar Mesa</button>
      </div>
  <?php }?>
  <?php if($porpagar==$validos){?>
      <div class="box-footer clearfix no-border">
        <button id="btnAbrir" name="btnAbrir" type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-abrir"><i class="fa fa-check"></i> Abrir Mesa</button>

        <?php if($vgFlujo==1){ $imprimir = 1;?>
        <button id="btnPreCuenta" name="btnPreCuenta" type="button" class="btn btn-primary" onClick="javascript:imprimir();"><i class="fa fa-print"></i> Imprimir PRE-Cuenta</button><?php }?>
      </div>
  <?php }?>
<?php }}?>