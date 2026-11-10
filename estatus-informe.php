<?php 
if (!isset($_SESSION)) {
  session_start();
} 
  if (isset($_SESSION['U_spprivilegio']) and in_array("sup-info-finalizado", $_SESSION['U_spprivilegio'])) {
	 
require_once('Connections/conexion.php');
$menuactivo ='supervision';
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
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_lista = 20000;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;
$colname_lista = "-1";
$usuario = 0; 
$variableusu = ""; 
if (isset($_GET['usuario'])) {
  $usuario = $_GET['usuario'];
  if ($usuario !='todo'){
	  $variableusu = "where usuario.idusuario = '". $usuario."'";
  }
}
//$query_lista = "SELECT usuario.*, auditoria.*, dependencia.dependencia FROM auditoria inner join usuario on auditoria.idusuario = usuario.idusuario inner join oficina on usuario.idoficina = oficina.idoficina inner join dependencia on oficina.iddependencia = dependencia.iddependencia   ".$variableusu."    order by  fecharegistro desc, apellidos , nombres asc";
$query_lista = "SELECT usuario.*, licencia.*, bien.*, biencatalogo.*, licencia.estado, familiabien.codfamiliabien FROM licencia
inner join bien on licencia.id_bien = bien.idbien
INNER JOIN biencatalogo ON bien.codbiencat = biencatalogo.codbiencat
INNER JOIN familiabien ON bien.idfamiliabien = familiabien.idfamiliabien
inner join usuario on licencia.idusuario = usuario.idusuario ".$variableusu." ORDER BY licencia.estado = 'PENDIENTE' ";
$query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
$lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);

if (isset($_GET['totalRows_lista'])) {
  $totalRows_lista = $_GET['totalRows_lista'];
} else {
  $all_lista = mysqli_query($conexion, $query_lista);
  $totalRows_lista = mysqli_num_rows($all_lista);
}
$totalPages_lista = ceil($totalRows_lista/$maxRows_lista)-1;

$queryString_lista = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_lista") == false && 
        stristr($param, "totalRows_lista") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_lista = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_lista = sprintf("&totalRows_lista=%d%s", $totalRows_lista, $queryString_lista);
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
  $activarmenu='lista-bien-debaja';
  include('aside.php');
  ?>
    <div class="content-wrapper">
      <section class="content-header hidden-print">
        <h1>

          <form action="" method="get"><i class="fa fa-file-text"></i>
            Usuario:
            <?php
              if (in_array("sup-info-finalizado", $_SESSION['U_spprivilegio'])) {										 
                          $consulta_mysql='select * from usuario where tipo = "INGLABORATORISTA" order by user asc';
              }else{
                $consulta_mysql='SELECT usuario.*, dependencia.dependencia FROM  usuario 
                inner join oficina on usuario.idoficina = oficina.idoficina 
                inner join dependencia on oficina.iddependencia = dependencia.iddependencia =  "'.$_SESSION['U_spiddependencia'].'"   order by  apellidos asc';
              }
              $resultado=mysqli_query($conexion,$consulta_mysql);
            ?>
            <select required name='usuario' id='usuario' style="font-size:14px;color:#333"
              onchange='this.form.submit()'>
              <option value=''> -- Seleccione Usuario --</option>
              <option value='todo' <?php if ("todo"==$usuario){ echo 'selected';  } ?>> -- TODOS LOS USUARIOS --
              </option>
              <?php
              $nomdepen = '';
              while($fila=mysqli_fetch_array($resultado)){ ?>
              <option value='<?php echo $fila['idusuario'] ?>'
                <?php if ($fila['idusuario']==$usuario){ echo 'selected'; $nomdepen = $fila['user'];} ?>>
                <?php echo $fila['nombres'].' '.$fila['apellidos'].'&nbsp;&nbsp; ( &nbsp;&nbsp;'.$fila['user'].' )'?>
              </option>
              <?php }
              ?>
            </select>
          </form>
        </h1>
        <ol class="breadcrumb">
          <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm "
            role="button" title="Imprimir reporte" stsyle="font-size:10px"><span
              class="glyphicon glyphicon-print"></span> Imprimir</a>         
        </ol>
      </section>      
      <?php include('header_impr.php'); ?>
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div id="Exportar_a_Excel" class="box-body">                
                <div style="text-align:center" class="visible-print-block">
                  <strong> 
                  ESTATUS INFORMES ASIGNADOS </strong> 
                </div>
                <div class="table-responsive">
                  <table id="servicios" cellspacing="0" width="100%" class="table table-bordered table-hover display">
                    <thead>
                      <tr class="titulotabla">
                        <td width="2%"><strong>N°</strong></td>
                        <td width="8%">N. INFORME</td>
                        <td width="25%">ITEM</td>
                        <td width="10%">MARCA</td>
                        <td width="10%">MODELO</td>
                        <td width="10%">NORMA</td>
                        <td width="15%">INGENIERO LAB</td>  
                        <td>FECHA DE ASIGNACION</td>                                                                                     
                        <td width="10%">FECHA INGRESO ITEM</td> 
                        <td width="10%">FECHA DE ENTREGA PROPUESTA</td>      
                        <td>ESTATUS</td>
                      </tr>
                    </thead>
                    <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
                    <tr>
                      <td><strong>
                          <?php
                          $result += 1;
                          echo  $result;
                          ?>
                        </strong></td>
                        <td><?php echo $row_lista['codbieninv']; ?></td>
                        <td><?php echo $row_lista['nombien']; ?></td>
                        <td><?php echo $row_lista['codmarca']; ?></td>
                        <td><?php echo $row_lista['codmodelo']; ?></td>
                        <td><?php echo $row_lista['codfamiliabien']; ?></td>
                        <td><?php echo $row_lista['nombres'].' '.$row_lista['apellidos']; ?></td> 
                        <td><?php echo $row_lista['fech_asignacion'];?></td>
                        <td><?php echo $row_lista['fechaadq'];?></td>
                        <td><?php echo $row_lista['tiempovida'];?></td>   

                        <td><?php if( $row_lista['estado'] == 'pendiente' ||  $row_lista['estado'] == 'Pruebas'  ){
                          echo ' <span class="label label-warning  " style="font-size:14px">EN PRUEBAS</span> ';
                        }elseif($row_lista['estado'] == "completo" ||  $row_lista['estado'] == 'Supervision'){
                          echo ' <span class="label label-info " style="font-size:14px">EN SUPERVISION</span> ';
                          }
                        elseif($row_lista['estado'] == "finalizado" ||  $row_lista['estado'] == 'Supervisado' ){
                          echo ' <span class="label " style="font-size:14px; background: rgb(24,110,205);
                          background: linear-gradient(90deg, rgba(24,110,205,1) 0%, rgba(162,6,38,1) 23%, rgba(221,140,25,1) 61%, rgba(0,94,191,1) 100%);  !important; " >PEND. POR ENVIAR</span> ';
                          }
                          elseif($row_lista['estado'] == "enviado"){
                            echo ' <span class="label label-success" style="font-size:14px">ENVIADO</span> ';
                            }
                          else{
                            echo ' <span class="label label-danger " style="font-size:14px">ASIGNADO REC</span> ';
                          } ?>
                        </td>  

                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));}else{ echo '<tr>
                       <td colspan="6"><strong>Sin registros</strong></td></tr>';} ?>
                  </table>
                </div>
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