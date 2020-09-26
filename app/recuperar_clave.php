<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
  
$error = "";
$sesionManager = new SesionManagerImpl();
$usuarioManager = new UsuarioManagerImpl();

if ($sesionManager->validarPublic(array('anonymous'))){
	$usuario;

	if (sizeof($_POST) > 0){
		$var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
		if ( $var_accion == "recuperar"){
			$usuario = $usuarioManager->recuperar();
			if ($usuarioManager->getStatus() == 'ok'){
				$statusRet  = 'OK';
				$mensajeRet = 'Para continuar con el proceso de restaurar password verifique el email.';
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
