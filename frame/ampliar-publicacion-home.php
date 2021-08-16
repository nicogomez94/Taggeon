<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
#include_once($GLOBALS['configuration']['path_app_admin_objects']."comentario/ComentarioManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");


if ($perfil=='seller' || $perfil='picker'){

    $publicacionManager = new PublicacionManager();
    $productoManager = new ProductoManager();
    #$comentarioManager = new ComentarioManager();
    $seguidoresManager = new SeguidoresManager();

    $cat = (isset($_GET['cat']))  ? $_GET['cat'] : "";
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
        "categoria"     => $publicacionManager->getListCategoria(),
        "publicaciones"     => $publicacionManager->getListPublicacionIndex(),
        "categoria_producto" => $productoManager->getListCategoria(),
	"productos"     => $productoManager->getListProductoIndex(),
   # "comentarios"     => $comentarioManager->getListComentario(),
	"cat"           => $cat,
    "seguidores"     => $seguidoresManager->getListSeguidores(),
    "seguidos"     => $seguidoresManager->getListSeguidos()
    );
    $jsonData = json_encode($jsonData);
    $menuperfil = '';
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido = new Template($nameTemplate);
	$contenido->asigna_variables(array(
            "json" => $jsonData,
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
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
}else{
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
   $contenidoString = Template::sostenedor_error("Permiso denegado {$perfil}");
   echo $contenidoString;
}
Database::Connect()->close();
exit;

?>
