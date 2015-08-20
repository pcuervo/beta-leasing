<?php

	$nombre = $_POST['nombre'];
	$email = $_POST['email'];
	$mensaje = $_POST['mensaje'];
	$to_email = 'miguel@pcuervo.com';

	$subject = $nombre . ' te ha contactado a través de www.betaleasing.com: ';
	$headers = 'From: Nombre <' . $to_email . '>' . "\r\n";
	$message = '<html><body>';
	$message .= '<h3>Datos de contacto</h3>';
	$message .= '<p>Nombre: '.$nombre.'</p>';	
	$message .= '<p>Email: '. $email . '</p>';
	$message .= '<p>Mensaje: '. $mensaje . '</p>';
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

