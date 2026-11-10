<?php 

require_once('Connections/conexion.php');

$codigo = '';
$nomtabla = '';
$nomtablaa = '';
$nomtablaaaa = '';
date_default_timezone_set('America/Bogota');
$fecha = date('Y-m-d');
			$hora = date('h:i:s');
			$fechaupdateestado = $fecha.' '.$hora;
 
if(isset($_POST['nomtabla']) and $_POST['nomtabla']!='')
{
	$nomtabla =$_POST['nomtabla'];
}
//Eliminar tabla familiabien
if(isset($_POST['id_licencia']) and $nomtabla =='licencia')
{
	$codigo =$_POST['id_licencia'];
	if($codigo !=''){		
		
		$deleteSQL = "UPDATE licencia SET estado = 'Pruebas', fech_pruebas = '".$fechaupdateestado."' WHERE id_licencia='".$codigo."'";
		
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:informes-asignados.php");
	
}


//===============================

if(isset($_POST['nomtablaa']) and $_POST['nomtablaa']!='')
{
	$nomtablaa =$_POST['nomtablaa'];
}
//Eliminar tabla familiabien
if(isset($_POST['id_licencia']) and $nomtablaa =='licencia')
{
	$codigo =$_POST['id_licencia'];
	if($codigo !=''){		
		$deleteSQL = "UPDATE licencia SET estado = 'Supervision', fech_supervision = '".$fechaupdateestado."' WHERE id_licencia='".$codigo."'";
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:informes-asignados.php");
	
}



//Return informe a pruebas
if(isset($_POST['formreturnpruebas']) and $_POST['formreturnpruebas']!='')
{
	$nomtabla =$_POST['formreturnpruebas'];
}
if(isset($_POST['id_licencia']) and $nomtabla =='licencia')
{
	$codigo =$_POST['id_licencia'];
	if($codigo !=''){				
		$deleteSQL = "UPDATE licencia SET estado = 'Pruebas', fech_pruebas = '".$fechaupdateestado."' WHERE id_licencia='".$codigo."'";			
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:estatus-informes-supervision.php");
	
}

//Enviar informe a pendientes por enviar

if(isset($_POST['nomtablaaaa']) and $_POST['nomtablaaaa']!='')
{
	$nomtablaaaa =$_POST['nomtablaaaa'];
}
//Eliminar tabla familiabien
if(isset($_POST['id_licencia']) and $nomtablaaaa =='licencia')
{
	$codigo =$_POST['id_licencia'];
	if($codigo !=''){		
		$deleteSQL = "UPDATE licencia SET estado = 'Supervisado', fech_finalizado = '".$fechaupdateestado."' WHERE id_licencia='".$codigo."'";
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:estatus-informes-supervision.php");
	
}

?>