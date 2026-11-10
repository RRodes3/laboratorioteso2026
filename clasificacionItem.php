<?php require_once('Connections/conexion.php');
$menuactivo = 'movimiento';
if (!isset($_SESSION)) {
    session_start();
} ?>
<?php
$idbien = '';
$metodo = '';
if (isset($_GET['metodo'])) {
    $metodo = $_GET['metodo'];
}

if (isset($_GET['id'])) {
    $idbien = $_GET['id'];
}

$query_lista = "SELECT bien.*,biencatalogo.nombien, proveedor.codproveedor, 
proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien, movimiento.clasificacion FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
inner join proveedor on bien.idproveedor = proveedor.idproveedor 
LEFT join movimiento on bien.idbien = movimiento.idbien
inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien'";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);

$query_listamov = "SELECT * FROM movimiento where idbien = '" . $row_lista['idbien'] . "' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$row_listamov = mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema Teso</title>
    <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
    <meta name="author" content="Eduardo Sandoval">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <script src="includes/carganormal.js"></script>
    <style>

        .content .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box-body .form-group .col-sm-12 .control-label {
            text-align: left;
        }

    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <?php include('header.php'); ?>
        </header>
        <?php
        $activarmenu = 'modificar-bien';
        include('aside.php');
        ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <span class="glyphicon glyphicon-edit"></span> Modificar la clasificación del Item
                </h1>
            </section>
            <section class="content">
                <div class="row" ">
                    <div class=" col-md-4">
                    <div class="box box-info">
                        <form class="form-horizontal" method="post" enctype='multipart/form-data' name="form1" action="proce_moditem.php">
                            <div class="box-header"></div>
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="id" class="col-sm-4 control-label">No. Informe:</label>
                                        <label for="" class="col-sm-8 control-label"><?php echo $row_lista['codbieninv']; ?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="id" class="col-sm-4 control-label">Nombre Item:</label>
                                        <label for="" class="col-sm-8 control-label"><?php echo $row_lista['nombien']; ?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="id" class="col-sm-4 control-label">Norma:</label>
                                        <label for="" class="col-sm-8 control-label"><?php echo $row_lista['codfamiliabien']; ?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="id" class="col-sm-4 control-label">Tensión Inicial:</label>
                                        <label for="" class="col-sm-8 control-label">
                                        <?php if ( $row_lista['clasificacion'] == "TENSIONORMAL") {
                                                              echo "TENSION NORMAL";
                                                            } elseif( $row_lista['clasificacion'] == "" ) {
                                                              echo "FALTO AGREGAR LA TENSIÓN";
                                                            } else {
                                                              echo  $row_lista['clasificacion'];
                                                            } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id" class="col-sm-4 control-label">Selecciona la tensión correcta:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="clasificacion" id="clasificacion">
                                            <OPTION selected value="TENSION NORMAL">TENSION NORMAL</OPTION>
                                            <OPTION value="EXTRABAJA">EXTRABAJA</OPTION>
                                            <OPTION value="KIT">KIT </OPTION>
                                            <OPTION value="<2K">< 2K</OPTION>
                                            <OPTION value=">2K">> 2K</OPTION>
                                            <OPTION value="TRIFASICO">TRIFÁSICO</OPTION>
                                            <OPTION value="METALMECANICA">METALMECANICA</OPTION>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input name="idbien" type="hidden" required id="idbien" value="<?php echo $row_lista['idbien']; ?>" size="32">
                            <input type="hidden" name="MM_update" value="form1">
                            <div class="box-footer">
                                <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                                <button type="submit" class="btn btn-info pull-right" title="Grabar registro">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        </section>
    </div>
    <?php include('footer.html'); ?>
    </div>
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script>
        $(function() {
            $(".select2").select2();
        });
    </script>
</body>

</html>