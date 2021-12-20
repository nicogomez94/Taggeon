<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';
$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new UsuarioManagerImpl();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
#if ($sesionManager->validarPublic(array('anonymous'))){
if ($sesionManager->validar(array('seller'))){
if (sizeof($_POST) > 0) {
    $var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
    if (preg_match('/^(desvincular)$/i', $var_accion)) {
        if ($var_accion == 'desvincular') 
			$objPrincipalManager->desvincularTokenMP();
		if ($objPrincipalManager->getStatus() == 'ok') {
		    $statusRet  = 'OK';
		    $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
		} else {
		    $statusRet  = 'ERROR';
		}
            $mensajeRet = $objPrincipalManager->getMsj();
    } else {
        $statusRet  = 'ERROR';
        $mensajeRet = "Acción incorrecta.";
    }
} else {
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
