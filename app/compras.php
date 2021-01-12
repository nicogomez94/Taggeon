<?php
include 'objects/util/configuration.php';
include_once("objects/util/database.php");
include_once("objects/carrito/CarritoManager.php");
$statusRet  = 'ERROR';
$mensajeRet = 'ERROR'; 

$objPrincipalManager = new CarritoManager();


$var_accion = (isset($_GET['accion']))  ? $_GET['accion'] : "ninguna";
if (preg_match('/^(comprar)$/i', $var_accion)) {
    if ($var_accion == 'comprar') {
    }
}

if (preg_match('/^(comprar)$/i', $var_accion)) {
    if ($var_accion == 'comprar') {
        $objPrincipalManager->finalizarCompra($_GET);
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

$objRet = array(
    "status"  => $statusRet,
    "mensaje" => $mensajeRet
);
$ret = json_encode($objRet);
Database::Connect()->close();
echo $ret;
exit;
