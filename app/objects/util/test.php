<?php
			require("class.phpmailer.php"); //Importamos la función PHP class.phpmailer
			$mail = new PHPMailer();

			$mail->IsSMTP();
			$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true;  // authentication enabled
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			$mail->Host = "smtp.gmail.com"; 

			$mail->Port = 587;
			$mail->Username = "arquipick@gmail.com";
			$mail->Password = "LosGomez2020@";
			
			$mail->From = "arquipick@gmail.com";
			$mail->FromName = "Arquipick";
			$mail->Subject = "test";

			//$mail->AddAddress("rikizito@gmail.com",$usuario);
			$mail->AddAddress("licrzito@gmail.com","test");

			$mail->WordWrap = 50;

			$mail->IsHTML(true);
			$mail->Body = "hola";

			// Notificamos al usuario del estado del mensaje

			if(!$mail->Send()){
				echo "Err: ".$mail->ErrorInfo . "\n";
			}else{
				echo "ok";
			}
echo "\n\n";
?>
