<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
  
$error = "";
$sesionManager = new SesionManagerImpl();
if ($sesionManager->validar(array('picker','seller'))){
	if (sizeof($_POST) > 0){
		$var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
		if ( $var_accion == "guardar"){
			$usuarioManager = new UsuarioManagerImpl();
			$usuario = $usuarioManager->actualizarPass();

			if ($usuarioManager->getStatus() == 'ok'){
				$statusRet  = 'OK';
				$mensajeRet = 'La clave se modifico con &eacute;xito.';
			}else{
				$statusRet  = 'ERROR';
				$mensajeRet = $usuarioManager->getMsj();
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
