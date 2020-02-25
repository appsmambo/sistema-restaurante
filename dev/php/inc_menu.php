<?php 
	if($_SESSION["cargo"] == "GERENTE GENERAL" || $_SESSION["cargo"] == "ADMINISTRADOR" || $_SESSION["cargo"] == "SISTEMAS"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
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
      <a href="#" class="input-lg">
        <i class="fa fa-table"></i> <span>Tablas Maestras</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>

      <ul class="treeview-menu">
        <li><a href="man_cargos0.php"><i class="fa fa-circle-o"></i>Cargos</a></li>
        <li><a href="man_clientes0.php"><i class="fa fa-circle-o"></i>Clientes</a></li>
        <li><a href="man_documentoscontables0.php"><i class="fa fa-circle-o"></i>Documentos Contables</a></li>
        <li><a href="man_empresas0.php"><i class="fa fa-circle-o"></i>Empresas</a></li>
        <li><a href="man_mesas0.php"><i class="fa fa-circle-o"></i>Mesas</a></li>
        <li><a href="man_productos0.php"><i class="fa fa-circle-o"></i>Productos</a></li>
        <li><a href="man_proveedores0.php"><i class="fa fa-circle-o"></i>Proveedores</a></li>
        <li><a href="man_sucursales0.php"><i class="fa fa-circle-o"></i>Sucursales</a></li>
        <li><a href="man_tipodocumentos0.php"><i class="fa fa-circle-o"></i>Tipos de Documentos</a></li>
        <li><a href="man_tipopagos0.php"><i class="fa fa-circle-o"></i>Tipo de Pagos</a></li>
        <li><a href="man_tipoproductos0.php"><i class="fa fa-circle-o"></i>Tipo de Productos</a></li>
        <li><a href="man_trabajadores0.php"><i class="fa fa-circle-o"></i>Trabajadores</a></li>
        <li><a href="man_unidadmedida0.php"><i class="fa fa-circle-o"></i>Unidad de Medida</a></li>
      </ul>
    </li>
    
    <li>
      <a href="pro_pedidos0.php" class="input-lg">
        <i class="fa fa-edit"></i> <span>Mozos</span>
      </a>
    </li>
    
    <li>
      <a href="pro_cocina0.php" class="input-lg">
        <i class="fa fa-th"></i> <span>Cocina</span>
      </a>
    </li>
    
    <li>
      <a href="pro_bar0.php" class="input-lg">
        <i class="fa fa-book"></i> <span>Bar</span>
      </a>
    </li>


    <li class="treeview menu">
      <a href="#" class="input-lg">
        <i class="fa fa-table"></i> <span>Caja</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>

      <ul class="treeview-menu">
        <li><a href="rep_caja0.php"><i class="fa fa-circle-o"></i>Apertura y cierre Caja</a></li>
        <li><a href="pro_caja0.php"><i class="fa fa-circle-o"></i>Emitir Comprobante</a></li>
        <li><a href="rep_ventas0.php"><i class="fa fa-circle-o"></i>Consulta de comprobantes</a></li>
        <li><a href="man_tcambio0.php"><i class="fa fa-circle-o"></i>Tipo de Cambio</a></li>
      </ul>
    </li>

  
  </ul>
</section>
<!-- /.sidebar -->
</aside>
<?php }?>



<?php 
	if($_SESSION["cargo"] == "ANFITRIONA"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
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
      <a href="#" class="input-lg">
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
<?php }?>


<?php 
	if($_SESSION["cargo"] == "MESERO" || $_SESSION["cargo"] == "JEFE DE SALA"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION["usuario"];?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Opciones del sistema</li>
    
    <li>
      <a href="pro_pedidos0.php" class="input-lg">
        <i class="fa fa-edit"></i> <span>Mozos</span>
      </a>
    </li>
    
    
  </ul>
</section>
<!-- /.sidebar -->
</aside>
<?php }?>



<?php
	if($_SESSION["cargo"] == "COCINERO" || $_SESSION["cargo"] == "JEFE DE SALA" || $_SESSION["cargo"] == "JEFE DE COCINA"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION["usuario"];?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Opciones del sistema</li>
    
    <li>
      <a href="pro_cocina0.php" class="input-lg">
        <i class="fa fa-th"></i> <span>Cocina</span>
      </a>
    </li>

  </ul>
</section>
<!-- /.sidebar -->
</aside>
<?php }?>





<?php
	if($_SESSION["cargo"] == "BARMAN"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION["usuario"];?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Opciones del sistema</li>
    
    <li>
      <a href="pro_bar0.php" class="input-lg">
        <i class="fa fa-book"></i> <span>Bar</span>
      </a>
    </li>

  </ul>
</section>
<!-- /.sidebar -->
</aside>
<?php }?>






<?php
	if($_SESSION["cargo"] == "CAJERO"){
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="../dist/img/user/<?php echo $_SESSION["foto"];?>.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION["usuario"];?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Opciones del sistema</li>
    
    <li>
      <a href="pro_caja0.php" class="input-lg">
        <i class="fa fa-laptop"></i> <span>Caja</span>
      </a>
    </li>

  </ul>
</section>
<!-- /.sidebar -->
</aside>
<?php }?>