<?php
include '../app/objects/util/configuration.php';
include_once("../app/objects/sesion/sesionManagerImpl.php");
include_once("../app/objects/util/database.php");
include_once("../app/objects/carrito/CarritoManager.php");
include_once("../app/objects/metrica/MetricaManager.php");
require_once 'vendor/autoload.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");


$statusRet  = 'ERROR';
$mensajeRet = 'ERROR'; 

$sesionManager = new SesionManagerImpl();
if ($sesionManager->validar(array('seller','picker'))){


    if (sizeof($_POST) > 0) {
	    $usuarioManager = new UsuarioManagerImpl();
	    $tokenMP = $usuarioManager->getTokenMP();

	    if (!isset($tokenMP) || $tokenMP == ''){
		$objRet = array(
		    "status"  => "ERROR",
		    "mensaje" => "token incorrecto $tokenMP"
		);
		$ret = json_encode($objRet);
		$fp = fopen("/var/www/html/log.txt", 'a');
		fwrite($fp, $ret);
		fclose($fp);
		Database::Connect()->close();
		echo $ret;
		exit;
	    }else{

		$fp = fopen("/var/www/html/log.txt", 'a');
		fwrite($fp, "\n######################################################\njson $tokenMP\n######################################################\n");
		fclose($fp);
	    }
        $objPrincipalManager = new CarritoManager();
$validarStock = $objPrincipalManager->validarStock($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario']);
if ($objPrincipalManager->getStatus() != 'ok'){
	$objRet = array(
	    "status"  => "ERROR",
	    "mensaje" => $objPrincipalManager->getMsj()
	);
	$ret = json_encode($objRet);
	$fp = fopen("/var/www/html/log.txt", 'a');
	fwrite($fp, $ret);
	fclose($fp);
	Database::Connect()->close();
	echo $ret;
	exit;
}
        $objResponse = pagar($tokenMP);
        $statusRet  = $objResponse['status'];
        $mensajeRet = $objResponse['mensaje'];
        $str = "token $tokenMP\n";
        $str .= " status: ".$statusRet." mensaje: ".$mensajeRet."\n";
        $fp = fopen("/var/www/html/log.txt", 'a');
        fwrite($fp, $str);
        fclose($fp);
        

        #$objResponse = pagar('TEST-3352741419059189-050618-af87bf11b26552b6b21a12aebad985b6-755113315');
        #$statusRet  = $objResponse['status'];
        #$mensajeRet = $objResponse['mensaje'];
        #$str = "TEST-3352741419059189-050618-af87bf11b26552b6b21a12aebad985b6-755113315\n";
        #$str .= " status: ".$statusRet." mensaje: ".$mensajeRet."\n";
        #$fp = fopen("/var/www/html/log.txt", 'a');
        #fwrite($fp, $str);
        #fclose($fp);
    } else {
        $statusRet  = 'ERROR';
        $mensajeRet = "Error post.";
    }
}else{
    $statusRet  = 'ERROR';
    $mensajeRet = $sesionManager->getMsj();
}

$objRet = array(
    "status"  => $statusRet,
    "mensaje" => $mensajeRet
);
$ret = json_encode($objRet);
Database::Connect()->close();
echo $ret;
exit;



function pagar ($token){
    MercadoPago\SDK::setAccessToken($token);

    #MercadoPago\SDK::setAccessToken("TEST-3352741419059189-050618-af87bf11b26552b6b21a12aebad985b6-755113315");
    #MercadoPago\SDK::setAccessToken("TEST-3352741419059189-050614-b7e70c4e7455bf1a8ffb36f765ba3da9-754982066");
        
    $payment = new MercadoPago\Payment();
    #$payment->marketplace_fee  = 1.00;
   
    $payment->transaction_amount = (float)$_POST['transactionAmount'];
    $payment->application_fee  = ($payment->transaction_amount * 20)/100;
    #$payment->transaction_amount = $payment->transaction_amount - 1;
    $payment->token = isset($_POST["token"]) ? $_POST["token"] : '';
    $payment->description = "TAGGEON ".$_POST['id_carrito'];
    $payment->installments = isset($_POST["installments"]) ? (int)$_POST['installments'] : 0;
    $payment->payment_method_id = isset($_POST["payment_method_id"]) ? $_POST['paymentMethodId'] : '';
    $payment->issuer_id = isset($_POST["issuer"]) ? (int)$_POST['issuer'] : 0;
      


    $payer = new MercadoPago\Payer();
    $payer->email = $_POST['email'];
    $payer->identification = array(
        "type" => $_POST['docType'],
        "number" => $_POST['docNumber']
    );
    $payment->payer = $payer;

    $payment->save();

            #debug post y response mp
            $str = "Fecha " . date('d/m/Y  H:i:s', time())."\n";
            $str .= "post transactionAmount: ".$payment->transaction_amount."\n";
            $str .= "post application_fee: ".$payment->application_fee."\n";
            $str .= "post token: ".$_POST['token']."\n";
            $str .= "post installments: ".$_POST['installments']."\n";
            $str .= "post paymentMethodId: ".$_POST['paymentMethodId']."\n";
            $str .= "post issuer: ".$_POST['issuer']."\n";
            $str .= "post email: ".$_POST['email']."\n";
            $str .= "post docType: ".$_POST['docType']."\n";
            $str .= "post docNumber: ".$_POST['docNumber']."\n";
            $str .= "post description: TAGGEON ".$_POST['id_carrito']."\n";
            $str .= "post id_carrito: ".$_POST['id_carrito']."\n";
            $str .= "response mp status: ".$payment->status."\n";
            $str .= "response mp status_detail: ".$payment->status_detail."\n";
            $str .= "response mp id: ".$payment->id."\n";
            $str .= "#---------------------------------------------\n";
            $fp = fopen("/var/www/html/log.txt", 'a');
            fwrite($fp, $str);
            fclose($fp);
            #fin debug post y response mp
        
            if (isset($payment) && isset($payment->status) && $payment->status == 'approved'){
                $fp = fopen("/var/www/html/log.txt", 'a');
                $str = "################################## METRICA\n";
                fwrite($fp, $str);
        	$metricalManager = new MetricaManager();
		$metrica = $metricaManager->procesarMetrica($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario']);
                $str = "FIN METRICA\n";
                fwrite($fp, $str);
                fclose($fp);

                $str = "Paso 1\n";
                $fp = fopen("/var/www/html/log.txt", 'a');
                fwrite($fp, $str);
                fclose($fp);
        	$objPrincipalManager = new CarritoManager();
                $objPrincipalManager->cambiarEstadoMayor3($_POST,4);
                if ($objPrincipalManager->getStatus() == 'OK') {
                    $str = "OK\n";
                    $fp = fopen("/var/www/html/log.txt", 'a');

                    fwrite($fp, $str);
                    fclose($fp);
                    $statusRet  = 'OK';
                    $mensajeRet = $payment->id;



			$actualizarStock = $objPrincipalManager->actualizarStock($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario']);
			if ($objPrincipalManager->getStatus() != 'ok'){
				$objRet = array(
				    "status"  => "ERROR",
				    "mensaje" => "Error al descontar el stock:".$objPrincipalManager->getMsj()
				);
				$ret = json_encode($objRet);
				$fp = fopen("/var/www/html/log.txt", 'a');
				fwrite($fp, $ret);
				fclose($fp);
				Database::Connect()->close();
				echo $ret;
				exit;
			}

		    
                } else {
                    $str = "ERROR\n";
                    $fp = fopen("/var/www/html/log.txt", 'a');
                    fwrite($fp, $str);
                    fclose($fp);
                    $statusRet  = 'ERROR';
                    $mensajeRet = $objPrincipalManager->getMsj();
                }
            }else{
                
                $str = "Paso else\n";
                $fp = fopen("/var/www/html/log.txt", 'a');
                fwrite($fp, $str);
                fclose($fp);
                if (isset($payment)){
                    $str = "Paso else 1\n";
                    $fp = fopen("/var/www/html/log.txt", 'a');
                    fwrite($fp, $str);
                    fclose($fp);
                    if (isset($payment->status)){
                        $str .= "Paso else 2\n";
                        $fp = fopen("/var/www/html/log.txt", 'a');
                        fwrite($fp, $str);
                        fclose($fp);
                        if ($payment->status == 'approved'){
                            $str .= "Paso else 3\n";
                            $fp = fopen("/var/www/html/log.txt", 'a');
                            fwrite($fp, $str);
                            fclose($fp);
                        }
                    } 
                } 
                
                $statusRet  = 'ERROR';
                $mensajeRet = $payment->status;
            }

            $objRet = array(
                "status"  => $statusRet,
                "mensaje" => $mensajeRet
            );
            return $objRet;
}


    
?>
