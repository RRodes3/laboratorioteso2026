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
WHERE l.estado = "pendiente" OR l.estado = "pruebas"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteoo = mysqli_query($conexion, $query_conteoo) or die(mysqli_error());

  $row_conteoo = mysqli_fetch_assoc($conteoo);
  $totalRows_conteoo = mysqli_num_rows($conteoo);


  $query_conteooo = sprintf('SELECT COUNT(*) idusuario FROM `licencia` l 
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
WHERE l.estado = "completo" OR l.estado = "Supervision"  AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));
  $conteooo = mysqli_query($conexion, $query_conteooo) or die(mysqli_error());

  $row_conteooo = mysqli_fetch_assoc($conteooo);
  $totalRows_conteooo = mysqli_num_rows($conteooo);

  //Lista 1
  $query_lista = sprintf('SELECT l.*,b.*, m.clasificacion, c.nombien, u.nombres, u.apellidos, f.codfamiliabien, f.familiabien FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
inner join `familiabien` f ON b.idfamiliabien = f.idfamiliabien
LEFT JOIN `movimiento` m ON b.idbien = m.idbien
WHERE l.estado IS NULL AND b.debaja IS NULL AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));

  $lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
  $row_lista = mysqli_fetch_assoc($lista);
  $totalRows_lista = mysqli_num_rows($lista);


  $query_listaa = sprintf('SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE (l.estado= "pendiente" OR l.estado="Pruebas") AND b.debaja IS NULL AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));

  $listaa = mysqli_query($conexion, $query_listaa) or die(mysqli_error());
  $row_listaa = mysqli_fetch_assoc($listaa);
  $totalRows_listaa = mysqli_num_rows($listaa);


  $query_listaaa = sprintf('SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE (l.estado= "completo" OR l.estado= "Supervision") AND b.debaja IS NULL AND `user` = %s', GetSQLValueString($colname_misdatos, "text"));

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
      $activarmenu = 'rvehiculo';
      include('aside.php');
      ?>
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <section class="content-header">


              <br>
            </section>


          </div>
          <!-- /.row -->
          <div class="row">
            <div class="col-md-12">
              <!-- USERS LIST -->
              <div class="box box-danger box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Asignados Recientemente</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td>N.</td>
                        <td width="12%">N. Informe</td>
                        <td width="30%">Item</td>
                        <td width="20%">Consta / Incluye</td>
                        <td width="10%">Pais de Origen</td>
                        <td width="20%">Fecha de <br>entrega</td>
                        <td width="20%">Observaciones</td>
                        <td width="2%">Anaquel</td>
                        <td width="10%">-</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0) {
                      $i = 0;
                      do {
                        $i += 1; ?>
                        <tr <?php if ($i % 2) {
                              echo 'filapar';
                            } else {
                              echo 'filaimpar';
                            } ?>>
                          <th class="active"><?php echo $i; ?></th>
                          <td><?php echo $row_lista['codbieninv'] . ' ' . $row_lista['segui']; ?></td>
                          <td><?php echo $row_lista['nombien']; ?></td>
                          <td><?php echo $row_lista['caracteristica']; ?></td>
                          <td><?php echo $row_lista['paisorigen']; ?></td>
                          <?php
                          $fecha_actual = date("Y-m-d");
                          $fecha_entrega = $row_lista['tiempovida'];

                          if (empty($fecha_entrega) || $fecha_entrega == '0000-00-00') {
                            // Fecha de entrega vacía
                            echo '<td><span class="label" style="background-color:gray; color:white; font-size:14px">Solicita la fecha de entrega con atención a clientes</span></td>';
                          } else {
                            $date1 = new DateTime($fecha_actual);
                            $date2 = new DateTime($fecha_entrega);
                            $diff = $date1->diff($date2);

                            $dias = $diff->days;
                            $invertida = $diff->invert; // 0 = futura, 1 = pasada

                            if ($invertida == 1) {
                              // Fecha de entrega ya pasó
                              echo '<td><span class="label label-danger" style="font-size:14px">Entrega vencida - ' . $fecha_entrega . ' (' . $dias . ' días de retraso)</span></td>';
                            } elseif ($dias >= 7) {
                              // Más de 8 días restantes
                              echo '<td><span class="label label-success" style="font-size:14px">Aún tienes tiempo - ' . $fecha_entrega . ' (' . $dias . ' días restantes)</span></td>';
                            } elseif ($dias >= 3 && $dias <= 6) {
                              // Entre 5 y 3 días restantes
                              echo '<td><span class="label" style="background-color:yellow; color:black; font-size:14px">Se aproxima la fecha de entrega - ' . $fecha_entrega . ' (' . $dias . ' días restantes)</span></td>';
                            } elseif ($dias <= 2 && $dias >= 0) {
                              // 2 días o el mismo día
                              echo '<td><span class="label label-warning" style="background-color:orange; color:white; font-size:14px">Últimos días - ' . $fecha_entrega . ' (' . $dias . ' días restantes)</span></td>';
                            } 
                          }
                          ?>
                          <td><?php echo $row_lista['observacionesasig']; ?></td>
                          <td><?php echo $row_lista['lugar']; ?></td>
                          <td style="padding:1px; margin:0px">
                            <?php
                            $fc = 0;
                            ?>

                            <a href="#" data-toggle="modal"
                              <?php if ($fc != 0) {
                                echo "disabled";
                              } elseif ($row_lista['suspendido'] == '1') {
                                echo ' <span class="badge badge-secondary" style="font-size:14px">SUSPENDIDO</span> ';
                              } else {
                              ?>
                              data-target="#elimbien<?php echo $row_lista['id_licencia']; ?>" title="Cambiar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                          <?php
                              } ?>

                          <a class="btn btn-primary btn-sm" href="extensiones/tcpdf/pdf/form-almacen.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_listaa['nombien']; ?>">
                            <span class="glyphicon glyphicon-print"></span></a>
                          <!-- caratula -->
                          <a class="btn btn-primary btn-sm" href="extensiones/tcpdf/pdf/caratula.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_listaa['nombien']; ?>">
                            <span class="glyphicon glyphicon-book"></span></a>
                          <!-- reporte en Eliminar -->
                          <form action="cambiartestado.php" method="post" name="frmimv">
                            <!-- Modal -->
                            <div class="modal fade" id="elimbien<?php echo $row_lista['id_licencia']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de cambiar el estado del registro a pruebas?
                                    </h4>
                                  </div>
                                  <div class="modal-body">
                                    <strong class="alert-warning">Detalles:</strong><br>
                                    <strong>No. Informe:</strong>
                                    <?php echo $row_lista['codbieninv'] . ' ' . $row_lista['nombien']; ?><br>
                                    <strong>Fecha de Recepción del Item:</strong>
                                    <?php echo $row_lista['fechaadq'] ?><br>
                                    <strong>Gestor:</strong>
                                    <?php echo $row_lista['codcolor'] . ' /// ' . $row_lista['observaciones'] ?><br>
                                    <strong>Norma:</strong>
                                    <strong style="color: red;"><?php echo $row_lista['codfamiliabien'] . ' // ' . $row_lista['familiabien'] ?><br></strong>
                                    <strong>Tensión:</strong>
                                    <strong style="color: orange;">
                                      <?php if ($row_lista['clasificacion'] == "TENSIONORMAL") {
                                        echo "TENSION NORMAL";
                                      } elseif ($row_lista['clasificacion'] == "") {
                                        echo "FALTO AGREGAR LA TENSIÓN";
                                      } else {
                                        echo  $row_lista['clasificacion'];
                                      } ?>
                                    </strong>
                                  </div>
                                  <div class="modal-footer">
                                    <?php if ($fc != 0) {
                                      echo '<label style ="font-size:14px"  class="label label-danger">No se puede cambiar</label>';
                                    } ?>
                                    <input name="id_licencia" type="hidden" required id="id_licencia" value="<?php echo $row_lista['id_licencia']; ?>" size="32">
                                    <input name="nomtabla" type="hidden" required id="nomtabla" value="licencia" size="32">
                                    <div style="text-align: left;">

                                      <strong>Verifica si la tensión y Norma asignada es correcta, en caso de ser incorrecta favor de modificarla.</strong><br>
                                    </div>
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button> -->
                                    <a class="btn btn-danger btn-sm" href="correccionNorma.php?id=<?php echo $row_lista['idbien']; ?>">
                                      Corregir Norma</a>
                                    <a class="btn btn-warning btn-sm" href="clasificacionItem.php?id=<?php echo $row_lista['idbien']; ?>">
                                      Corregir tensión</a>
                                    <input name="Grabar" type="submit" class=" btn btn-success" <?php if ($fc != 0) {
                                                                                                  echo "disabled";
                                                                                                } else {
                                                                                                  echo "enabled";
                                                                                                } ?> title="Eliminar" value="Siguiente">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form> <!-- Fin de eliminar -->
                          </td>
                        </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));
                    } ?>
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->

          </div>
          <!-- <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-thumbs-o-up"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">En supervisión</span>
                  <span class="info-box-number"><?php echo $row_conteooo['idusuario']; ?></span>
                </div>
                
              </div>
              
            </div>
            
          </div> -->
          <div class="row">
            <div class="col-md-6">
              <!-- USERS LIST -->
              <div class="box box-warning box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">En pruebas</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <table id="servicioss" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td width="5%">N.</td>
                        <td width="20%">N. TESO</td>
                        <td width="40%">ITEM</td>
                        <td width="5%">UBICACION/ANAQUEL</td>
                        <td width="25%">-</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_listaa > 0) {
                      $i = 0;
                      do {
                        $i += 1; ?>
                        <tr <?php if ($i % 2) {
                              echo 'filapar';
                            } else {
                              echo 'filaimpar';
                            } ?>>
                          <th class="active"><?php echo $i; ?></th>
                          <td><?php echo $row_listaa['codbieninv']; ?></td>
                          <td><?php echo $row_listaa['nombien']; ?></td>
                          <td><?php echo $row_listaa['lugar']; ?></td>
                          <td style="padding:1px; margin:0px">
                            <?php
                            $fcc = 0;
                            ?>
                            <a href="#" data-toggle="modal" <?php if ($fcc != 0) {
                                                              echo "disabled";
                                                            } elseif ($row_listaa['suspendido'] == '1') {
                                                              echo ' <span class="badge badge-secondary" style="font-size:14px">SUSPENDIDO</span> ';
                                                            } else {
                                                            ?>
                              data-target="#elimbien<?php echo $row_listaa['id_licencia']; ?>" title="Cambiar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>

                          <?php
                                                            } ?>
                          <a class="btn btn-primary btn-sm" href="extensiones/tcpdf/pdf/form-almacen.php?id=<?php echo $row_listaa['idbien']; ?>&bien=<?php echo $row_listaa['nombien']; ?>">
                            <span class="glyphicon glyphicon-print"></span></a>
                          <!-- caratula -->
                          <a class="btn btn-primary btn-sm" href="extensiones/tcpdf/pdf/caratula.php?id=<?php echo $row_listaa['idbien']; ?>&bien=<?php echo $row_listaa['nombien']; ?>">
                            <span class="glyphicon glyphicon-book"></span></a>
                          <!-- reporte en Eliminar -->
                          <form action="cambiartestado.php" method="post" name="frmimv">
                            <!-- Modal -->
                            <div class="modal fade" id="elimbien<?php echo $row_listaa['id_licencia']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de enviar el teso a supervision?
                                    </h4>
                                  </div>
                                  <div class="modal-body">
                                    <strong class="alert-warning">Detalles:</strong><br>
                                    <strong>No. Informe:</strong>
                                    <?php echo $row_listaa['codbieninv'] . ' ' . $row_listaa['nombien']; ?><br>
                                  </div>
                                  <div class="modal-footer">
                                    <?php if ($fcc != 0) {
                                      echo '<label style ="font-size:14px"  class="label label-danger">No se puede cambiar</label>';
                                    } ?>
                                    <input name="id_licencia" type="hidden" required id="id_licencia" value="<?php echo $row_listaa['id_licencia']; ?>" size="32">
                                    <input name="nomtablaa" type="hidden" required id="nomtablaa" value="licencia" size="32">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
                                    <input name="Grabar" type="submit" class=" btn btn-primary" <?php if ($fcc != 0) {
                                                                                                  echo "disabled";
                                                                                                } else {
                                                                                                  echo "enabled";
                                                                                                } ?> title="Eliminar" value="Si, Cambiar">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form> <!-- Fin de eliminar -->
                          </td>
                        </tr>
                    <?php } while ($row_listaa = mysqli_fetch_assoc($listaa));
                    } ?>
                  </table>


                </div>
                <!-- /.box-body -->

              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <!-- USERS LIST -->
              <div class="box box-info box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">En supervisión</h3>

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
                        <td width="60%">ITEM</td>
                        <td width="15%">-</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_listaaa > 0) {
                      $i = 0;
                      do {
                        $i += 1; ?>
                        <tr <?php if ($i % 2) {
                              echo 'filapar';
                            } else {
                              echo 'filaimpar';
                            } ?>>
                          <th class="active"><?php echo $i; ?></th>
                          <td><?php echo $row_listaaa['codbieninv']; ?></td>
                          <td><?php echo $row_listaaa['nombien']; ?></td>
                          <td style="padding:1px; margin:0px">

                            <a class="btn btn-primary btn-sm" href="extensiones/tcpdf/pdf/form-almacen.php?id=<?php echo $row_listaaa['idbien']; ?>&bien=<?php echo $row_listaaa['nombien']; ?>">
                              <span class="glyphicon glyphicon-print"></span></a>
                            <a href="#" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-bookmark"></span></a>
                            <a class="btn btn-danger btn-sm" href="qr.php?id=<?php echo $row_listaaa['idbien']; ?>&bien=<?php echo $row_listaaa['nombien']; ?>">
                              <span class="glyphicon glyphicon-qrcode"></span></a>

                            <a class="btn btn-success btn-sm" href="qr-iteso.php?id=<?php echo $row_listaaa['idbien']; ?>&bien=<?php echo $row_listaaa['nombien']; ?>">
                              <span class="glyphicon glyphicon-qrcode"></span></a>
                          </td>
                        </tr>
                    <?php } while ($row_listaaa = mysqli_fetch_assoc($listaaa));
                    } ?>
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
<?php } else {
  header("Location: pagina-restringida.php");
} ?>