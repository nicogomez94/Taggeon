<?php
    require_once 'vendor/autoload.php';
    include '../app/objects/util/configuration.php';
    include_once("../app/objects/sesion/sesionManagerImpl.php");
    include_once("../app/objects/util/database.php");
    include_once("../app/objects/carrito/CarritoManager.php");
    include_once("../app/objects/metrica/MetricaManager.php");
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
        	logError($ret);
		Database::Connect()->close();
		echo $ret;
		exit;
	    }else{
		logError("\n######################################################\njson $tokenMP\n######################################################\n");
	    }
        $objPrincipalManager = new CarritoManager();
        $validarStock = $objPrincipalManager->validarStock($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario']);
        if ($objPrincipalManager->getStatus() != 'ok'){
            $objRet = array(
                "status"  => "ERROR",
                "mensaje" => $objPrincipalManager->getMsj()
            );
            $ret = json_encode($objRet);
            logError($ret);
            Database::Connect()->close();
            echo $ret;
            exit;
        }

        /* daniel *//* daniel *//* daniel *//* daniel */
        $items = array();
        MercadoPago\SDK::setAccessToken("TEST-2892726168082494-032501-ab7e4692f39158920f69b50fcdb4177e-594511111");
    
        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = $_GET["title"];
        $item->quantity = 1;
        $item->unit_price = $_GET["price"];
        $item->descripion = $_GET["title"];
        $item->currency_id = "ARS";
        array_push($items, $item);
    
        //Creamos un payment
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $_GET["price"];
        $payment->description = $_GET["title"];
        $payment->installments = 1;
        $payment->live_mode = true;
    
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        $preference->items = $items;
        $preference->auto_return = "approved";
        $preference->payments = array($payment);
    
        $preference->back_urls = array(
            'failure' => "",
            'pending' => "",
            'success' => "http://www.taggeon.com/mis-compras.html" // con colocar este es mas que suficiente
        );
    
        $preference->binary_mode = true;
        $preference->notification_url = "http://www.taggeon.com/mp/notification-mp.php"; //para escuchar el evento de pago y validarlo
    
        //Datos que puedes enviar para guardar en el pago y usarlo en el webhook
        //para comprobar lo que quieras id usuario id etc etc
        $metadata = array(
            "id_carrito" => $_GET["id"]
        );
        $preference->metadata = $metadata;
        $preference->save();
    
        header('Location: '.$preference->init_point);
        /*daniel*//*daniel*//*daniel*/

    }else{
        $statusRet  = 'Se esperaba POST en ves de GET';
        Database::Connect()->close();
        echo $statusRet;
        exit;
    }
    
}else{
    $statusRet  = 'ERROR - SESION INCORRECTA';
    Database::Connect()->close();
    echo $statusRet;
    exit;
}
    



?>
