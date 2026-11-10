<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("i_inform_asig", $_SESSION['U_spprivilegio'])) { 	 
require_once('Connections/conexion.php');
$menuactivo ='informes';
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
WHERE l.estado = "pendiente"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
$conteoo = mysqli_query($conexion, $query_conteoo) or die(mysqli_error());

$row_conteoo = mysqli_fetch_assoc($conteoo);
$totalRows_conteoo = mysqli_num_rows($conteoo);


$query_conteooo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado = "completo"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
$conteooo = mysqli_query($conexion, $query_conteooo) or die(mysqli_error());

$row_conteooo = mysqli_fetch_assoc($conteooo);
$totalRows_conteooo = mysqli_num_rows($conteooo);

//Lista 1
$query_lista = sprintf('SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE l.estado IS NULL AND b.debaja IS NULL `user` = %s', GetSQLValueString($colname_misdatos, "text"));

$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);
$totalRows_lista = mysqli_num_rows($lista);


$query_listaa = sprintf('SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE l.estado= "pendiente" AND b.debaja IS NULL `user` = %s', GetSQLValueString($colname_misdatos, "text"));

$listaa = mysqli_query($conexion, $query_listaa) or die(mysqli_error());
$row_listaa = mysqli_fetch_assoc($listaa);
$totalRows_listaa = mysqli_num_rows($listaa);


$query_listaaa = sprintf('SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE l.estado= "completo" AND b.debaja IS NULL `user` = %s', GetSQLValueString($colname_misdatos, "text"));

$listaaa = mysqli_query($conexion, $query_listaaa) or die(mysqli_error());
$row_listaaa = mysqli_fetch_assoc($listaaa);
$totalRows_listaaa = mysqli_num_rows($listaaa);



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<header class="main-header">
  <?php include('header.php'); ?>
</header>
<?php 
$activarmenu='rvehiculo';
include('aside.php');
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
    <div class="row">
    <section class="content-header">
      <!-- <h1>
        Ingenieros:
        Apartir del día de hoy, tendras disponible el formato de solicitud de muestras al area de almacen, por lo que es necesario llevar para firma de recibido y entregado,
         el formato es para cada muestra que se les sea asignado.<br><br>
        * El formato podras descargarlo dando click en el icono de impresora en el apartado de informes asignados recientemente.
      </h1> -->
      <br>
    </section>        
        <div class="col-md-4 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-thumbs-o-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Terminados</span>
              <span class="info-box-number"><?php echo $row_conteooo['idusuario']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div class="row">    
          

            <div class="col-md-8">
              <!-- USERS LIST -->
              <div class="box box-success box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Terminados</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table id="serviciosss" width="100%" class="table table-hover display">
                      <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                          <td width="5%">N.</td>
                          <td width="20%">N. TESO</td>
                          <td width="70%">ITEM</td>                          
                          <td width="5%">-</td>
                        </tr>
                      </thead>
                      <?php if ($totalRows_listaaa > 0) {$i = 0 ; do {  $i += 1; ?>
                      <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                        <th class="active"><?php echo $i; ?></th>
                        <td><?php echo $row_listaaa['codbieninv']; ?></td>
                        <td><?php echo $row_listaaa['nombien']; ?></td>                        
                        <td style="padding:1px; margin:0px">
                        <a href="" title="Editar"
                            stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-success btn-sm"><span
                              class="glyphicon glyphicon-bookmark"></span></a>

                        </td>
                      </tr>
                      <?php } while ($row_listaaa = mysqli_fetch_assoc($listaaa));} ?>
                    </table>
                </div>
              </div>
            </div>
           
          </div>
    </section>

</div>
<?php include('footer.html'); ?>
</div>
  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
  $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicios').DataTable();
  });
  </script>
    <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicioss').DataTable();
  });
  </script>
    <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#serviciosss').DataTable();
  });
  </script>

  <script language="javascript">
  $(document).ready(function() {
    $("#botonExcel").click(function(event) {
      $("#datos_a_enviar").val($("<div>").append($("#Exportar_a_Excel").eq(0).clone()).html());
      $("#FormularioExportacion").submit();
    });
  });
  </script>
</body>
</html>
<?php }else{
header("Location: pagina-restringida.php");
}?>