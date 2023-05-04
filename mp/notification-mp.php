<?php
    include '../app/objects/util/configuration.php';
include_once("../app/objects/sesion/sesionManagerImpl.php");
include_once("../app/objects/util/database.php");
include_once("../app/objects/carrito/CarritoManager.php");
include_once("../app/objects/metrica/MetricaManager.php");
require_once 'vendor/autoload.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
    require_once 'vendor/autoload.php';
	ob_start();
    MercadoPago\SDK::setAccessToken("TEST-2892726168082494-032501-ab7e4692f39158920f69b50fcdb4177e-594511111");
    $payment = MercadoPago\Payment::find_by_id($_GET["data_id"]);

    if($payment->status == "approved"){
	
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
