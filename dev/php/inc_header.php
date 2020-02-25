<header class="main-header">
<a href="home.php" class="logo">
  <span class="logo-mini"><img src="../dist/img/logo_blank_thumb.png"></span>
  <span class="logo-lg"><img src="../dist/img/logo_blank.png"></span>
  <!--<span class="logo-lg">"Mama Mechi"</span>-->
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="../dist/img/user/<?php echo strtoupper($_SESSION["foto"]);?>.jpg" class="user-image" alt="User Image">
          <span class="hidden-xs"><?php echo $_SESSION["usuario"];?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="../dist/img/user/<?php echo strtoupper($_SESSION["foto"]);?>.jpg" class="img-circle" alt="User Image">

            <p>
              <?php echo $_SESSION["usuario"].' - '.ucwords(strtolower($_SESSION["cargo"]));?>
              <small><?php echo "Flujo para hoy:<br>".$_SESSION["nomflujo"];?></small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="#" class="btn btn-default btn-flat">Cambiar Contraseña</a>
            </div>
            <div class="pull-right">
              <a href="cerrar_sesion.php" class="btn btn-default btn-flat">Cerrar Sesion</a>
            </div>
          </li>
        </ul>
      </li>
      <!-- Control Sidebar Toggle Button -->
      <!-- <li>
        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
      </li>-->
    </ul>
  </div>

</nav>
</header>