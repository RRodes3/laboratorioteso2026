<?php if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['U_spidus'])and $_SESSION['U_spidus']=='')
{
    echo '<script language=javascript>
    alert("Por Favor Inicie Sesion")
    self.location="login.php"
    </script>';
}
?>
<header class="main-header hidden-print">
  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">Teso</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Laboratorio </b>Teso</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Navegación</span>
    </a>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/icono.png" class="user-image" height="160" width="160" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['U_spnombres'].' '.$_SESSION['U_spapellidos'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="img/logo.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['U_spnombres'].' '.$_SESSION['U_spapellidos']; ?> <br />
                  <?php echo $_SESSION['U_sptipo']; ?>
                  <small>Miembro desde : <?php echo $_SESSION['U_spfregistro']; ?> </small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="mis-datos.php" class="btn btn-default btn-flat">Mis datos</a>
                </div>
                <div class="pull-right">
                  <a href="cerrar-sesion.php" class="btn btn-default btn-flat">Cerrar Sesión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
  </nav>
</header>