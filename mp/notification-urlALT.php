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
        
        
        if (isset($payment) && isset($payment->status) && $payment->status == 'approved'){
            $str = "################################## METRICA\n";
        logError($str);
        $metricaManager = new MetricaManager();
        if ($metricaManager->procesarMetrica($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario'],$payment->id) === false){
            if ($metricaManager->getStatus() != 'ok'){
                $objRet = array(
                    "status"  => "ERROR",
                    "mensaje" => $metricaManager->getMsj()
                );
                $ret = json_encode($objRet);
                logError($ret);

                $str = "FIN METRICA\n";
                logError($str);

                Database::Connect()->close();
                echo $ret;
                exit;
            }

        }
            $str = "FIN METRICA\n";
        logError($str);


        /****************/ 
        $str = "Paso 1\n";
        logError($str);
        $objPrincipalManager = new CarritoManager();
            $objPrincipalManager->cambiarEstadoMayor3($_POST,4);
            if ($objPrincipalManager->getStatus() == 'OK') {
                $str = "OK\n";
            logError($str);
                $statusRet  = 'OK';
                $mensajeRet = $payment->id;
        /****************/


        $actualizarStock = $objPrincipalManager->actualizarStock($_POST['id_carrito'],$GLOBALS['sesionG']['idUsuario']);
        if ($objPrincipalManager->getStatus() != 'ok'){
            $objRet = array(
                "status"  => "ERROR",
                "mensaje" => "Error al descontar el stock:".$objPrincipalManager->getMsj()
            );
            $ret = json_encode($objRet);
                logError($ret);
            Database::Connect()->close();
            echo $ret;
            exit;
        }
}else{
    $statusRet  = 'ERROR';
    $mensajeRet = $sesionManager->getMsj();
}


?>