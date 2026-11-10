<?php require_once('../../../Connections/conexion.php');

$menuactivo = 'movimiento';
if (!isset($_SESSION)) {
    session_start();
} ?>
<?php
$idbien = '';
$idmov = '';
if (isset($_GET['id'])) {
    $idbien = $_GET['id'];
}

$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, proveedor.direccion, proveedor.personacontacto, familiabien.codfamiliabien, familiabien.familiabien FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
inner join proveedor on bien.idproveedor = proveedor.idproveedor 
inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  
WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roowfila = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);
$nombrearchivo = $roowfila["codbieninv"];

if (isset($_GET['idmov']) and $_GET['idmov'] != '') {
    $idmov = $_GET['idmov'];
} else {
    $idmov = $roowfila['idmovimiento'];
}

$query_listamov = "SELECT * FROM movimiento where idbien = '" . $idbien . "' and idmov = '" . $idmov . "' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov = mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

//-------------------------------------------------------------------------------

$bloque1 = <<<EOF
	<div align="center"; top: "50%">
	<img src="images/impr_izquierda.jpg" width="50px">
	LABORATORIO TESO DE MEXICO S.A. DE C.V.
	<h5>SOLICITUD ITEM A ALMACEN</h5>
	</div>
EOF;
$pdf->writeHTML($bloque1, false, false, false, false, '');
// ---------------------------------------------------------

$bloque2 = <<<EOF

	<table style="font-size:10px; padding:3px 10px;">	
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540x">
			<strong>EMPRESA:</strong> $roowfila[razonsocial]
			</td>
		</tr>
	</table>
	<table style="font-size:10px; padding:3px 10px;">	
	<tr>		
		<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
		<strong>REPRESENTANTE:</strong> $roowfila[personacontacto]
		</td>
	</tr>
</table>

<table style="font-size:10px; padding:3px 10px;">	
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:150px">
			<strong>No. TESO:</strong> $roowfila[codbieninv] $roowfila[segui]
			</td>
			<td style="border: 1px solid #DFDFDF; background-color:white; width:390px; text-align:left">		
			<strong>ITEM:</strong>  $roowfila[nombien]
			</td>
		</tr>
	</table>
    <table style="font-size:10px; padding:3px 10px;">	
		<tr>					
			<td style="border: 1px solid #DFDFDF; background-color:white; width:150px; text-align:left">		
			<strong>MARCA:</strong> $roowfila[codmarca]
			</td>
            <td style="border: 1px solid #DFDFDF; background-color:white; width:150px; text-align:left">		
			<strong>MODELO:</strong> $roowfila[codmodelo]
			</td>
            <td style="border: 1px solid #DFDFDF; background-color:white; width:240px; text-align:left">		
			<strong>No. SERIE:</strong> $roowfila[serie]
			</td>
		</tr>
	</table>
    <table style="font-size:10px; padding:3px 10px;">	
	<tr>		
		<td style="border: 1px solid #DFDFDF; background-color:white; width:350px">
		<strong>NORMA:</strong> $roowfila[codfamiliabien]
		</td>
        <td style="border: 1px solid #DFDFDF; background-color:white; width:135px">
		<strong>UBICACION/ANAQUEL:</strong> 
		</td>
        <td style="border: 1px solid #DFDFDF; background-color:white; width:55px">
        $roowfila[lugar]
		</td>
	</tr>
</table>
<br>
<table style="font-size:10px; padding:3px 10px;">	
<tr>		
    <td style="background-color:white; width:540px">    
    </td>
</tr>
<tr>		
    <td style="background-color:white; width:540px">
        <strong>Accesorios:</strong>
    </td>
</tr>
</table>
<table style="font-size:10px; padding:3px 10px;">	
		<tr>					
			<td style="border: 1px solid #DFDFDF; background-color:white; width:116px; text-align:left">		
            <input type="checkbox" name="adap" value="1" /> <label for="agree">Adaptador ca/cc </label>
			</td>
            <td style="border: 1px solid #DFDFDF; background-color:white; width:100px; text-align:left">		
			<input type="checkbox" name="bat" value="1" /> <label for="agree">Bateria </label>
			</td>
            <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="cabred" value="1" /> <label for="agree">Cable de red </label>
			</td>
            <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="tin" value="1" /> <label for="agree">Manual </label>
			</td>            
            <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="mic" value="1" /> <label for="agree">Microfono </label>
            </td>   
		</tr>
	</table>    
