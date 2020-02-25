<?php
	if($_SESSION["iniciado"] != "1"){
		echo "<script language='javascript'> location.href = 'cerrar_sesion.php'; </script>";
	}
?>