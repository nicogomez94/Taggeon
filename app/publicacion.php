<?php

include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/publicacion/PublicacionManager.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';
$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new PublicacionManager();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
#if ($sesionManager->validarPublic(array('anonymous'))){
if ($sesionManager->validar(array('seller','picker'))){
if (sizeof($_POST) > 0) {
    $var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
    if (preg_match('/^(alta|editar|listar|get|eliminar|subescena|subescena2)$/i', $var_accion)) {
        if ($var_accion == 'alta') {
            $objPrincipalManager->agregarPublicacion($_POST);
            $statusRet  = 'OK';
            $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
	    }else if($var_accion == 'subescena') {
            $objPrincipalManager->searchSubEscena($_POST);
            $statusRet  = 'OK';
            $mensajeRet = $objPrincipalManager->getMsj();
	    }else if($var_accion == 'subescena2') {
            $objPrincipalManager->searchSubEscena2($_POST);
            $statusRet  = 'OK';
            $mensajeRet = $objPrincipalManager->getMsj();
        }else if($var_accion == 'editar') {
            $objPrincipalManager->modificarPublicacion($_POST);
            $statusRet  = 'OK';
            $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
        }else if($var_accion == 'eliminar'){
            $objPrincipalManager->eliminarPublicacion($_POST);
            if ($objPrincipalManager->getStatus() == 'OK') {
                $statusRet  = 'OK';
                $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
            } else {
                $statusRet  = 'ERROR post';
                $mensajeRet = $objPrincipalManager->getMsj();
            }
        }else if($objPrincipalManager->getStatus() != 'OK') {
            $statusRet  = 'ERROR1';
            $mensajeRet = $objPrincipalManager->getMsj();
        }
    }else {
        $statusRet  = 'ERROR2';
        $mensajeRet = "Acción incorrecta.";
    }
} else {
    $var_accion = (isset($_GET['accion']))  ? $_GET['accion'] : "ninguna";
    if ($var_accion == 'eliminar') {
    	$objPrincipalManager->eliminarPublicacion($_GET);
        if ($objPrincipalManager->getStatus() == 'OK') {
            $statusRet  = 'OK';
            $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
        } else {
            $statusRet  = 'ERROR3';
            $mensajeRet = $objPrincipalManager->getMsj();
        }

    }else{
    	$statusRet  = 'ERROR4';
    	$mensajeRet = "Error post.";
    }
}
}else{
	$statusRet  = 'ERROR6';
	$mensajeRet = $sesionManager->getMsj();
}

$objRet = array(
    "status"  => $statusRet,
    "mensaje" => $mensajeRet
);
$ret = json_encode($objRet,JSON_INVALID_UTF8_IGNORE);
Database::Connect()->close();
echo $ret;
exit;
