<?php
require 'PHPMailerAutoload.php';
include '/var/www/html/conexion.php';

if (isset($_POST["nombre"]) && isset($_POST["id"]) && isset($_POST["email"]) && isset($_POST["asunto"]) && isset($_POST["mensaje"])) {

	$nombre = $_POST["nombre"];
	$id = $_POST["id"];
	$email = $_POST["email"];
	$asunto = $_POST["asunto"];
	$mensaje = $_POST["mensaje"];

	$mail = new PHPMailer;

	$mail->isSMTP();                               
	$mail->Host = $smtp;  
	$mail->SMTPAuth = true;                               
	$mail->Username = $tomail;                
	$mail->Password = $passemail;                           
	$mail->SMTPSecure = $smtpsecure;                            
	$mail->Port = $portsmtp;                                    

	$mail->setFrom($fromemail, 'Bedya');
	$mail->addAddress($tomail, 'Soporte Bedya'); 
	$mail->isHTML(true);                                  

	$mail->Subject = 'Nuevo mensaje';
	$mail->Body    = '<strong>Nombre: </strong>'.$nombre.'<br><strong>ID Telegram: </strong>'.$id.'<br><strong>Email: </strong>'.$email.'<br><strong>Asunto: </strong>'.$asunto.'<br><strong>Mensaje: </strong>'.$mensaje;

	if(!$mail->send()) {
		header("Location:../contacto?code=1"); //Codigo 1 se ha producido un error al enviarel mail
	} else {
		header("Location:../contacto?code=2"); //El mail se ha enviado correctamente
	}
}
else{
	header("Location:/");
}
?>