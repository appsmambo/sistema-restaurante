<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	$vgCodUser = $_SESSION["user"];

	$variable = $_POST['variable'];
	$vlMesa	  = $_POST["mesa"];
	$vlEstado = $_POST["estado"];
	
	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");


	//$vlSqlCad = "update tmp_pedidos set estado=9, user_anula='$vgCodUser', fec_anula=now() where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and id='$variable'";
	$vlSqlCad = "delete from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa' and id='$variable'";
	$rpta = mysqli_query($con, $vlSqlCad);

	//if($rpta>=0){
		$todo = 0; $est1 = 0; $est2 = 0; $est3 = 0;  $est4 = 0; 
		$consulta = mysqli_query($con, "select estado from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'");
		while ($row = mysqli_fetch_array($consulta)){
			$todo = $todo + 1;

			if($row["estado"]==0){	// digitado
				$est1 = $est1 + 1;
			}
			if($row["estado"]==1){	// cocinando
				$est2 = $est2 + 1;
			}
			if($row["estado"]==2){	// atendido
				$est3 = $est3 + 1;
			}
			if($row["estado"]==3){	// caja
				$est4 = $est4 + 1;
			}
		}

		if($est1 == $todo){
			// hay que poner estado = 0
			$vlSqlCad1 = "update man_mesas set ocupado=0, user_ocupado='', fec_ocupado='0000-00-00' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
			mysqli_query($con, $vlSqlCad1);
		}else{
			if($est2 == $todo){
				// hay que poner estado = 1
				$vlSqlCad1 = "update man_mesas set ocupado=1 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
				mysqli_query($con, $vlSqlCad1);
			}else{
				if($est3 == $todo){
					// hay que poner estado = 2
					$vlSqlCad1 = "update man_mesas set ocupado=3 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
					mysqli_query($con, $vlSqlCad1);
				}else{
					if($est4 == $todo){
						// hay que poner estado = 3
						$vlSqlCad1 = "update man_mesas set ocupado=4 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
						mysqli_query($con, $vlSqlCad1);
					}else{
						if($est2>0 && $est3>0){
							$vlSqlCad1 = "update man_mesas set ocupado=2 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$vlMesa'";
							mysqli_query($con, $vlSqlCad1);
						}
					}
				}
			}
		}


	//}

	echo "<script language='JavaScript'> location.href='pro_pedidos1.php?m=$vlMesa'; </script>";
?>