<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."xx/xxManagerImpl.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';
$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new XXManagerImpl();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
if ($sesionManager->validarPublic(array('anonymous'))){
#if ($sesionManager->validar(array('admin','superadmin'))){
	$objPrincipal;
	if (sizeof($_POST) > 0){
		$var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
		if ( $var_accion == "guardar"){
			$objPrincipal = $objPrincipalManager->callAlMetodo(xx); 
			if ($objPrincipalManager->getStatus() == 'ok'){
				$statusRet  = 'OK';
				$mensajeRet = 'La solicitud se proceso con éxito.';
			}else{
				$statusRet  = 'ERROR';
				$mensajeRet = $objPrincipalManager->getMsj();
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