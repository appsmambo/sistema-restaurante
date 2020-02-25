<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];

	$variable  = $_POST['variable'];
	$vlMesa	   = $_POST['mesa'];
	$vgDestino = $_POST['destino'];
	
	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	$vlSqlCad = "update tmp_pedidos set estado=2, user_prepara='$vgCodUser', fec_prepara=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=1 and destino='$vgDestino' and id='$variable'";
	$result = mysqli_query($con, $vlSqlCad);

	if($result){
?>
		<script language="javascript">
			document.getElementById("<?php echo $variable;?>").style.display = "none";
        </script>
<?php
		$ocupa = 2;
		$consulta = mysqli_query($con, "select count(*) as total from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=1");
		if(mysqli_num_rows($consulta)>0){
			$row = mysqli_fetch_array($consulta);
			$encontro = $row[total];
			if($encontro == 0){
				$ocupa = 3;
			}
		}
	
		if($ocupa>0){
			@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
			$vlSqlCad = "update man_mesas set ocupado='$ocupa' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and (ocupado=1 or ocupado=2)";
			$result2 = mysqli_query($con, $vlSqlCad);
			if($result2){
				$consulta2 = mysqli_query($con, "select count(*) as total from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and estado=1 and destino='$vgDestino'");
				$row2 = mysqli_fetch_array($consulta2);
				$encontro2 = $row2[total];
				if($encontro2 == 0){
?>
		<script language="javascript">
			document.getElementById("M<?php echo $vlMesa;?>").style.display = "none";
        </script>
<?php
				}
			}
		}
	}
?>