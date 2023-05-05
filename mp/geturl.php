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


    if (sizeof($_GET) > 0) {
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
        $validarStock = $objPrincipalManager->validarStock($_GET['id'],$GLOBALS['sesionG']['idUsuario']);
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
        MercadoPago\SDK::setAccessToken($tokenMP);
    
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
            //fee comision
            $comisionTaggeador = $GLOBALS['configuration']['comision_taggeador'];
            $comisionMarket    = $GLOBALS['configuration']['comision_market'];
            $comisionTotal     =  $comisionTaggeador + $comisionMarket;
            $payment->application_fee  = ($payment->transaction_amount * $comisionTotal)/100;
    
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        $preference->items = $items;
        $preference->auto_return = "approved";
        $preference->payments = array($payment);
    
        $preference->back_urls = array(
            'failure' => "",
            'pending' => "",
            'success' => "http://168.181.186.206/mis-compras.html" // con colocar este es mas que suficiente
        );
    
        $preference->binary_mode = true;
        $preference->notification_url = "http://168.181.186.206/mp/notification-mp.php&id_carrito=".$_GET["id"]; //para escuchar el evento de pago y validarlo
    
        //Datos que puedes enviar para guardar en el pago y usarlo en el webhook
        //para comprobar lo que quieras id usuario id etc etc
        $metadata = array(
            "id_carrito" => $_GET["id"]
        );
        $preference->metadata = $metadata;
        $preference->save();
    
        //header('Location: '.$preference->init_point);
        $objRetD = array(
            "status"  => "OK",
            "mensaje" => $preference->init_point
        );
        $retD = json_encode($objRetD);
        Database::Connect()->close();
        echo $retD;
        exit;
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
