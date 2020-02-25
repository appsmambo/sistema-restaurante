<?php
	$vlServidor  = "localhost";
	$vlBaseDatos = "wwwtodoloquebusc_dbairez";
	$vlUsuario	 = "wwwtodoloquebusc_admin";
	$vlClave	 = "41846788";
	$vgDnsCon	 = mysql_connect($vlServidor, $vlUsuario, $vlClave);
	if (!$vgDnsCon){
		echo "Conexion errada";
		exit;
	}
	mysql_select_db($vlBaseDatos, $vgDnsCon)
?>