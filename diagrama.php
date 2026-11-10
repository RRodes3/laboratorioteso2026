<?php
require_once('Connections/conexion.php');
require_once('Connections/funciones.php');
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("i_inform_asig", $_SESSION['U_spprivilegio'])) {
  require_once('Connections/conexion.php');
  $menuactivo = 'informes';
?>


  <?php
  if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }
      //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }
      return $theValue;
    }
  }
  $colname_misdatos = "-1";
  if (isset($_SESSION["U_sdni"])) {
    $colname_misdatos = $_SESSION["U_sdni"];
  }

  $query_conteo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado IS NULL  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
  $row_conteo = mysqli_fetch_assoc($conteo);
  $totalRows_conteo = mysqli_num_rows($conteo);

  $query_conteoo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado = "Pruebas"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteoo = mysqli_query($conexion, $query_conteoo) or die(mysqli_error());
  $row_conteoo = mysqli_fetch_assoc($conteoo);
  $totalRows_conteoo = mysqli_num_rows($conteoo);

  $query_conteooo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado = "Supervision"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteooo = mysqli_query($conexion, $query_conteooo) or die(mysqli_error());
  $row_conteooo = mysqli_fetch_assoc($conteooo);
  $totalRows_conteooo = mysqli_num_rows($conteooo);

  $query_conteoooo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado = "Supervisado"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteoooo = mysqli_query($conexion, $query_conteoooo) or die(mysqli_error());
  $row_conteoooo = mysqli_fetch_assoc($conteoooo);
  $totalRows_conteoooo = mysqli_num_rows($conteoooo);

  $query_conteooooo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
  INNER JOIN `usuario` u ON l.idusuario = u.idusuario
  WHERE l.estado = "enviado"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteooooo = mysqli_query($conexion, $query_conteooooo) or die(mysqli_error());
  $row_conteooooo = mysqli_fetch_assoc($conteooooo);
  $totalRows_conteooooo = mysqli_num_rows($conteooooo);
  ?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema Teso</title>
    <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Eduardo Sandoval">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <?php include('header.php'); ?>
      </header>
      <?php
      $menuactivo = 'inicio';
      include('aside.php');
      ?>

      <!-- ---------------------------------------------- -->
      <div class="content-wrapper">
        <section class="content-header">
          <!-- <h1>
        Tablero
        <small>Panel de Control</small>
      </h1> -->
          <!-- <ol class="breadcrumb">     
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>      
        <li class="active">Tablero</li>   
      </ol> -->

        </section>
        <section class="content">
          <div class="row">
            <!-- ./col -->
            <div class="col-lg-3 col-xs-3">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $row_conteo['idusuario']; ?><sup style="font-size: 20px"></sup></h3>

                  <h4>Informes Asignados recientemente</h4>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="informes-asignados.php" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-3">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $row_conteoo['idusuario']; ?></h3>

                  <h4>Informes pendientes</h4>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="informes-asignados.php" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- ./col -->
            <div class="col-lg-3 col-xs-3">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?php echo $row_conteooo['idusuario']; ?></h3>
                  <h4>Informes Supervisión</h4>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="informes-asignados.php" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-3 col-xs-3">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="box-triangulos">
                  <div class="inner-one">
                    <h3><?php echo $row_conteoooo['idusuario']; ?></h3>
                    <h4>Informes terminados/sin enviar</h4>
                  </div>
                  <div class="inner-two">
                    <h3><?php echo $row_conteooooo['idusuario']; ?></h3>
                    <h4>Informes enviados</h4>
                  </div>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="informes-asignados.php" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>

              </div>
            </div>
            <!-- ./col -->
          </div>
          <div class="row">
            <div class="col-lg-12">
              <img src="img/intro-bng.png" alt="" class="img-fluid" style="width: 100%">
            </div>
          </div>
        </section>
      </div>

      <!-- ------------------------------------------------------ -->
      
      <?php include('footer.html'); ?>
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- page script -->

  </body>

  </html>
<?php } else {
  header("Location: pagina-restringida.php");
} ?>