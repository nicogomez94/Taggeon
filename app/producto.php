<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/producto/ProductoManager.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';
$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new ProductoManager();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
#if ($sesionManager->validarPublic(array('anonymous'))){
if ($sesionManager->validar(array('seller'))){
if (sizeof($_POST) > 0) {
    $var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
    if (preg_match('/^(alta|editar|listar|get|eliminar|importar)$/i', $var_accion)) {
        if ($var_accion == 'alta') {
            $objPrincipalManager->agregarProducto($_POST);
        } else if ($var_accion == 'editar') {
            $objPrincipalManager->modificarProducto($_POST);
        } else if ($var_accion == 'importar') {
            $objPrincipalManager->importarProducto($_POST);
        } else if ($var_accion == 'eliminar') {
            $objPrincipalManager->eliminarProducto($_POST);
        }
        if ($objPrincipalManager->getStatus() == 'OK') {
            $statusRet  = 'OK';
            $mensajeRet = "La solicitud se proceso con éxito. Id: ".$objPrincipalManager->getMsj();
        } else {
            $statusRet  = 'ERROR';
            $mensajeRet = $objPrincipalManager->getMsj();
        }
    } else {
        $statusRet  = 'ERROR';
        $mensajeRet = "Acción incorrecta.";
    }
} else {
    $var_accion = (isset($_GET['accion']))  ? $_GET['accion'] : "ninguna";
    if (preg_match('/^(foto)$/i', $var_accion)) {
        if ($var_accion == 'foto') {
            $foto = $objPrincipalManager->getFoto($_GET);
	    Database::Connect()->close();
	    echo $foto;
	    exit;
        } 
    }else{
    	$statusRet  = 'ERROR';
    	$mensajeRet = "Error post.";
    }
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
