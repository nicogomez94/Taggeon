<?php
include '../app/objects/util/configuration.php';
include_once("../app/objects/sesion/sesionManagerImpl.php");
include_once("../app/objects/util/database.php");
include_once("../app/objects/carrito/CarritoManager.php");
require_once 'vendor/autoload.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");


$statusRet  = 'ERROR';
$mensajeRet = 'ERROR'; 

$sesionManager = new SesionManagerImpl();
if ($sesionManager->validar(array('seller','picker'))){


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
    if (sizeof($_POST) > 0) {
        $objPrincipalManager = new CarritoManager();
	if ($objPrincipalManager->validarStock() == false){
		$objRet = array(
		    "status"  => "ERROR",
		    "mensaje" => "Los siguientes productos se encuentran sin stock:".$objPrincipalManager->getMsj();
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
    $payment->transaction_amount = (float)$_POST['transactionAmount'];
    $payment->token = $_POST['token'];
    $payment->description = "TAGGEON ".$_POST['id_carrito'];
    $payment->installments = (int)$_POST['installments'];
    $payment->payment_method_id = $_POST['paymentMethodId'];
    $payment->issuer_id = (int)$_POST['issuer'];
      


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
            $str .= "post transactionAmount: ".$_POST['transactionAmount']."\n";
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
                $str = "Paso 1\n";
                $fp = fopen("/var/www/html/log.txt", 'a');
                fwrite($fp, $str);
                fclose($fp);
                $objPrincipalManager->cambiarEstadoMayor3($_POST,4);
                if ($objPrincipalManager->getStatus() == 'OK') {
                    $str = "OK\n";
                    $fp = fopen("/var/www/html/log.txt", 'a');

                    fwrite($fp, $str);
                    fclose($fp);
                    $statusRet  = 'OK';
                    $mensajeRet = $payment->id;
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
