<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/clienteManagerImpl.php");

$statusRet  = 'ERROR';

$mensajeRet = 'No se permiten altas nuevas de usuarios pickers';
$objRet = array(
	"status"  => $statusRet,
	"mensaje" => $mensajeRet
);
$ret = json_encode($objRet);
Database::Connect()->close();
echo $ret;
exit;

$sesionManager = new SesionManagerImpl();
$clienteManager = new ClienteManagerImpl();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
if ($sesionManager->validarPublic(array('anonymous'))){
#if ($sesionManager->validar(array('admin','superadmin'))){
	$cliente;
	if (sizeof($_POST) > 0){
		$var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
		if ( $var_accion == "guardar"){
			$cliente = $clienteManager->crear(); 
			if ($clienteManager->getStatus() == 'ok'){
				$statusRet  = 'OK';
				$mensajeRet = 'El usuario se creo con éxito';
			}else{
				$statusRet  = 'ERROR';
				$mensajeRet = $clienteManager->getMsj();
			}
		}else{
			$statusRet  = 'ERROR';
			$mensajeRet = "Acción incorrecta.";
		}
	}else{
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
?>
