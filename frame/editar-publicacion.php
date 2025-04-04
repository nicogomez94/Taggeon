<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects'] . "publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");

if ($perfil == 'seller' || $perfil=='picker') {
    $publicacionManager = new PublicacionManager();
    $seguidoresManager = new SeguidoresManager();


    $publicacion =  $publicacionManager->getPublicacion($_GET);
    if ($publicacionManager->getStatus() != "ok"){
        //HEADER
        echo $contenidoStringHeader;
        //FIN HEADER
        $contenidoString = Template::sostenedor_error($publicacionManager->getMsj());
        echo $contenidoString;
echo $contenidoStringFooter;
        Database::Connect()->close();
        exit;
    }
    $productoManager = new ProductoManager();
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
        "publicaciones" => $publicacion,
        "categoria_producto" => $productoManager->getListCategoria(),
        "productos"     => $productoManager->getListProducto(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
    );


    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
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
echo $contenidoStringFooter;
} else {
    header("Location: ".$GLOBALS['configuration']['redirect_home']);
}
Database::Connect()->close();
exit;
