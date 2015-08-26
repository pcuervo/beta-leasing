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
$email =isset( $_POST['email'] ) ? $_POST['email'] : '-' ;
$telefono =isset( $_POST['telefono'] ) ? $_POST['telefono'] : '-' ;
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
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH );

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
<style>
	h1 {
		font-family: helvetica;
		font-size: 20pt;
		text-align: center;
	}
	p {
		color: #003300;
		font-family: helvetica;
		font-size: 12pt;
	}

	table {
		color: #003300;
		font-family: helvetica;
		font-size: 12pt;
	}
	table.border{
		border: 2px solid #313131;
	}
	th{
		font-size: 16px;
		font-weigth: bold;
	}
	td {
		padding: 4px;
		border: none;
	}

	.text-right{
		text-align: right;
	}

	.text-center{
		text-align: center;
	}


</style>

<p class="[ text-right ]"><small>Fecha: $fecha</small></p>
<h1>Cotización estimada de arrendamiento puro</h1>

<table>
	<tr>
		<th><h4>Información cliente</h4></th>
		<th><h4>Datos de unidad a cotizar</h4></th>
	</tr>
	<tr>
		<td># de referencia: $filename</td>
		<td>Tipo de unidad: $tipo</td>
	</tr>
	<tr>
		<td>Cliente: $cliente</td>
		<td>Marca: $marca </td>
	</tr>
	<tr>
		<td>Compañía: $compania</td>
		<td>Modelo: $modelo</td>
	</tr>
	<tr>
		<td>Teléfono: $telefono</td>
		<td>Precio de referencia (con IVA): $valor_total</td>
	</tr>
	<tr>
		<td>Email: $email</td>
		<td></td>
	</tr>
</table>

<br />
<br />
<br />

<table>
	<tr>
		<td><h4>Plazo mensual</h4></td>
		<td><h4>$plazo_mensual</h4></td>
	</tr>
	<tr>
		<td><h4>Renta mensual fija (con IVA)</h4></td>
		<td><h4>$renta_mensual_iva</h4></td>
	</tr>
</table>

<h2>Pago inicial</h2>
<table class="[ border ]">
	<tr>
		<td>Pago inicial</td>
		<td>$pago_inicial</td>
	</tr>
	<tr>
		<td>Comisión 2%</td>
		<td>$valor_comision</td>
	</tr>
	<hr>
	<tr>
		<td>Subtotal</td>
		<td>$subtotal</td>
	</tr>
	<tr class="[ border-bottom ]">
		<td>IVA (16%)</td>
		<td>$iva</td>
	</tr>
	<hr>
	<tr>
		<td>Renta en depósito</td>
		<td>$renta_en_deposito</td>
	</tr>
	<tr>
		<td><h4>Total pago inicial</h4></td>
		<td><h4>$total_pago_inicial</h4></td>
	</tr>
	<tr>
		<td><h4>Valor residual (sin IVA)</h4></td>
		<td><h4>$valor_residual</h4></td>
	</tr>
</table>

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

<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />

<p class="[ text-center ]">
	<small>
		Cerrada Loma Bonita 33 Piso 3 · Lomas Altas · 11950 México D.F. <br />
		ventas@betaleasing.com · (55) 2325.0453
	</small>
</p>

EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$filename .= '.pdf';
$pdf->Output( $filename, 'F' );

$pdf_url = '/beta-leasing/cotizaciones/' . $filename;

//send_email_cotizacion( $nombre, $email, $compania, $pdf_url );

echo $pdf_url;

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

function send_email_cotizacion( $nombre, $email, $compania, $pdf_url ){

	$from_email = 'ventas@betaleasing.com';
	$to_email = 'miguel@pcuervo.com';

	$subject = $nombre . ' ha creado su cotización a través de www.betaleasing.com: ';
	$headers = 'From: Nombre <' . $from_email . '>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h3>Datos de contacto</h3>';
	$message .= '<p>Nombre: '.$nombre.'</p>';
	$message .= '<p>Email: '. $email . '</p>';
	$message .= '<p>Cotización: '. $pdf_url . '</p>';
	$message .= '</body></html>';

	mail($to_email, $subject, $message, $headers );

}// send_email_cotizacion
