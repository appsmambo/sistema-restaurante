<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["user"];?>.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION["usuario"];?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Opciones del sistema</li>
    <li class="treeview menu">
      <a href="#">
        <i class="fa fa-table"></i> <span>Tablas Maestras</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>

      <ul class="treeview-menu">
        <li><a href="man_clientes0.php"><i class="fa fa-circle-o"></i>Clientes</a></li>
      </ul>
    </li>
    
    
  </ul>
</section>
<!-- /.sidebar -->
</aside>