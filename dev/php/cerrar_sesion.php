<?php
	session_start();
	session_destroy();
	echo "<script type='text/javascript'> parent.location.href = '../index.php'; </script>";
?>