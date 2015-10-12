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
$ano = isset( $_POST['ano'] ) ? $_POST['ano'] : '-' ;
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


// Beneficio fiscal y costo real estiamdo
$total_rentas_y_pago_inicial = 36 * sanitize_money_string( $renta_mensual_iva ) + sanitize_money_string( $subtotal ) + sanitize_money_string( $iva );
$ahorro_fiscal_isr = $total_rentas_y_pago_inicial * 0.3 * -1;
$ahorro_fiscal_ptu = $total_rentas_y_pago_inicial * 0.1 * -1;
$ahorro_fiscal_iva = $total_rentas_y_pago_inicial * 0.16 * -1;
$costo_real = $total_rentas_y_pago_inicial + $ahorro_fiscal_iva + $ahorro_fiscal_ptu + $ahorro_fiscal_isr + sanitize_money_string( $valor_residual );
// Agregar formato de dinero
$total_rentas_y_pago_inicial = "$" . number_format( $total_rentas_y_pago_inicial, 2, ",", "." );
$ahorro_fiscal_isr = "-$" . number_format( abs( $ahorro_fiscal_isr ), 2, ",", "." );
$ahorro_fiscal_ptu = "-$" . number_format( abs( $ahorro_fiscal_ptu ), 2, ",", "." );
$ahorro_fiscal_iva = "-$" . number_format( abs( $ahorro_fiscal_iva ), 2, ",", "." );
$costo_real = "$" . number_format( $costo_real, 2, ".", "," );

// Textos variables
if ( $tipo == 'vehiculo' ){
	$titulo_informacion = 'del Automóvil';
	$titulo_beneficio = 'Beneficio Fiscal y Costo Real Estimado';
}
if ( $tipo == 'maquinaria' ){
	$titulo_informacion = 'de la Maquinaria';
	$titulo_beneficio = 'Beneficio Fiscal y Costo Real Estimado';
}
if ( $tipo == 'mobiliario' ){
	$titulo_informacion = 'del Mobiliario / Equipo';
	$titulo_beneficio = 'Ahorro Fiscal y Costo Real';
}


