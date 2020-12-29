<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/carrito/CarritoManager.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR'; 

$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new CarritoManager();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
#if ($sesionManager->validarPublic(array('anonymous'))){
if ($sesionManager->validar(array('seller','picker'))){
if (sizeof($_POST) > 0) {
    $var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
    if (preg_match('/^(alta|editar|listar|get|eliminar|finalizar|finalizar2)$/i', $var_accion)) {
        if ($var_accion == 'alta') {
            $objPrincipalManager->agregarCarrito($_POST);
        } else if ($var_accion == 'finalizar2') {
            $objPrincipalManager->finalizarCarrito2($_POST);
        } else if ($var_accion == 'finalizar') {
            $objPrincipalManager->finalizarCarrito($_POST);
        } else if ($var_accion == 'editar') {
            $objPrincipalManager->modificarCarrito($_POST);
        } else if ($var_accion == 'eliminar') {
            $objPrincipalManager->eliminarCarrito($_POST);
        }
        if ($objPrincipalManager->getStatus() == 'OK') {
            $statusRet  = 'OK';
            $mensajeRet = "La solicitud se proceso con éxito";
        } else {
            $statusRet  = 'ERROR';
            $mensajeRet = $objPrincipalManager->getMsj();
        }
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
