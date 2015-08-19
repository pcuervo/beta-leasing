<?php
//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Get POST variables
$cliente = $_POST['cliente']; 
$compania = isset( $_POST['compania'] ) ? $_POST['compania'] : '-' ; 
$tipo = $_POST['tipo']; 
$marca = isset( $_POST['marca'] ) ? $_POST['marca'] : '-' ; 
$modelo = isset( $_POST['modelo'] ) ? $_POST['modelo'] : '-' ; 
$valor_total = $_POST['valor_total']; 
$plazo_mensual = $_POST['plazo_mensual']; 
$renta_mensual_iva = $_POST['renta_mensual_iva']; 
$pago_inicial = $_POST['pago_inicial']; 
$valor_comision = $_POST['valor_comision']; 
$subtotal = $_POST['subtotal']; 
$iva = $_POST['iva']; 
$iva_mensual = $_POST['iva_mensual']; 
$renta_en_deposito = $_POST['renta_en_deposito']; 
$total_pago_inicial = $_POST['total_pago_inicial']; 
$valor_residual = $_POST['valor_residual']; 

$filename = time() . '-' . to_slug( $cliente );




// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Beta Leasing');
$pdf->SetTitle('Cotización de arrendamiento puro');
$pdf->SetSubject('Cotización');
$pdf->SetKeywords('PDF, Beta Leasing, cotizador');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Beta Leasing', '# de referencia: ' . $filename );

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
$fecha = date("d/m/Y");
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
	h1 {
		font-family: times;
		font-size: 24pt;
	}
	p.first {
		color: #003300;
		font-family: helvetica;
		font-size: 12pt;
	}
	p.first span {
		color: #006600;
		font-style: italic;
	}
	p#second {
		color: rgb(00,63,127);
		font-family: times;
		font-size: 12pt;
		text-align: justify;
	}
	p#second > span {
		background-color: #FFFFAA;
	}
	table.first {
		color: #003300;
		font-family: helvetica;
		font-size: 8pt;
		border-left: 3px solid red;
		border-right: 3px solid #FF00FF;
		border-top: 3px solid green;
		border-bottom: 3px solid blue;
		background-color: #ccffcc;
	}
	td {
		border: 2px solid blue;
		background-color: #ffffee;
	}
	td.second {
		border: 2px dashed green;
	}
	div.test {
		color: #CC0000;
		background-color: #FFFF66;
		font-family: helvetica;
		font-size: 10pt;
		border-style: solid solid solid solid;
		border-width: 2px 2px 2px 2px;
		border-color: green #FF00FF blue red;
		text-align: center;
	}
	.lowercase {
		text-transform: lowercase;
	}
	.uppercase {
		text-transform: uppercase;
	}
	.capitalize {
		text-transform: capitalize;
	}
</style>

<h1>Cotización estimada de arrendamiento puro</h1>

<p>Fecha: $fecha </p>

<h2>Información cliente</h2>
<p>Cliente: $cliente</p>
<p>Compañía: $compania</p>

<h2>Datos de unidad a cotizar</h2>
<p>Tipo de unidad: $tipo</p>
<p>Marca: $marca </p>
<p>Modelo: $modelo </p>

<p>Precio de referencia (con IVA): $valor_total</p>
<p>Plazo mensual: $plazo_mensual</p>
<p>Renta mensual fija (con IVA): $renta_mensual_iva</p>

<h2>Pago inicial</h2>
<p>Pago inicial: $pago_inicial</p>
<p>Comisión 2%: $valor_comision</p>
<hr>
<p>Subtotal: $subtotal</p>
<p>IVA (16%): $iva</p>
<hr>
<p>Renta en depósito: $renta_en_deposito</p>

<p>Total pago inicial: $total_pago_inicial</p>
<p>Valor residual (sin IVA): $valor_residual</p>

<p><small>
	Notas:
	<ol>
		<li>Las Rentas se pagarán mensualmente en las fechas establecidas en el Anexo de Arrendamiento.</li>
		<li>Esta cotización podrá ser modificada por el Arrendador hasta la firma del Contrato y Anexo de Arrendamiento.</li>
		<li>Los costos del seguro y localizador podrán ser pagados por el Arrendatario por anticipado o en pagos mensuales.</li>
		<li>La obtención de los permisos, licencias, placas o registros que se requieran para poseer o usar el Bien Arrendado, asi como el pago de tenencias, contribuciones, impuestos o costos inherentes al mantenimiento del Bien Arrendado serán responsabilidad del Arrendatario.</li>
		<li>El depósito en garantía será reembolsable en términos del Contrato Maestro de Arrendamiento Puro.</li>
		<li>La aceptación de esta cotización por el cliente constituye una obligación para firmar el Contrato de Arrendamiento y Anexos. En caso de no presentarse el Arrendatario, Obligado Solidario, Aval o Depositario a celebrar el Contrato o Anexo respectivo en la fecha y lugar señalado para tales efectos por el Arrendador; el Arrendatario se obliga a reembolsar al Arrendador cualquier gasto, desembolso o erogación en la que haya incurrido para adquirir el(los) bien(es) señalado(s) en esta Cotización. </li>
	</ol>
</small></p>

EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// add a page
$pdf->AddPage();


$html = "
	<h1>Tabla de pagos</h1>
	<p>Cliente: $cliente</p>
	<table>
		<tr>
			<th>Pago</th>
			<th>Renta</th>
			<th>IVA</th>
			<th>Pago Total</th>
		</tr>";

for ($i=1; $i < intval( $plazo_mensual ) ; $i++) { 
	$html .= "
		<tr>
			<td>$i</td>
			<td>$renta_en_deposito</td>
			<td>$iva_mensual</td>
			<td>$renta_mensual_iva</td>
		</tr>";
}

$html .= "</table>";

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$filename .= '.pdf';
$pdf->Output( $filename, 'F' );

echo '/beta-leasing/cotizaciones/' . $filename;

//============================================================+
// END OF FILE
//============================================================+


// Functions
function to_slug( $text )
{ 
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	// trim
	$text = trim($text, '-');
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// lowercase
	$text = strtolower($text);
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	if (empty($text)) return 'n-a';

	return $text;
}// to_slug
