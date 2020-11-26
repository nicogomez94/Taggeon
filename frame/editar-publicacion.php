<?php
include_once($GLOBALS['configuration']['path_app_admin_objects'] . "util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects'] . "publicacion/PublicacionManager.php");


if ($perfil == 'seller') {
    $publicacionManager = new PublicacionManager();

    $publicacion =  $publicacionManager->getPublicacion($_GET);
    if ($publicacionManager->getStatus() != "ok"){
        //HEADER
        echo $contenidoStringHeader;
        //FIN HEADER
        $contenidoString = Template::sostenedor_error($publicacionManager->getMsj());
        echo $contenidoString;
        Database::Connect()->close();
        exit;
    }

    $jsonData = array(
        "usuario"       => $GLOBALS['sesionG']['usuario'],
        "nombre"        => $GLOBALS['sesionG']['nombre'],
        "apellido"      => $GLOBALS['sesionG']['apellido'],
        "contacto"      => $GLOBALS['sesionG']['email'],
        "categoria"     => $publicacionManager->getListCategoria(),
        "publicaciones" => $publicacion
    );


    $jsonData = json_encode($jsonData);
    //$urlEditar = ($perfil == 'seller') ? "/editar-usuario-seller.html": '/editar-usuario.html';
    $contenido = new Template($nameTemplate);
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido->asigna_variables(array(
        "json"        => $jsonData,
        "usuario"     => $GLOBALS['sesionG']['usuario'],
        "nombre"      => $GLOBALS['sesionG']['nombre'] . " " . $GLOBALS['sesionG']['apellido'],
        "apellido"    => $GLOBALS['sesionG']['apellido'],
        "contacto"    => $GLOBALS['sesionG']['email'],
        "url_editar"  => "/editar-usuario.html",
	    "id"          => $idEditar,
        "foto-perfil" => $fotoPerfil //fotoPerfil definida en header.php
    ));
    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER

    echo $contenidoString;
} else {
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
    $contenidoString = Template::sostenedor_error("Permiso denegado");
    echo $contenidoString;
}
Database::Connect()->close();
exit;
