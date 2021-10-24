<?php
include '../app/objects/util/configuration.php';
include_once("../app/objects/sesion/sesionManagerImpl.php");
include_once("../app/objects/util/database.php");
include_once("../app/objects/carrito/CarritoManager.php");
require_once 'vendor/autoload.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");

$objPrincipalManager = new CarritoManager();
$id_carrito = '334';
$usuarioAlta = '414';
$actualizarStock = $objPrincipalManager->actualizarStock($id_carrito,$usuarioAlta);
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

    
?>
