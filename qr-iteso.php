<?php
require_once('Connections/conexion.php');

// Mejor gestión de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación y saneamiento de parámetros
$idbien = isset($_GET['id']) ? intval($_GET['id']) : 0;
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Consulta SQL segura con prepared statements
$query_lista = "SELECT bien.*, biencatalogo.nombien, proveedor.codproveedor, proveedor.razonsocial, 
    familiabien.codfamiliabien, familiabien.familiabien 
    FROM bien 
    INNER JOIN biencatalogo ON bien.codbiencat = biencatalogo.codbiencat
    INNER JOIN proveedor ON bien.idproveedor = proveedor.idproveedor
    INNER JOIN familiabien ON bien.idfamiliabien = familiabien.idfamiliabien
    WHERE bien.idbien = ?
    ORDER BY biencatalogo.nombien ASC";

$stmt = $conexion->prepare($query_lista);
$stmt->bind_param("i", $idbien);
$stmt->execute();
$result = $stmt->get_result();
$roowfila = $result->fetch_assoc();
$totalRows_lista = $result->num_rows;
$stmt->close();

// Consulta de movimiento también segura
$query_listamov = "SELECT * FROM movimiento WHERE idbien = ? ORDER BY idmov DESC LIMIT 1";
$stmt2 = $conexion->prepare($query_listamov);
$stmt2->bind_param("i", $roowfila['idbien']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$roowfilamov = $result2->fetch_assoc();
$totalRows_listamov = $result2->num_rows;
$stmt2->close();
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
    <style type="text/css" media="print">
        @page {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        $activarmenu = 'imprimir-qr';
        // include('aside.php');
        ?>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg border-0 rounded-4">

                    <!-- Encabezado del modal -->
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title" id="myModalLabel">
                            <i class="bi bi-qr-code-scan me-2"></i>Imprimir Código QR
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="modal-body">



                        <p class="mb-2 fs-5">🔔<strong>¡Atención!</strong></p>

                        <p>Este <strong>código QR</strong> solo podrá utilizarse para los informes con la nonmenclatura <strong>TESO250701--</strong> y posteriores
                         ya que se implementará un nuevo sistema de validación de informes. en donde quien supervise el informe ya no sera nesesario validarlo desde el Form</p>

                        <p>Los informes con nonmenclatura <strong>TESO25010100</strong> A <strong>TESO25063099</strong>  deben validarse siguiendo el procedimiento de validacio manual.</p>

                        <p>Si tienes alguna duda, por favor comunícate con el área de 💻 sistemas al 📞 Ext. 109.</p>
                    </div>

                    <!-- Pie del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success rounded-pill px-4" data-dismiss="modal">
                            <i class="bi bi-check-circle me-1"></i> Enterado
                        </button>
                    </div>

                </div>
            </div>
        </div>



        <div class="content-wrapper" style="max-width: 50%; margin:0 auto;">
            <section class="content-header" style="padding-top: 90px;">
                <h1 style="text-align: center;">
                    Utiliza el siguiente codigo Qr para insertarlo en el informe. <?php echo "$roowfila[codbieninv]"  ?>
                </h1>
                <ol class="breadcrumb">
                    <a href="<?php if ($accion == 'REGISTRO') {
                                    echo 'registro-bien.php';
                                } else {
                                    echo 'javascript:history.back(-1);';
                                } ?>" class="btn btn-default btn-sm" title="Volver atras">Volver atras <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
                </ol>
            </section>
            <section class="content" style="display: grid; align-items: center; justify-items: center;">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="row">
                                <div class="col-md-4 text-center" style="margin:0px; padding:0px; width:200px; ">
                                    <div style="width:100%; font-size:14px ;margin:0px; padding:0px;">
                                        <?php
                                        // Directorio temporal para los QR
                                        $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
                                        $PNG_WEB_DIR = 'temp/';

                                        // Crear el directorio si no existe
                                        if (!file_exists($PNG_TEMP_DIR)) {
                                            mkdir($PNG_TEMP_DIR, 0755, true);
                                        }

                                        // Limpia solo archivos QR antiguos de este bien
                                        foreach (glob($PNG_TEMP_DIR . 'qr_' . $roowfila['idbien'] . '_*.png') as $file) {
                                            if (is_file($file)) unlink($file);
                                        }

                                        include "codigoqr/qrlib.php";

                                        // Parámetros QR
                                        $matrixPointSize = 10;
                                        $errorCorrectionLevel = 'H'; // Usa nivel 'H' (Alta) para permitir insertar logo
                                        $data = "https://www.i-teso.com/vlqr/validacionqr/index.php?codunicoinfor=" . urlencode($roowfila['codunicoinfor']);
                                        $filename = $PNG_TEMP_DIR . 'qr_' . $roowfila['idbien'] . '_' . md5($data) . '.png';

                                        // Solo genera si no existe
                                        if (!file_exists($filename)) {
                                            QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

                                            // Insertar logotipo
                                            $QR = imagecreatefrompng($filename);
                                            $logoPath = __DIR__ . './img/logotesoqr.jpg'; // Ruta al logo

                                            if (file_exists($logoPath)) {
                                                $logo = imagecreatefrompng($logoPath);

                                                // Dimensiones
                                                $qrWidth = imagesx($QR);
                                                $qrHeight = imagesy($QR);
                                                $logoWidth = imagesx($logo);
                                                $logoHeight = imagesy($logo);

                                                // Escala del logo (20-25% del QR)
                                                $logo_qr_width = $qrWidth * 0.50;
                                                $scale = $logo_qr_width / $logoWidth;
                                                $logo_qr_height = $logoHeight * $scale;

                                                // Posición centrada
                                                $posX = ($qrWidth - $logo_qr_width) / 2;
                                                $posY = ($qrHeight - $logo_qr_height) / 2;

                                                // Fusionar imagen
                                                imagecopyresampled($QR, $logo, $posX, $posY, 0, 0, $logo_qr_width, $logo_qr_height, $logoWidth, $logoHeight);

                                                // Guardar con logo sobreescribiendo
                                                imagepng($QR, $filename);

                                                // Liberar memoria
                                                imagedestroy($logo);
                                            }

                                            imagedestroy($QR);
                                        }

                                        // Mostrar QR
                                        echo '
                                                <a href="' . htmlspecialchars($data) . '">
                                                    <img style="width:100%;margin:0px; padding:0px" src="' . $PNG_WEB_DIR . basename($filename) . '" alt="QR Código" />
                                                </a>
                                            ';
                                        ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </section>
            <section>
                <div class="content-qr-logo" style="display: grid; align-items: center; justify-items: center; padding-top: 10px;">
                    <img src="./img/LOGO.jpg" alt="">
                </div>
            </section>
        </div>
        <?php // include('footer.html'); 
        ?>
    </div>
    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#myModal").modal("show");
        });
    </script>
    <script>
        // $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="plugins/fastclick/fastclick.js"></script>
 AdminLTE App 
<script src="dist/js/app.min.js"></script>-->
</body>

</html>