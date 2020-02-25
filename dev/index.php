<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestion Restaurante</title>

	<script language="javascript">
		function Validar(user, pass){
			$.ajax({
				url: "sql/login.php",
				type: "POST",
				data: "user="+user+"&pass="+pass,
				success: function(resp){
				$('#resultado').html(resp)
				}       
			});
		}
    </script>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="height:0; background-image: url(dist/img/bg.jpg)">
<div class="login-box">
  <div class="login-logo">
  	<img src="dist/img/logo.png">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Ingrese sus credenciales</p>

    <form method="post" id="frmLogin">
        <label>Usuario:</label>
        <div class="input-group">
            <span class="input-group-addon input-lg"><i class="fa fa-male"></i></span>
            <input type="text" class="form-control input-lg" id="user" name="user" autocomplete="off" style="text-transform:uppercase;" autofocus>
        </div>
        <br>

        <label>Contraseña:</label>
        <div class="input-group">
            <span class="input-group-addon input-lg"><i class="fa fa-bolt"></i></span>
            <input type="password" class="form-control input-lg" id="pass" name="pass" autocomplete="off" style="text-transform:uppercase;">
        </div>
        <div id="resultado" class="text-red"></div>
        <br>

        <div class="box-footer">
            <button type="button" class="btn btn-primary pull-right btn-lg" id="btnIngresar" onClick="Validar(document.getElementById('user').value, document.getElementById('pass').value);"><i class="fa fa-unlock-alt"></i>&nbsp Ingresar</button>
        </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>