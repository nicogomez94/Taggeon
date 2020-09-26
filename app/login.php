<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");


$sesionManager = new SesionManagerImpl();
$var_usuario = (isset($_POST['mail']))  ? $_POST['mail'] : "";
$var_pass    = (isset($_POST['pass'])) ? $_POST['pass'] : "";

$sesionManager->crear($var_usuario,$var_pass);
	
$statusRet    = $sesionManager->getStatus();
$mensajeRet   = $sesionManager->getMsj();
$objRet = array(
	"status"  => $statusRet,
	"mensaje" => $mensajeRet
);
$ret = json_encode($objRet);
Database::Connect()->close();
echo $ret;
exit;
		
?>