$clave_referencia = time() . '-' . to_slug( $cliente );




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
	*{ color: #000000; }

	h1 {
		font-family: helvetica;
		font-size: 20pt;
		text-align: center;
	}
	p {
		color: #000000;
		font-family: helvetica;
		font-size: 12pt;
	}

	span{ display: block; }

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

	.text-right{ text-align: right; }
	.text-left{ text-align: left; }
	.text-center{ text-align: center; }

	.pull-right{ float: right; }
	.pull-left{ float: left; }

	.margin-bottom{ margin-bottom: 20px; }
	.margin-bottom--large{ margin-bottom: 40px; }

	.color-negative{ color: #f0151c; }


</style>

<h1>Cotización de arrendamiento puro</h1>
<h3 class="[ text-center ]">BIEN: $tipo</h3>

<br />

<table class="">
	<tr>
		<th>Clave referencia: $clave_referencia</th>
		<th><span class="[ text-right ]">Fecha: $fecha</span></th>
	</tr>
</table>

<br />

<table class="[ border ]">
	<tr>
		<th><h4 class="[ text-center ]">Información del cliente</h4></th>
	</tr>
	<tr>
		<td>Empresa: $compania</td>
	</tr>
	<tr>
		<td>Contacto: $cliente</td>
	</tr>
	<tr>
		<td>Teléfono: $telefono</td>
	</tr>
	<tr>
		<td>Correo electrónico: $email</td>
	</tr>
</table>

<table class="[ border ]">
	<tr>
		<td>
			<span class="[ text-center ]">
				<strong>Información $titulo_informacion </strong>
			</span>
		</td>
	</tr>
	<tr>
		<td>Marca: $marca </td>
		<td>Modelo: $modelo</td>
	</tr>
	<tr>
		<td>Año: $ano</td>
		<td>Valor Factura: $valor_total</td>
	</tr>
</table>

<table class="[ border ]">
	<tr>
		<td><span class="[ text-center ]"><strong>Información del arrendamiento</strong></span></td>
		<td></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Plazo</strong></span></td>
		<td><strong>$plazo_mensual</strong></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Renta mensual</strong></span></td>
		<td><strong>$renta_mensual_iva</strong></td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Pago inicial</span></td>
		<td>$pago_inicial</td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Comisión 2%</span></td>
		<td>$valor_comision</td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Subtotal</span></td>
		<td>$subtotal</td>
	</tr>
	<tr class="[  ]">
		<td><span class="[ text-right ]">IVA (16%)</span></td>
		<td>$iva</td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Renta en depósito</span></td>
		<td>$renta_en_deposito</td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Total pago inicial</strong></span></td>
		<td><strong>$total_pago_inicial</strong></td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Valor residual (sin IVA)</strong></span></td>
		<td><strong>$valor_residual</strong></td>
	</tr>
</table>

<table class="[ border ]">
	<tr>
		<td><h4  class="[ text-right ]">$titulo_beneficio</h4></td>
		<td></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Total renta + Pago inicial</strong></span></td>
		<td><strong>$total_rentas_y_pago_inicial</strong></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Ahorro fiscal I.S.R. 30%</span></td>
		<td><span class="[ color-negative ]">$ahorro_fiscal_isr</span></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Ahorro fiscal P.T.U. 10%</span></td>
		<td><span class="[ color-negative ]">$ahorro_fiscal_ptu</span></td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Ahorro fiscal I.V.A.</span></td>
		<td><span class="[ color-negative ]">$ahorro_fiscal_iva</span></td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td><span class="[ text-right ]">Valor residual</span></td>
		<td>$valor_residual</td>
	</tr>
	<tr>
		<td><span class="[ text-right ]"><strong>Costo real</strong></span></td>
		<td><strong>$costo_real</strong></td>
	</tr>
</table>


<p><small>
	Notas: Los importes de seguros, impuestos, comisiones y costos de accesorios no están incluidos en esta cotización. La presente cotización se emite únicamente con fines informativos y podrá ser objeto de modificaciones. La celebración de cualquier contrato está sujeta a la aprobación de la solicitud de arrendamiento respectiva. El beneficio fiscal y costo real esta sujeto a la situación particular de cada cliente.
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
$filename = $clave_referencia . '.pdf';
$pdf->Output( $filename, 'F' );

//Servidor
//$pdf_url = 'http://pcuervo.com/beta-leasing/cotizaciones/' . $filename;

//Local
$pdf_url = 'http://localhost:8888/beta-leasing/cotizaciones/' . $filename;

send_email_cotizacion( $cliente, $email, $telefono, $compania, $pdf_url, $clave_referencia );

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

function send_email_cotizacion( $nombre, $email, $telefono,  $compania, $pdf_url, $clave_referencia ){

	$nombre = utf8_decode ( $nombre );

	// Correo a Beta Leasing
	$from_email = 'no-reply@betaleasing.com';
	$to_email = 'miguel@pcuervo.com';
	$subject = $nombre . ' ha creado su cotización a través de betaleasing.com ';
	$headers = 'From: ' . $nombre . ' <' . $from_email . '>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h3>Datos de contacto</h3>';
	$message .= '<p>Nombre: '. $nombre .'</p>';
	$message .= '<p>Email: '. $email . '</p>';
	$message .= '<p>Teléfono: '. $telefono . '</p>';
	$message .= '<p>Clave de referencia: '. $clave_referencia . '</p>';
	$message .= '<a href="' . $pdf_url . '">Ver cotización</a>';
	$message .= '<img src="http://pcuervo.com/beta-leasing/images/beta-leasing.png"><br>';
	$message .= '</body></html>';

	mail($to_email, $subject, $message, $headers );

	// Correo a cliente
	$from_email = 'ventas@betaleasing.com';
	$to_email = $email;
	$subject = 'Has creado una cotización a través de www.betaleasing.com: ';
	$headers = 'From: Ventas BetaLeasing <' . $from_email . '>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h3>&#161;Gracias por tu inter&eacute;s en nuestro servicio!</h3>';
	$message .= '<p>Estimad@ ' .$nombre. ':</p>';
	$message .= '<p>Muy pronto nos pondremos en contacto contigo. Por el momento puedes descargar tu cotizaci&oacute;n en el <a href="' . $pdf_url . '">siguiente enlace</a>.</p>';
	$message .= '<img src="http://pcuervo.com/beta-leasing/images/beta-leasing.png"><br>';
	$message .= '</body></html>';

	mail($to_email, $subject, $message, $headers );

}// send_email_cotizacion

function sanitize_money_string( $amount ){
	return floatval( str_replace(',', '', str_replace('$', '', $amount ) ) );
}