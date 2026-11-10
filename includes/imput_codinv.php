<?php 
$codfam ='';
$codinv='';
if(isset($_GET['codfam']))
{ 
	$codfam = $_GET['codfam'];
}
if(isset($_GET['codinv']))
{ 
	$codinv = $_GET['codinv'];
}

 require_once('../Connections/conexion.php');
 //Info de la familia del bien
  
$query_infofam = "SELECT * FROM proveedor where idproveedor = '".$codfam."'";
$infofam = mysqli_query($conexion, $query_infofam) or die(mysqli_error());
$row_infofam=mysqli_fetch_array($infofam);

$codfamilia =  $row_infofam['personacontacto'];
//Sacar el maximo id de la tabla bien
 
$query_lista = "SELECT MAX( idbien ) as idd FROM bien where idfamiliabien = '".$codfam."'";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roow=mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);
$codigo = $roow['idd'] ;
//$codigo = $roow['idd'] +1 ;
//$codigo = substr('00000'. $codigo,-5) ;
$codinve = $codfamilia.$codigo 
 ?>
 <input  class="form-control"   type="text" name="codbieninvs" id="codbieninvs" value="<?php echo $codinve;?>" >
 
 <?php if ($codinv !=''){?>
 <small>Cod. anterior: <?php echo $codinv; ?></small><?php }?>