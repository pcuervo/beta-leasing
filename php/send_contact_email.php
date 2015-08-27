<?php

	$nombre = $_POST['nombre'];
	$email = $_POST['email-contacto'];
	$mensaje = $_POST['mensaje'];
	$telefono = $_POST['telefono-contacto'];
	$to_email = 'miguel@pcuervo.com';

	$subject = $nombre . ' te ha contactado a través de www.betaleasing.com: ';
	$headers = 'From: Nombre <' . $to_email . '>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h3>Datos de contacto</h3>';
	$message .= '<p>Nombre: '.$nombre.'</p>';	
	$message .= '<p>Email: '. $email . '</p>';
	$message .= '<p>Teléfono: '. $telefono . '</p>';
	$message .= '<p>Mensaje: '. $mensaje . '</p>';
	$message = '<img src="http://pcuervo.com/beta-leasing/images/beta-leasing.png"><br>';
	$message .= '</body></html>';

	$mail = mail($to_email, $subject, $message, $headers );

	if( ! $mail ) {
		$json_message = array(
		'error'		=> 1,
		'message'	=> 'No se pudo enviar el correo.',
		);
		echo json_encode($json_message , JSON_FORCE_OBJECT);
	}

	$json_message = array(
		'error'		=> 0,
		'message'	=> 'Gracias por tu mensaje ' . $nombre . '. En breve nos pondremos en contacto contigo.',
	);
	echo json_encode($json_message , JSON_FORCE_OBJECT);

