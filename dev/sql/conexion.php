<?php
	error_reporting(0);
	$con = new mysqli("localhost", "tsoluxio_muelle69", "QBdixPYUe5aH5b8pW4", "tsoluxio_dbmuelle69-dev");
	if ($con->connect_errno){
		echo "Fallo al conectar a MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
		exit();
	}
?>