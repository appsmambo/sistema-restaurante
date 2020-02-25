<?php
	session_start();
	error_reporting(0);
	include("conexion.php");
	$vgCodUser = $_SESSION["user"];
	$vgCodEmp = $_SESSION["vgCodEmp"];
	$vgCodSuc = $_SESSION["vgCodSuc"];
	
    $fecha	    = $_POST["fecha"];
    $concepto	= strtoupper($_POST["concepto"]);
	$importe    = $_POST["importe"];
	
	if($concepto !="" && $importe != ""){
		@mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
		@mysqli_query($con, "SET NAMES 'utf8'");
        $consulta = mysqli_query($con, "insert into pro_gastoscaja_cab(id_empresa, id_sucursal, fecha, concepto, importe, user_registro, fec_registro) values('$vgCodEmp', '$vgCodSuc', '$fecha', '$concepto', '$importe', '$vgCodUser', now())");
		if($consulta){
            //echo '<script> generar(); </script>';
            echo '<script>document.getElementById("txtConcepto").value = "";</script>';
            echo '<script>document.getElementById("txtImporte").value = "";</script>';
            echo '<script>document.getElementById("txtConcepto").focus();</script>';
            echo '<script>document.getElementById("btnGrabarEgreso").disabled = "";</script>';
            echo '<script>swal("Registro grabado", "", "success");</script>';
			exit();
		}else{
			echo '<script>alert("Problemas con el servidor, Vuelva a intentarlo mas tarde.");</script>';
			echo '<script>location.href = "../php/cerrar_sesion.php"</script>';
			exit();
		}
	}else{
		echo '<script>alert("Enlace roto.");</script>';
		echo '<script>location.href = "../php/cerrar_sesion.php"</script>';
		exit();
	}
?>