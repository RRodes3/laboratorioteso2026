<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("sup-info-finalizado", $_SESSION['U_spprivilegio'])) {

  require_once('Connections/conexion.php');
  $menuactivo = 'supervisores-informes';
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
  $maxRows_lista = 8000;
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
    if ($usuario != 'todo') {
      $variableusu = "where usuario.idusuario = '" . $usuario . "' ";
    }
  }

  
$query_lista = "SELECT usuario.*, licencia.*, bien.*, biencatalogo.*, licencia.estado FROM licencia
inner join bien on licencia.id_bien = bien.idbien
INNER JOIN biencatalogo ON bien.codbiencat = biencatalogo.codbiencat
inner join usuario on licencia.idusuario = usuario.idusuario " . $variableusu . " AND licencia.estado = 'Supervision' AND bien.debaja IS NULL";

  $query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
  $lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
  $row_lista = mysqli_fetch_assoc($lista);

  if (isset($_GET['totalRows_lista'])) {
    $totalRows_lista = $_GET['totalRows_lista'];
  } else {
    $all_lista = mysqli_query($conexion, $query_lista);
    $totalRows_lista = mysqli_num_rows($all_lista);
  }
  $totalPages_lista = ceil($totalRows_lista / $maxRows_lista) - 1;

  $queryString_lista = "";
  if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
      if (
        stristr($param, "pageNum_lista") == false &&
        stristr($param, "totalRows_lista") == false
      ) {
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
      $activarmenu = 'lista-bien-debaja';
      include('aside.php');
      ?>
      <div class="content-wrapper">
        <section class="content-header hidden-print">
          <h1>
            <form action="" method="get"><i class="fa fa-file-text"></i>
              Usuario:
              <?php
              if (in_array("sup-info-finalizado", $_SESSION['U_spprivilegio'])) {
                $consulta_mysql = 'select * from usuario where tipo = "INGLABORATORISTA" order by user asc';
              } else {
                $consulta_mysql = 'SELECT usuario.*, dependencia.dependencia FROM  usuario 
                inner join oficina on usuario.idoficina = oficina.idoficina 
                inner join dependencia on oficina.iddependencia = dependencia.iddependencia =  "' . $_SESSION['U_spiddependencia'] . '"   order by  apellidos asc';
              }
              $resultado = mysqli_query($conexion, $consulta_mysql);
              ?>
              <select required name='usuario' id='usuario' style="font-size:14px;color:#333" onchange='this.form.submit()'>
                <option value=''> -- Seleccione Usuario --</option>
                <option value='todo' <?php if ("todo" == $usuario) {
                                        echo 'selected';
                                      } ?>> -- TODOS LOS USUARIOS --
                </option>
                <?php
                $nomdepen = '';
                while ($fila = mysqli_fetch_array($resultado)) { ?>
                  <option value='<?php echo $fila['idusuario'] ?>' <?php if ($fila['idusuario'] == $usuario) {
                                                                      echo 'selected';
                                                                      $nomdepen = $fila['user'];
                                                                    } ?>>
                    <?php echo $fila['nombres'] . ' ' . $fila['apellidos'] . '&nbsp;&nbsp; ( &nbsp;&nbsp;' . $fila['user'] . ' )' ?>
                  </option>
                <?php }
                ?>
              </select>
            </form>
          </h1>
        </section>
        <!--Cabecera de impresión-->
        <?php include('header_impr.php'); ?>
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <!--<div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
              </div>-->
                <div id="Exportar_a_Excel" class="box-body">
                  <!-- Titulo del reporte-->
                  <div style="text-align:center" class="visible-print-block">
                    <strong>
                      ESTATUS INFORMES ASIGNADOS </strong>
                  </div>
                  <div class="table-responsive">
                    <table id="servicios" cellspacing="0" width="100%" class="table table-bordered table-hover display">
                      <thead>
                        <tr class="titulotabla">
                          <td width="2%"><strong>N°</strong></td>
                          <td width="">ESTATUS</td>
                          <td width="8%">N. INFORME</td>
                          <td width="">ITEM</td>
                          <td width="">INGENIERO LAB</td>
                          <td width="">FECHA ASIGNADO</td>
                          <td width="">FECHA ENTREGA</td>
                          <td width="9%">AUTORIZADO</td>
                        </tr>
                      </thead>
                      <?php $result = $startRow_lista;
                      if ($totalRows_lista > 0) {
                        do { ?>
                          <tr>
                            <td><strong>
                                <?php
                                $result += 1;
                                echo  $result;
                                ?>
                              </strong></td>
                            <td><?php if ($row_lista['estado'] == 'pendiente' || $row_lista['estado'] == 'Pruebas') {
                                  echo ' <span class="label label-warning" style="font-size:14px">EN PRUEBAS</span> ';
                                } elseif ($row_lista['estado'] == "completo" || $row_lista['estado'] == 'Supervision') {
                                  echo ' <span class="label label-info" style="font-size:14px">EN SUPERVISION</span> ';
                                } else {
                                  echo ' <span class="label label-danger " style="font-size:14px">ASIGNADO REC</span> ';
                                } ?>
                            </td>
                            <td><strong><?php echo $row_lista['codbieninv']; ?></strong></td>
                            <td><?php echo $row_lista['nombien'] . ' ' . $row_lista['codmarca'] . '' . $row_lista['codmodelo']; ?></td>
                            <td><?php echo $row_lista['nombres'] . ' ' . $row_lista['apellidos']; ?></td>
                            <td><?php echo $row_lista['fechaadq']; ?></td>
                            <td><?php echo $row_lista['tiempovida']; ?></td>
                            <td style="padding:1px; margin:0px">
                              <?php $fcc = 0; ?>
                              <a href="#" data-toggle="modal" <?php if ($fcc != 0) {
                                                                echo "disabled";
                                                              } else {
                                                                echo "enabled";
                                                              } ?> data-target="#returnpruebas<?php echo $row_lista['id_licencia']; ?>" title="A pruebas" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove-sign"></span></a>

                              <a href="#" data-toggle="modal" <?php if ($fcc != 0) {
                                                                echo "disabled";
                                                              } else {
                                                                echo "enabled";
                                                              } ?> data-target="#elimbien<?php echo $row_lista['id_licencia']; ?>" title="Listo para enviar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok-sign"></span></a>                                                              
                              <!-- Codigo Qr -->
                              <a class="btn btn-primary btn-sm" href="qr.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>">
                                <span class="glyphicon glyphicon-qrcode"></span></a>
                                     <a class="btn btn-success btn-sm" href="qr-iteso.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>">
                                <span class="glyphicon glyphicon-qrcode"></span></a>


                                <form action="cambiartestado.php" method="post" name="frmimv">
                                <!-- Modal -->
                                <div class="modal fade" id="returnpruebas<?php echo $row_lista['id_licencia']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de enviar el informe a PRUEBAS DE LABORATORIO?
                                        </h4>
                                      </div>
                                      <div class="modal-body">
                                        <strong class="alert-warning">Detalles:</strong><br>
                                        <strong>No. Informe:</strong>
                                        <?php echo $row_lista['codbieninv'] . ' ' . $row_lista['nombien']; ?><br>
                                      </div>
                                      <div class="modal-footer">
                                        <?php if ($fcc != 0) {
                                          echo '<label style ="font-size:14px"  class="label label-danger">No se puede cambiar</label>';
                                        } ?>
                                        <input name="id_licencia" type="hidden" required id="id_licencia" value="<?php echo $row_lista['id_licencia']; ?>" size="32">
                                        <input name="formreturnpruebas" type="hidden" required id="formreturnpruebas" value="licencia" size="32">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
                                        <input name="Grabar" type="submit" class=" btn btn-primary" <?php if ($fcc != 0) {
                                                                                                      echo "disabled";
                                                                                                    } else {
                                                                                                      echo "enabled";
                                                                                                    } ?> title="Eliminar" value="Ok">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </form> <!-- Fin de eliminar -->
                              <!-- reporte en Eliminar -->
                              <form action="cambiartestado.php" method="post" name="frmimv">
                                <!-- Modal -->
                                <div class="modal fade" id="elimbien<?php echo $row_lista['id_licencia']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de terminar la supervision del informe?
                                        </h4>
                                      </div>
                                      <div class="modal-body">
                                        <strong class="alert-warning">Detalles:</strong><br>
                                        <strong>No. Informe:</strong>
                                        <?php echo $row_lista['codbieninv'] . ' ' . $row_lista['nombien']; ?><br>
                                      </div>
                                      <div class="modal-footer">
                                        <?php if ($fcc != 0) {
                                          echo '<label style ="font-size:14px"  class="label label-danger">No se puede cambiar</label>';
                                        } ?>
                                        <input name="id_licencia" type="hidden" required id="id_licencia" value="<?php echo $row_lista['id_licencia']; ?>" size="32">
                                        <input name="nomtablaaaa" type="hidden" required id="nomtablaaaa" value="licencia" size="32">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
                                        <input name="Grabar" type="submit" class=" btn btn-primary" <?php if ($fcc != 0) {
                                                                                                      echo "disabled";
                                                                                                    } else {
                                                                                                      echo "enabled";
                                                                                                    } ?> title="Eliminar" value="Ok">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </form> <!-- Fin de eliminar -->
                            </td>
                          </tr>
                      <?php } while ($row_lista = mysqli_fetch_assoc($lista));
                      } else {
                        echo '<tr>
                       <td colspan="6"><strong>Sin registros</strong></td></tr>';
                      } ?>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /.box -->
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

<?php } else {
  header("Location: pagina-restringida.php");
} ?>