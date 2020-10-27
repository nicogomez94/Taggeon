<?php
include_once($GLOBALS['configuration']['path_app_admin_objects'] . "util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects'] . "producto/ProductoManager.php");


if ($perfil == 'seller') {
    $productoManager = new ProductoManager();

    $producto =  $productoManager->getProducto($_GET);
    if ($productoManager->getStatus() != "ok"){
        //HEADER
        echo $contenidoStringHeader;
        //FIN HEADER
        $contenidoString = Template::sostenedor_error($productoManager->getMsj());
        echo $contenidoString;
        Database::Connect()->close();
        exit;
    }

    $jsonData = array(
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre"      => $GLOBALS['sesionG']['nombre'],
        "apellido"    => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email'],
        "categoria" => $productoManager->getListCategoria(),
        "rubro"     => $productoManager->getListRubro(),
        "productos" => $producto
    );


    $jsonData = json_encode($jsonData);
    //$urlEditar = ($perfil == 'seller') ? "/editar-usuario-seller.html": '/editar-usuario.html';
    $contenido = new Template($nameTemplate);
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido->asigna_variables(array(
        "json" => $jsonData,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre" => $GLOBALS['sesionG']['nombre'] . " " . $GLOBALS['sesionG']['apellido'],
        "apellido" => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email'],
        "url_editar" => "/editar-usuario.html",
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
