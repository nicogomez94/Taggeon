<?php
include 'objects/util/configuration.php';
include_once("objects/sesion/sesionManagerImpl.php");
include_once("objects/util/database.php");
include_once("objects/metrica/MetricaManager.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR';
$sesionManager = new SesionManagerImpl();
$objPrincipalManager = new MetricaManager();
//se definio superadminadmin para que no pueda entrar nadie. El dia de manana se ve si se habilita o no esta pantalla
#if ($sesionManager->validarPublic(array('anonymous'))){
if ($sesionManager->validar(array('seller','picker'))){
if (sizeof($_POST) > 0) {
    $var_accion = (isset($_POST['accion']))  ? $_POST['accion'] : "ninguna";
    if (preg_match('/^(solicitud|guardar)$/i', $var_accion)) {
        if ($var_accion == 'alta') {
            $objPrincipalManager->agregarMetrica($_POST);
        } else if ($var_accion == 'editar') {
            $objPrincipalManager->modificarMetrica($_POST);
            
        } else if ($var_accion == 'guardar') {
            $objPrincipalManager->subirComprobante();
        } else if ($var_accion == 'solicitud') {
            $objPrincipalManager->solicitudRetiro($_POST);
        } else if ($var_accion == 'eliminar') {
            $objPrincipalManager->eliminarMetrica($_POST);
        }
        if ($objPrincipalManager->getStatus() == 'OK') {
            $statusRet  = 'OK';
            $mensajeRet = $objPrincipalManager->getMsj();
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
