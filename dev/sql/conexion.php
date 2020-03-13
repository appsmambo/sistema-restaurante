<?php
	error_reporting(0);
	$con = new mysqli("localhost", "root", "", "muelle69");
	if ($con->connect_errno){
		echo "Fallo al conectar a MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
		exit();
	}
?>