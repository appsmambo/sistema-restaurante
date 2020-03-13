<?php
	error_reporting(0);
	session_start();
	$con = new mysqli("localhost", "root", "", "muelle69");
	if ($con->connect_errno)
	{
		echo "Fallo al conectar a MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
		exit();
	}
	//Valida que los campos de usuario y contraseña tengan datos para su validacion
	@mysqli_query($con, "SET NAMES 'utf8'");
	$user = strtolower(mysqli_real_escape_string($con, $_POST['user']));
	$pass = mysqli_real_escape_string($con, $_POST['pass']);
	if ($user == null || $pass == null)
	{
		echo '<span>Por favor complete todos los campos.</span>';
	}
	else
	{
		$consulta = mysqli_query($con, "select a.id_empresa, a.id_sucursal, a.id_trabajador, a.paterno, a.materno, a.nombres, b.nombre as cargo, DATE_FORMAT(a.fec_registro, '%d/%m/%Y') as fec_registro from man_trabajadores as a left join man_cargos as b on a.id_cargo = b.id_cargo where estado=0 and usuario = '$user' AND clave = '$pass'");
		if (mysqli_num_rows($consulta) > 0)
		{
			$row = mysqli_fetch_array($consulta);
			$_SESSION["user"] 	  	  = strtoupper(trim($user));
			$_SESSION["usuario"] 	  = ucwords(strtolower(trim($row[nombres]))).' '.ucwords(strtolower(trim($row[paterno])));
			$_SESSION["cargo"]   	  = ucwords(strtolower(trim($row[cargo])));
			$_SESSION["fec_registro"] = $row[fec_registro];
			echo '<script>location.href = "php/home.php"</script>';
			
		}
		else
		{
			echo '<span>El usuario y/o clave son incorrectas, vuelva a intentarlo.</span>';
		}
	}   
?>