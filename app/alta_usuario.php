<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");

$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';

$usuario_var = (isset($_POST["mail"]) ? $_POST["mail"] : '');
$pass = (isset($_POST["pass"]) ? $_POST["pass"] : '');
$pass2 = (isset($_POST["cpass"]) ? $_POST["cpass"] : '');
$usuarioManager = new UsuarioManagerImpl();
$usuario = $usuarioManager->crear($usuario_var,$pass,$pass2,'picker');
if ($usuarioManager->getStatus() == 'ok'){
	$statusRet  = 'OK';
	$mensajeRet = '';
}else{
	$statusRet  = 'ERROR';
	$mensajeRet = $usuarioManager->getMsj();
}

$objRet = array(
	"status"  => $statusRet,
	"mensaje" => $mensajeRet
);
$ret = json_encode($objRet);
Database::Connect()->close();
echo $ret;
exit;


?>
