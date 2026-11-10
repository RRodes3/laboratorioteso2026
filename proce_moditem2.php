<?php require_once('Connections/conexion.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $modclasificacion = 1;
  $updateSQL = sprintf(
    "UPDATE movimiento SET clasificacion=%s, modclasificacion='". $modclasificacion. "' WHERE idbien=%s",
    GetSQLValueString($_POST['clasificacion'], "text"),
    GetSQLValueString($_POST['idbien'], "int")
  );

  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

  $codigo = '';
  $codigo = $_POST['idbien'];
  date_default_timezone_set('America/Bogota');
  $fecha = date('Y-m-d');
  $hora = date('h:i:s');
  $fechaupdateestado = $fecha . ' ' . $hora;

  $query_actbien = "UPDATE licencia SET estado = 'pendiente', fech_pruebas = '" . $fechaupdateestado . "' WHERE id_bien='" . $codigo . "'";
  $listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());

  $updateGoTo = "informes-asignados.php";

  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//////////////////////////////////////////


?>
