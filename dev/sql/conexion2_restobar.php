<?php
	error_reporting(0);
	$con = new mysqli("localhost", "wwwtodoloquebusc_admin", "41846788", "wwwtodoloquebusc_dbrestobar");
	if ($con->connect_errno){
		echo "Fallo al conectar a MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
		exit();
	}
?>