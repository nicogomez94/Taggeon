<?php
    include '../app/objects/util/configuration.php';
    include_once("../app/objects/sesion/sesionManagerImpl.php");
    include_once("../app/objects/util/database.php");
    include_once("../app/objects/carrito/CarritoManager.php");
    include_once("../app/objects/metrica/MetricaManager.php");
    require_once 'vendor/autoload.php';
    include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
    require_once 'vendor/autoload.php';

	/*daniel*/
    ob_start();
    MercadoPago\SDK::setAccessToken("APP_USR-3405785545373923-050417-b173efb7d95e42e94d962850e36ae301-443471850");
    $payment = MercadoPago\Payment::find_by_id($_GET["data_id"]);
	/*daniel*/

    if($payment->status == "approved"){
        
        /***actualizo metrica */
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


        /********actualizo carrito********/ 
        $str = "Paso 1 - actualizo carrito\n";
        logError($str);
        $objPrincipalManager = new CarritoManager();
            $objPrincipalManager->cambiarEstadoMayor3($_POST,4);
            if ($objPrincipalManager->getStatus() == 'OK') {
                $str = "OK\n";
                logError($str);
                $statusRet  = 'OK';
                $mensajeRet = $payment->id;


            /********actualizo stock********/ 
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
            $str = "ERROR\n";
            logError($str);
            $statusRet  = 'ERROR';
            $mensajeRet = $objPrincipalManager->getMsj();
        }
        //DANIELDANIELDANIELDANIEL
    
                $id_carrito = $payment->metadata->id_carrito;
                    $estado = 4;

                    $sql = <<<SQL
            UPDATE
                `carrito`
            SET
                `estado` = $estado
            WHERE
            `id` = $id_carrito AND
            `estado` >=  2
            SQL;

                mysqli_query(Database::Connect(), $sql);

    }

        print_r($_REQUEST);
        $data = ob_get_contents();
        ob_end_clean();
        file_put_contents("log.txt",$data);
       
?>