<table style="font-size:10px; padding:3px 10px;">	
    <tr>					
        <td style="border: 1px solid #DFDFDF; background-color:white; width:100px; text-align:left">		
            <input type="checkbox" name="pil" value="1" /> <label for="agree">Folleto </label>
        </td>
        <td style="border: 1px solid #DFDFDF; background-color:white; width:116px; text-align:left">		
            <input type="checkbox" name="ctr" value="1" /> <label for="agree">Control remoto </label>
        </td>
        <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="tec" value="1" /> <label for="agree">Teclado </label>
        </td>
        <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="herr" value="1" /> <label for="agree">Herramientas </label>
        </td>   
        <td style="border: 1px solid #DFDFDF; background-color:white; width:108px; text-align:left">		
            <input type="checkbox" name="cabali" value="1" /> <label for="agree">Cable Alim </label>
        </td>   
    </tr>
</table>
EOF;
$pdf->writeHTML($bloque2, false, false, false, false, '');
// ---------------------------------------------------------

$bloque3 = <<<EOF
<table style="font-size:10px; padding:3px 10px;">	
<tr>		
<br>
    <td style="background-color:white; width:340px">
    <strong>Salida del almacen al area de laboratorio para la corrida de ensayos.</strong>    
    </td>
    <td style="background-color:white; width:200px">
    <strong>FECHA. _____/______/__________</strong>    
    </td>
</tr>
</table>
<table style="font-size:10px; padding:5px 10px;">	
<tr>	
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Ing. Laboratorista</p></td>
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Almacén</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #060044; background-color:white; width:49%;text-align: center ">
	    <br>				
	</td>
	<td style="border: 1px solid #060044; background-color:white; width:49%; text-align: center">		
	</td>
</tr>
</table>
<table style="font-size:10px; padding:3px 10px;">	
<tr>		
    <td style="background-color:white; width:540px">    
    </td>
</tr>
<tr>		
    <td style="background-color:white; width:340px">
    <strong>Entrega del item a almacén, termino de ensayos.</strong>    
    </td>
    <td style="background-color:white; width:200px">
    <strong>FECHA. _____/______/__________</strong>    
    </td>
</tr>
</table>
<table style="font-size:10px; padding:5px 10px;">	
<tr>		
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Ing. Laboratorista</p></td>
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Almacén</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #060044; background-color:white; width:49%;text-align: center ">
		<br>				
	</td>
	<td style="border: 1px solid #060044; background-color:white; width:49%; text-align: center">		
	</td>
</tr>

</table>
EOF;
$pdf->writeHTML($bloque3, false, false, false, false, '');

$bloque4 = <<<EOF
<table style="font-size:10px; padding:10px 10px;">	
<tr>		
    <td style="background-color:white; width:340px">
    <strong>Salida del almacén al área de laboratorio para dar respuesta a desviación/notificación</strong>
    </td>
    <td style="background-color:white; width:200px">
    <strong>FECHA. _____/______/__________</strong>
    </td>
</tr>
</table>
<table style="font-size:10px; padding:5px 10px;">	
<tr>	
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Ing. Laboratorista</p></td>
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Almacén</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #060044; background-color:white; width:49%;text-align: center ">
		<br>				
	</td>
	<td style="border: 1px solid #060044; background-color:white; width:49%; text-align: center">		
	</td>
</tr>
</table>
<table style="font-size:10px; padding:3px 10px;">	
<tr>		
    <td style="background-color:white; width:540px">    
    </td>
</tr>
<tr>		
    <td style="background-color:white; width:340px">
    <strong>Entrega del item a almacén.</strong>
    </td>
    <td style="background-color:white; width:200px">
    <strong>FECHA. _____/______/__________</strong>
    </td>
</tr>
</table>
<table style="font-size:10px; padding:5px 10px;">	
<tr>		
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Ing. Laboratorista</p></td>
<td style="border: 1px solid #060044; width:49%; text-align:center"><p style="color:#060044;">Almacén</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #060044; background-color:white; width:49%;text-align: center ">
		<br>					
	</td>
	<td style="border: 1px solid #060044; background-color:white; width:49%; text-align: center">		
	</td>
</tr>
</table>
<table style="font-size:10px; padding:3px 10px;">	
<tr>		
    <td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
    <strong>Observaciones:</strong>
    <br>        
    </td>
</tr>
</table>
EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

$bloque5 = <<<EOF
	<table>		
		<tr>			
			<td style="background-color:white; width:540px">	            
                <div style="font-size:10px; text-align: right; line-height:5px;">					
                FLAM-419-03                
            </div>
			</td>
		</tr>
	</table>
EOF;

$pdf->writeHTML($bloque5, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 
$nombrear = "SOLICITUD" . " " . ($nombrearchivo) . ".pdf";
$pdf->Output($nombrear, 'D');

?>