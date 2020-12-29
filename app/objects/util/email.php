<?php
include_once("configuration.php");
class Email{
	private $enviar = false;
	private $status    = "";
	private $msj       = "";
	
   public function __construct(){
   } 
	
	public function getStatus(){
		return $this->status;
	}	

	private  function setStatus($status){
		$this->status = $status;
	}
	
	public function getMsj(){
		return $this->msj;
	}	

	private function setMsj($msj){
		$this->msj = $msj;
	}
	
	private  function getEnviar(){
		return $this->enviar;
	}
	
	public function setEnviar($enviar){
		$this->enviar = $enviar;
	}

	public function enviarEmailAltaUsuario($mensaje,$para){
		logEmailGral("Envio enviarEmailAltaUsuario\nmensajeTexto: $mensaje\nusuario $para\n");
		if ($this->getEnviar() == true){
			$this->setEnviar(false);

			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer
			$mail = new PHPMailer();

			$mail->IsSMTP();
			//$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true;  // authentication enabled
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			$mail->Host = $GLOBALS['configuration_email']['host']; 

			$mail->Port = 587;
			$mail->Username = $GLOBALS['configuration_email']['user'];
			$mail->Password = $GLOBALS['configuration_email']['pass'];
			
			$mail->From = $GLOBALS['configuration_email']['from'];
			$mail->FromName = $GLOBALS['configuration_email']['from_name'];
			$mail->Subject = $GLOBALS['configuration_email']['subject_alta_usuario'];

			//$mail->AddAddress("rikizito@gmail.com","TEST");

			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress($para,$para);

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = $mensaje;

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				$this->setStatus("error");
				$this->setMsj(getMsjConf('392')." Err: ".$mail->ErrorInfo);
				logEmailGral("Respuesta ERROR => Envio enviarEmailAltaUsuario\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta: " . getMsjConf('392') . " Err: ".$mail->ErrorInfo . "\n");
			}else{
				logEmailGral("Respuesta OK => Envio enviarEmailAltaUsuario\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta:\n");
			}		

			//Para enviar msj de texto solo
			//$this->setEnviar(true); $this->setStatus("");
			//$this->enviarEmailRecuperar($mensajeTexto,$para);
		}else{
			logEmailGral("Respuesta ERROR => Envio enviarEmailAltaUsuario\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta: " . getMsjConf('342') . "\n");
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
		}
	}
	
	public function enviarEmailRecuperar($mensaje,$para){
		if ($this->getEnviar() == true){
			$this->setEnviar(false);
			$asunto = "Recuperar clave/usuario";
			$from   = "noreply@arquipick.com";
			$mensaje .= "Enviado el " . date('d/m/Y', time()) . "\n\n---------------------------------\n";
			$header = "From: $from \r\n";
			$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
			$header .= "Mime-Version: 1.0 \r\n";
			$header .= "Content-Type: text/plain";
			mail($para, $asunto, utf8_decode($mensaje), $header);
			//mail($para, $asunto, utf8_encode($mensaje), $header);
			$this->setStatus("ok");
			$this->setMsj("");
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
		}
	}
	
	public function enviarEmailRecuperarHtml($mensajeTexto,$body,$para,$usuario){
		logEmailGral("Envio enviarEmailRecuperarHtml\nmensajeTexto: $mensajeTexto\nbody: $body\nusuario $usuario\n");
		if ($this->getEnviar() == true){
			$this->setEnviar(false);

			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer
			$mail = new PHPMailer();

			$mail->IsSMTP();
			//$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true;  // authentication enabled
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			$mail->Host = $GLOBALS['configuration_email']['host']; 

			$mail->Port = 587;
			$mail->Username = $GLOBALS['configuration_email']['user'];
			$mail->Password = $GLOBALS['configuration_email']['pass'];
			
			$mail->From = $GLOBALS['configuration_email']['from'];
			$mail->FromName = $GLOBALS['configuration_email']['from_name'];
			$mail->Subject = $GLOBALS['configuration_email']['subject_recuperar_clave'];

			//$mail->AddAddress("rikizito@gmail.com","TEST");

			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress($para,$usuario);

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = $body;

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				$this->setStatus("error");
				$this->setMsj(getMsjConf('392')." Err: ".$mail->ErrorInfo);
				logEmailGral("Respuesta ERROR => Envio enviarEmailRecuperarHtml\nmensajeTexto: $mensajeTexto\nbody: $body\nusuario $usuario\nDetalle Respuesta: " . getMsjConf('392') . " Err: ".$mail->ErrorInfo . "\n");
			}else{
				logEmailGral("Respuesta OK => Envio enviarEmailRecuperarHtml\nmensajeTexto: $mensajeTexto\nbody: $body\nusuario $usuario\nDetalle Respuesta:\n");
			}		

			//Para enviar msj de texto solo
			//$this->setEnviar(true); $this->setStatus("");
			//$this->enviarEmailRecuperar($mensajeTexto,$para);
		}else{
			logEmailGral("Respuesta ERROR => Envio enviarEmailRecuperarHtml\nmensajeTexto: $mensajeTexto\nbody: $body\nusuario $usuario\nDetalle Respuesta: " . getMsjConf('342') . "\n");
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
		}
	}
	
	public function enviarEmailErrorSilverpop($mensaje){
		logEmailGral("Envio enviarEmailErrorSilverpop\nmensaje: $mensaje\n");
		$para = $GLOBALS['configuration_email']['para_error_silverpop_alta_cliente']; 
		if ($this->getEnviar() == true){
			$this->setEnviar(false);
			$asunto = "Error api - alta cliente";
			$from   = "noreply@arquipick.com";
			$mensaje .= "Enviado el " . date('d/m/Y', time()) . "\n\n---------------------------------\n";
			$header = "From: $from \r\n";
			$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
			$header .= "Mime-Version: 1.0 \r\n";
			$header .= "Content-Type: text/plain";
			//mail($para, $asunto, utf8_decode($mensaje), $header);
			mail($para, $asunto, utf8_encode($mensaje), $header);
			$this->setStatus("ok");
			$this->setMsj("");
			logEmailGral("Respuesta OK => Envio enviarEmailErrorSilverpop\nmensaje: $mensaje\nDetalle Respuesta:\n");
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
			logEmailGral("Respuesta ERROR => Envio enviarEmailErrorSilverpop\nmensaje: $mensaje\nDetalle Respuesta:" . getMsjConf('342') . "\n");
		}
	}
	
	public function enviarEmailPin($body,$para,$usuario){
		logEmailGral("Envio enviarEmailPin\nbody: $body\npara $para\nusuario $usuario\n");
		if ($this->getEnviar() == true){
			$this->setEnviar(false);

			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer

			$mail = new PHPMailer();

			$mail->IsSMTP();
			$mail->SMTPAuth = $GLOBALS['configuration_email']['sms_auth']; 
			$mail->Username = $GLOBALS['configuration_email']['user'];
			$mail->Password = $GLOBALS['configuration_email']['pass'];
			$mail->Host = $GLOBALS['configuration_email']['host'];
			
			$mail->From = $GLOBALS['configuration_email']['from'];
			$mail->FromName = $GLOBALS['configuration_email']['from_name'];
			$mail->Subject = $GLOBALS['configuration_email']['subject_envio_pin'];

			//$mail->AddAddress("rikizito@gmail.com","TEST");

			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress($para,$usuario);

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = $body;

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				$this->setStatus("error");
				$this->setMsj(getMsjConf('392')." Err: ".$mail->ErrorInfo);
				logEmailGral("Respuesta ERROR => Envio enviarEmailPin\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta: " .getMsjConf('392')." Err: ".$mail->ErrorInfo."\n");
			}else{
				logEmailGral("Respuesta OK => Envio enviarEmailPin\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta:\n");
			}
						
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
				logEmailGral("Respuesta ERROR => Envio enviarEmailPin\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta: " .getMsjConf('342')."\n");
		}
	}
	
	public function enviarEmailTransaccionExitosa($body,$para,$usuario){
		logEmailGral("Envio enviarEmailTransaccionExitosa\nbody: $body\npara $para\nusuario $usuario\n");
		if ($this->getEnviar() == true){
			$this->setEnviar(false);

			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer

			$mail = new PHPMailer();

			$mail->IsSMTP();
			$mail->SMTPAuth = $GLOBALS['configuration_email']['sms_auth']; 
			$mail->Username = $GLOBALS['configuration_email']['user'];
			$mail->Password = $GLOBALS['configuration_email']['pass'];
			$mail->Host = $GLOBALS['configuration_email']['host'];
			
			$mail->From = $GLOBALS['configuration_email']['from'];
			$mail->FromName = $GLOBALS['configuration_email']['from_name'];
			$mail->Subject = $GLOBALS['configuration_email']['subject_transaccion_exitosa'];

			//$mail->AddAddress("rikizito@gmail.com","TEST");

			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress($para,$usuario);

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = $body;

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				$this->setStatus("error");
				$this->setMsj(getMsjConf('392')." Err: ".$mail->ErrorInfo);
				logEmailGral("Respuesta ERROR => Envio enviarEmailTransaccionExitosa\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta:".getMsjConf('392')." Err: ".$mail->ErrorInfo."\n");
			}else{
				logEmailGral("Respuesta OK => Envio enviarEmailTransaccionExitosa\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta:\n");
			}
						
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
			logEmailGral("Respuesta ERROR => Envio enviarEmailTransaccionExitosa\nbody: $body\npara $para\nusuario $usuario\nDetalle Respuesta:".getMsjConf('342')."\n");
		}
	}
	
	public function enviarErrorEnvio($mensaje){
			logEmailGral("Envio enviarErrorEnvio\nmensaje $mensaje\n");
			$para  = "rikizito@gmail.com";
			$asunto = "Error envio";
			$from   = "noreply@arquipick.com";
			$mensaje .= "Enviado el " . date('d/m/Y', time()) . "\n\n---------------------------------\n";
			$header = "From: $from \r\n";
			$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
			$header .= "Mime-Version: 1.0 \r\n";
			$header .= "Content-Type: text/plain";
			//mail($para, $asunto, utf8_decode($mensaje), $header);
			mail($para, $asunto, utf8_encode($mensaje), $header);
	}



	public function enviarEmailCarrito($mensaje,$para){
		logEmailGral("Envio enviarEmailAltaUsuario\nmensajeTexto: $mensaje\nusuario $para\n");
		if ($this->getEnviar() == true){
			$this->setEnviar(false);

			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer
			$mail = new PHPMailer();

			$mail->IsSMTP();
			//$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true;  // authentication enabled
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			$mail->Host = $GLOBALS['configuration_email']['host']; 

			$mail->Port = 587;
			$mail->Username = $GLOBALS['configuration_email']['user'];
			$mail->Password = $GLOBALS['configuration_email']['pass'];
			
			$mail->From = $GLOBALS['configuration_email']['from'];
			$mail->FromName = $GLOBALS['configuration_email']['from_name'];
			$mail->Subject = "Finalizar orden de compra";

			//$mail->AddAddress("rikizito@gmail.com","TEST");
			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress($para,$para);

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = $mensaje;

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				$this->setStatus("error");
				$this->setMsj(getMsjConf('392')." Err: ".$mail->ErrorInfo);
				logEmailGral("Respuesta ERROR => Envio enviarEmailCarrito\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta: " . getMsjConf('392') . " Err: ".$mail->ErrorInfo . "\n");
			}else{
				logEmailGral("Respuesta OK => Envio enviarEmailCarrito\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta:\n");
			}		

			//Para enviar msj de texto solo
			//$this->setEnviar(true); $this->setStatus("");
			//$this->enviarEmailRecuperar($mensajeTexto,$para);
		}else{
			logEmailGral("Respuesta ERROR => Envio enviarEmailCarrito\nmensajeTexto: $mensaje\nusuario $para\nDetalle Respuesta: " . getMsjConf('342') . "\n");
			$this->setStatus("error");
			$this->setMsj(getMsjConf('342'));
		}
	}


};

?>
