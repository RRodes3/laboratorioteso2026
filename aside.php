<head>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
</head>
<?php if (!isset($_SESSION)) {
  session_start();
}
if (!isset($menuactivo)) {
  $menuactivo = '';
}
$actinicio = '';
$actmovimiento = '';
$actmantenimiento = '';
$actsupervision = '';
$actgestor = '';
$actalmacen = '';
$actreportes = '';
$actusuario = '';
$actmisdatos = '';
$actdocs = '';

if ($menuactivo == 'inicio') {
  $actinicio = 'active';
} else if ($menuactivo == 'movimiento') {
  $actmovimiento = 'active';
} else if ($menuactivo == 'mantenimiento') {
  $actmantenimiento = 'active';
} else if ($menuactivo == 'supervisores-informes') {
  $actsuperinfor = 'active';
} else if ($menuactivo == 'supervision') {
  $actsupervision = 'active';
} else if ($menuactivo == 'gestoria') {
  $actgestor = 'active';
} else if ($menuactivo == 'informes') {
  $actmisinformes = 'active';
} else if ($menuactivo == 'facturacion') {
  $actfacturacion = 'active';
} else if ($menuactivo == 'almacen') {
  $actalmacen = 'active';
} else if ($menuactivo == 'reportes') {
  $actreportes = 'active';
} else if ($menuactivo == 'usuario') {
  $actusuario = 'active';
} else if ($menuactivo == 'misdatos') {
  $actmisdatos = 'active';
}else if ($menuactivo == 'misdocs') {
  $actdocs = 'active';
}
?>
<aside class="main-sidebar " class="print-hidden">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="img/LOGO.jpg" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'] ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <ul class="sidebar-menu">
      <li class="header">MENU DE NAVEGACION</li>
      <li class="<?php echo $actinicio;
                  if ($activarmenu == 'diagrama') {
                    echo 'active';
                  } ?> treeview">
        <a href="diagrama.php">
          <i class="glyphicon glyphicon-home"></i> <span>Inicio</span>
        </a>
      </li>
      <?php
      if (in_array("i_inform", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actsupervision; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i> <span>INFORMES </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("i_inform_asig", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="informes-asignados.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Informes asig.</a></li>

            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>
      <?php
      if (in_array("super-informes", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actsuperinfor; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i> <span>Supervision</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("sup-info-finalizado", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="estatus-informes-supervision.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>Informes</a></li>
              <li><a href="clave-qr.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>Clave QR ener-jul</a></li>
              <li><a href="clave-qr2.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>Clave QR ago-dic</a></li>
              <li><a href="estatus-informe.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Estatus informes</a></li>
            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>


      <li class="<?php echo $actdocs; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-th-list" aria-hidden="true"></i> <span> Calidad</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span></a>
        <ul class="treeview-menu">
          <li><a href="./docs/MCLAM-01 MANUAL DE CALIDAD rev-19 2024-02-16.pdf" target="_blank" class="active"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Manual de calidad</a></li>         
        </ul>
      </li>


      <li class="<?php echo $actmisdatos; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-user" aria-hidden="true"></i> <span> Mis datos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span></a>
        <ul class="treeview-menu">
          <li><a href="mis-datos.php" class="active"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Mis Datos</a></li>
          <li><a href="cambiar-clave.php"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Cambiar
              Clave</a></li>
          <li><a href="cerrar-sesion.php"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Cerrar
              Sesión</a></li>
        </ul>
      </li>
    </ul>
  </section>
</aside>