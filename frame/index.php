<?php
//HEADER
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");
echo $contenidoStringHeader;
//FIN HEADER

    $publicacionManager = new PublicacionManager();
    $seguidoresManager = new SeguidoresManager();

    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
    $jsonData = array(
        "tokenMercadoPago" => $tokenMercadoPago,
    	"perfil"        => $perfil,
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        "publicaciones"     => $publicacionManager->getListPublicacionIndexDinamico(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
    );

    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
    

    $contenido = new Template($nameTemplate);
    $contenido->asigna_variables(array(
        "json" => $jsonData,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre" => $GLOBALS['sesionG']['nombre'] . " " . $GLOBALS['sesionG']['apellido'],
        "apellido" => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email']
    ));
    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER

    echo $contenidoString;
Database::Connect()->close();
exit;
