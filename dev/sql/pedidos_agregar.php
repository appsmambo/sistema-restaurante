<?php
	@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
	@mysqli_query($con, "SET NAMES 'utf8'");
	$vlSqlCad = "insert into tmp_pedidos(id_empresa, id_sucursal, id_mesa, cant, id_producto, descripcion, precio, estado, destino, user_registro, fec_registro) values('$vgCodEmp', '$vgCodSuc', '$vlMesa', 1, '$cod_plato', '$nom_plato', '$pre_plato', 0, '$des_plato', '$vgCodUser', now())";
	mysqli_query($con, $vlSqlCad);
?>